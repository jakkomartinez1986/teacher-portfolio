<?php

namespace App\Http\Controllers\System\Student;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings\School\Grade;
use App\Models\System\Student\Student;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\HeadingRowImport;
use App\Models\System\Teacher\ClassSchedule;
use App\Imports\System\Student\StudentImport;

class StudentController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-student')->only(['create', 'store']);
        $this->middleware('permission:editar-student')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    public function index(Request $request)
    {
      
        $search = $request->input('search');
        $tutoria = ClassSchedule::where('teacher_id', Auth::id())
            ->whereHas('subject', function ($q) {
                $q->where('subject_name', 'like', '%Acompañamiento integral en el aula%');
            })
            ->first();

        // Verifica si existe $tutoria antes de acceder a grade_id
        $gradeId = $tutoria ? $tutoria->grade_id : null;

        // Si no hay $gradeId, no filtramos por grado en la consulta de estudiantes
        $students = Student::with(['user', 'currentGrade'])
            ->when($search, function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere('dni', 'like', "%$search%");
                });
            })
            ->when($gradeId, function($query) use ($gradeId) {
                $query->where('current_grade_id', $gradeId);
            })
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.lastname', 'asc')
            ->orderBy('users.name', 'asc')
            ->select('students.*')
            ->paginate(15);

        // Si no hay $gradeId, obtenemos todos los grados (o manejamos otro caso)
        $grades = $gradeId 
            ? Grade::where('id', $gradeId)->orderBy('grade_name')->get()
            : Grade::orderBy('grade_name')->get(); // Opción alternativa: todos los grados

        return view('pages.system.students.index', compact('students', 'grades', 'search', 'gradeId'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         // Obtener solo los grados que el profesor actual enseña
        // $teachingGradeIds = Auth::user()->classSchedules()
        //                     ->pluck('grade_id')
        //                     ->unique()
        //                     ->toArray();
        $teachingGradeIds = Auth::user()->classSchedules()
        ->whereHas('subject', fn($q) => $q->where('subject_name', 'Acompañamiento integral en el aula'))
        ->pluck('grade_id')
        ->unique()
        ->toArray();

        $grades = Grade::whereIn('id', $teachingGradeIds)
                ->orderBy('grade_name')
                ->get();

        return view('pages.system.students.create-edit', [
            'student' => null,
            'grades' => $grades,
            'mode' => 'create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'dni' => 'required|string|max:10|unique:users,dni',
    //         'name' => 'required|string|max:100',
    //         'lastname' => 'required|string|max:100',
    //         'email' => 'nullable|email|max:100|unique:users,email',
    //         'phone' => 'nullable|string|max:10',
    //         'cellphone' => 'nullable|string|max:10',
    //         'address' => 'nullable|string|max:255',
    //         'current_grade_id' => 'required|exists:grades,id',
    //         'enrollment_date' => 'required|date',
    //         'academic_status' => 'required|in:0,1,2,3',
    //         'additional_info' => 'nullable|string',
    //         'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB máximo
    //     ]);

    //     try {
    //         // Procesar la foto de perfil si existe
    //         // $profilePhotoPath = null;
    //         // if ($request->hasFile('profile_photo_path')) {
    //         //     $profilePhotoPath = $request->file('profile_photo_path')->store('profile-photos', 'public');
    //         // }

    //         // Crear primero el usuario
    //         $userData = [
    //             'dni' => $validated['dni'],
    //             'name' => $validated['name'],
    //             'lastname' => $validated['lastname'],
    //             'email' => $validated['email'] ?? null,
    //             'phone' => $validated['phone'] ?? null,
    //             'cellphone' => $validated['cellphone'] ?? null,
    //             'address' => $validated['address'] ?? null,
    //             'password' => bcrypt($validated['dni']),
    //             'school_id' => Auth::user()->school_id,
    //             'status' => 1
    //         ];

    //         // if ($profilePhotoPath) {
    //         //     $userData['profile_photo_path'] = $profilePhotoPath;
    //         // }

    //         $user = User::create($userData);

    //         // Asignar rol de estudiante
    //         $user->assignRole('ESTUDIANTE');
    //         // Manejo de imágenes
    //         $this->handleUserPhotos($user, $request);
    //         // Crear el estudiante asociado
    //         Student::create([
    //             'user_id' => $user->id,
    //             'current_grade_id' => $validated['current_grade_id'],
    //             'enrollment_date' => $validated['enrollment_date'],
    //             'academic_status' => $validated['academic_status'],
    //             'additional_info' => $validated['additional_info'] ?? null
    //         ]);

    //         return redirect()
    //             ->route('students.students.index')
    //             ->with('success', 'Estudiante creado exitosamente.');

    //     } catch (\Exception $e) {
    //         Log::error('Error al crear estudiante: ' . $e->getMessage());
    //         return back()->with('error', 'Error al crear el estudiante.')->withInput();
    //     }
    // }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|ec_cedula|string|max:10|unique:users,dni',
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:users,email',
            'phone' => 'nullable|string|max:10',
            'cellphone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'current_grade_id' => 'required|exists:grades,id',
            'enrollment_date' => 'required|date',
            'academic_status' => 'required|in:0,1,2,3',
            'additional_info' => 'nullable|string',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB máximo
        ]);

        try {
            $userData = [
                'dni' => $validated['dni'],
                'name' => $validated['name'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'cellphone' => $validated['cellphone'] ?? null,
                'address' => $validated['address'] ?? null,
                'password' => bcrypt($validated['dni']),
                'school_id' => Auth::user()->school_id,
                'status' => 1
            ];

            // Subir foto de perfil si existe
            if ($request->hasFile('profile_photo_path')) {
                $path = $request->file('profile_photo_path')->store('students/profile-photos', 'public');
                $userData['profile_photo_path'] = $path;
            }

            $user = User::create($userData);
            $user->assignRole('ESTUDIANTE');

            Student::create([
                'user_id' => $user->id,
                'current_grade_id' => $validated['current_grade_id'],
                'enrollment_date' => $validated['enrollment_date'],
                'academic_status' => $validated['academic_status'],
                'additional_info' => $validated['additional_info'] ?? null
            ]);

            return redirect()->route('students.students.index')->with('success', 'Estudiante creado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al crear estudiante: ' . $e->getMessage());
            return back()->with('error', 'Error al crear el estudiante.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Verificar que el estudiante pertenece a la misma escuela
        if ($student->user->school_id !== Auth::user()->school_id) {
            abort(403, 'No autorizado.');
        }

        // Cargar relaciones necesarias
        $student->load([
            'user',
            'currentGrade',
            'enrollments.grade',
            'enrollments' => function($query) {
                $query->orderBy('enrollment_date', 'desc');
            }
        ]);

        return view('pages.system.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        // Verificar que el estudiante pertenece a la misma escuela
        if ($student->user->school_id !== Auth::user()->school_id) {
            abort(403, 'No autorizado.');
        }

        // Obtener solo los grados que el profesor actual enseña
        $teachingGradeIds = Auth::user()->classSchedules()
                            ->pluck('grade_id')
                            ->unique()
                            ->toArray();

        $grades = Grade::whereIn('id', $teachingGradeIds)
                ->orderBy('grade_name')
                ->get();

        return view('pages.system.students.create-edit', [
            'student' => $student,
            'grades' => $grades,
            'mode' => 'edit'
        ]);
    }

    
    public function update(Request $request, Student $student)
    {
        if ($student->user->school_id !== Auth::user()->school_id) {
            abort(403, 'No autorizado.');
        }

        $validated = $request->validate([
            'dni' => [
                'required', 'string', 'max:10',
                Rule::unique('users', 'dni')->ignore($student->user_id)
            ],
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => [
                'nullable', 'email', 'max:100',
                Rule::unique('users', 'email')->ignore($student->user_id)
            ],
            'phone' => 'nullable|string|max:10',
            'cellphone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'current_grade_id' => 'required|exists:grades,id',
            'enrollment_date' => 'required|date',
            'academic_status' => 'required|in:0,1,2,3',
            'additional_info' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_profile_photo' => 'nullable|boolean'
        ]);

        try {
            $userData = [
                'dni' => $validated['dni'],
                'name' => $validated['name'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'cellphone' => $validated['cellphone'] ?? null,
                'address' => $validated['address'] ?? null,
            ];

            // Eliminar foto si se solicita
            if ($request->boolean('remove_profile_photo')) {
                if ($student->user->profile_photo_path) {
                    Storage::disk('public')->delete($student->user->profile_photo_path);
                }
                $userData['profile_photo_path'] = null;
            }
            // Subir nueva foto
            elseif ($request->hasFile('profile_photo')) {
                if ($student->user->profile_photo_path) {
                    Storage::disk('public')->delete($student->user->profile_photo_path);
                }
                $path = $request->file('profile_photo')->store('students/profile-photos', 'public');
                $userData['profile_photo_path'] = $path;
            }

            $student->user->update($userData);

            $student->update([
                'current_grade_id' => $validated['current_grade_id'],
                'enrollment_date' => $validated['enrollment_date'],
                'academic_status' => $validated['academic_status'],
                'additional_info' => $validated['additional_info'] ?? null
            ]);

            return redirect()->route('students.students.index')->with('success', 'Estudiante actualizado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar estudiante: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el estudiante.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }

    public function showImportForm(Grade $grade)
    {
        //dd($grade);
       
        //$gradeId = $grade->id;
        $nombregrado=$grade->grade_name.' '.$grade->section;
        // $grades = Grade::where('school_id', Auth::user()->school_id)
        $grades = Grade::where('id', $grade->id)
         ->get();
       
       
        return view('pages.system.students.import', compact('grades','nombregrado'));
    }

  
    public function import(Request $request)
    {
        // Validación de entrada
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120', // 5MB máximo
            'grade_id' => 'required|exists:grades,id',
            'enrollment_date' => 'required|date|before_or_equal:today',
        ]);

        try {
            // Validación de estructura del archivo
            $file = $request->file('file');
            $headings = (new HeadingRowImport)->toArray($file)[0][0] ?? [];
            
            $requiredHeaders = ['dni', 'nombres', 'apellidos'];
            $missingHeaders = array_diff($requiredHeaders, $headings);
            
            if (!empty($missingHeaders)) {
                return back()->withError(
                    "El archivo no contiene las columnas requeridas: " . implode(', ', $missingHeaders)
                );
            }

            // Procesar importación
            $import = new StudentImport(
                $request->grade_id,
                $request->enrollment_date,
                Auth::user()->school_id
            );

            Excel::import($import, $file);

            // Manejar resultados
            return $this->handleImportResults($import);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator);
        } catch (\Exception $e) {
            Log::error('Error en importación de estudiantes: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
                'file' => $request->file('file')->getClientOriginalName()
            ]);
            
            return back()->withError(
                "Ocurrió un error durante la importación. Por favor verifica el formato del archivo."
            );
        }
    }

    protected function handleImportResults(StudentImport $import)
    {
        $successCount = $import->getSuccessCount();
        $errorCount = $import->getErrorCount();

        if ($errorCount > 0) {
            return redirect()
                ->back()
                ->with([
                    'error' => "{$errorCount} estudiantes no pudieron ser importados. {$successCount} importados correctamente.",
                    'import_errors' => $import->getErrors(),
                    'success_count' => $successCount // Para mostrar también el éxito
                ]);
        }

        return redirect()
            ->route('students.students.index')
            ->with('success', "{$successCount} estudiantes importados correctamente.");
    }
    public function downloadTemplate()
    {
        return response()->download(public_path('storage/templates/students_import_template.xlsx'));
        
    }

     protected function handleUserPhotos(User $user, Request $request)
    {
        try {
            // Manejar foto de perfil
            if ($request->hasFile('profile_photo_path')) {
                // Eliminar foto anterior si existe
                if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                
                // Guardar nueva foto y almacenar la ruta
                $path = $request->file('profile_photo_path')->store('users/profile-photos', 'public');
                $user->profile_photo_path = $path;
            } elseif ($request->boolean('remove_profile_photo')) {
                // Eliminar foto si se marcó la opción y existe
                if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $user->profile_photo_path = null;
            }

            // Manejar firma
            if ($request->hasFile('signature_photo_path')) {
                // Eliminar firma anterior si existe
                if ($user->signature_photo_path && Storage::disk('public')->exists($user->signature_photo_path)) {
                    Storage::disk('public')->delete($user->signature_photo_path);
                }
                
                // Guardar nueva firma y almacenar la ruta
                $path = $request->file('signature_photo_path')->store('users/signature-photos', 'public');
                $user->signature_photo_path = $path;
            } elseif ($request->boolean('remove_signature_photo')) {
                // Eliminar firma si se marcó la opción y existe
                if ($user->signature_photo_path && Storage::disk('public')->exists($user->signature_photo_path)) {
                    Storage::disk('public')->delete($user->signature_photo_path);
                }
                $user->signature_photo_path = null;
            }

            // Guardar cambios si hubo modificaciones
            if ($user->isDirty(['profile_photo_path', 'signature_photo_path'])) {
                $user->save();
            }

        } catch (\Exception $e) {
            Log::error('Error al manejar fotos de usuario: '.$e->getMessage());
            throw $e;
        }
    }
}
