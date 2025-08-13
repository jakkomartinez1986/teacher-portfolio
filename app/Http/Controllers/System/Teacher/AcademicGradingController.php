<?php
namespace App\Http\Controllers\System\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use App\Models\System\Student\Student;
use Illuminate\Support\Facades\Validator;
use App\Models\System\Teacher\GradeSummary;
use App\Models\Settings\Trimester\Trimester;
use App\Models\System\Teacher\ClassSchedule;
use App\Imports\Academic\Import\GradesImport;
use App\Models\System\Teacher\GradeEvaluation;
use App\Models\System\Teacher\FormativeActivity;
use App\Exports\Academic\Export\GradesTemplateExport;

class AcademicGradingController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-academicgrading')->only(['create', 'store']);
        $this->middleware('permission:editar-academicgrading')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    // Mostrar formulario unificado de notas
    public function index(Request $request)
    {
        // Obtener materias que el profesor enseña
        // $subjects = Subject::whereHas('teachers', function($q) {
        //     $q->where('user_id', auth()->id());
        // })->get();
        $subjects=ClassSchedule::where('teacher_id', auth()->id())
            ->with(['subject', 'grade'])
            // ->active()
            ->get()
            ->pluck('subject')
            ->unique('id');

        $trimesters = Trimester::where('status',1)->orderby('id')->get();
        // $grades = Grade::get();
        $grades=ClassSchedule::where('teacher_id', auth()->id())
            ->with(['grade'])
            // ->active()
            ->get()
            ->pluck('grade')
            ->unique('id');

        // Datos seleccionados
        $selectedSubject = $request->subject_id ? Subject::find($request->subject_id) : null;
        $selectedTrimester = $request->trimester_id ? Trimester::find($request->trimester_id) : null;
        $selectedGrade = $request->grade_id ? Grade::find($request->grade_id) : null;

        $activities = collect([]);
        $students = collect([]);
        $formativeGrades = collect([]);
        $summativeGrades = collect([]);
        $gradeSummaries = collect([]);

        if ($selectedSubject && $selectedTrimester && $selectedGrade) {
            // Obtener actividades formativas
            $activities = FormativeActivity::where('subject_id', $selectedSubject->id)
                ->where('trimester_id', $selectedTrimester->id)
                ->orderBy('due_date')
                ->get();

            // Obtener estudiantes del curso ordenados por apellido
            // $students = $selectedGrade->students()->users()
            //     ->orderBy('lastname')
            //     ->orderBy('name')
            //     ->get();

          $students = Student::where('current_grade_id', $selectedGrade->id)
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select([
                'students.*',
                'users.name',
                'users.lastname',
                'users.email',
                'users.dni',
                'users.phone',
                'users.cellphone',
                'users.address',
                'users.profile_photo_path'
            ])
            ->orderBy('users.lastname')
            ->orderBy('users.name')
            ->get();

            // Obtener todas las notas formativas agrupadas por estudiante y actividad
            $formativeGrades = GradeEvaluation::formative()
                ->forSubject($selectedSubject->id)
                ->forTrimester($selectedTrimester->id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->groupBy(['student_id', 'activity_id']);

            // Obtener todas las notas sumativas agrupadas por estudiante y tipo
            $summativeGrades = GradeEvaluation::summative()
                ->forSubject($selectedSubject->id)
                ->forTrimester($selectedTrimester->id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->groupBy(['student_id', 'type']);

            // Obtener los resúmenes de notas
            $gradeSummaries = GradeSummary::where('subject_id', $selectedSubject->id)
                ->where('trimester_id', $selectedTrimester->id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }

        return view('pages.system.academic.grading.index', compact(
            'subjects',
            'trimesters',
            'grades',
            'selectedSubject',
            'selectedTrimester',
            'selectedGrade',
            'activities',
            'students',
            'formativeGrades',
            'summativeGrades',
            'gradeSummaries'
        ));
    }

    // Guardar notas (formativas y sumativas)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:users,id',
            'grades.*.formative' => 'nullable|array',
            'grades.*.formative.*' => 'nullable|numeric|min:0|max:10',
            'grades.*.summative_exam' => 'nullable|numeric|min:0|max:10',
            'grades.*.summative_project' => 'nullable|numeric|min:0|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subject = Subject::find($request->subject_id);
        $trimester = Trimester::find($request->trimester_id);
        $grade = Grade::find($request->grade_id);

        // Validar cantidad mínima de notas formativas según horas del curso
        $this->validateFormativeGradesCount($request, $subject);

        // Procesar cada estudiante
        foreach ($request->grades as $studentId => $gradeData) {
            $this->processStudentGrades(
                $subject, 
                $grade, 
                $trimester, 
                $studentId, 
                $gradeData,
                $request->has('is_draft') // Si es borrador
            );
        }

        $message = $request->has('is_draft') 
            ? 'Borrador de notas guardado correctamente' 
            : 'Notas guardadas definitivamente';

        return redirect()->back()->with('success', $message);
    }

    // Procesar notas de un estudiante
    protected function processStudentGrades($subject, $grade, $trimester, $studentId, $gradeData, $isDraft = false)
    {
        // Guardar notas formativas
        if (isset($gradeData['formative'])) {
            foreach ($gradeData['formative'] as $activityId => $value) {
                // Solo guardar si no es borrador o si el valor no está vacío
                if (!$isDraft || !empty($value)) {
                    GradeEvaluation::updateOrCreate(
                        [
                            'subject_id' => $subject->id,
                            'grade_id' => $grade->id,
                            'trimester_id' => $trimester->id,
                            'student_id' => $studentId,
                            'activity_id' => $activityId,
                            'type' => 'formative'
                        ],
                        [
                            'teacher_id' => auth()->id(),
                            'value' => $value ?: null,
                            'date' => now(),
                            'is_draft' => $isDraft
                        ]
                    );
                }
            }
        }

        // Guardar notas sumativas (examen)
        if (isset($gradeData['summative_exam']) && (!$isDraft || !empty($gradeData['summative_exam']))) {
            GradeEvaluation::updateOrCreate(
                [
                    'subject_id' => $subject->id,
                    'grade_id' => $grade->id,
                    'trimester_id' => $trimester->id,
                    'student_id' => $studentId,
                    'type' => 'summative_exam'
                ],
                [
                    'teacher_id' => auth()->id(),
                    'value' => $gradeData['summative_exam'] ?: null,
                    'date' => now(),
                    'is_draft' => $isDraft
                ]
            );
        }

        // Guardar notas sumativas (proyecto)
        if (isset($gradeData['summative_project']) && (!$isDraft || !empty($gradeData['summative_project']))) {
            GradeEvaluation::updateOrCreate(
                [
                    'subject_id' => $subject->id,
                    'grade_id' => $grade->id,
                    'trimester_id' => $trimester->id,
                    'student_id' => $studentId,
                    'type' => 'summative_project'
                ],
                [
                    'teacher_id' => auth()->id(),
                    'value' => $gradeData['summative_project'] ?: null,
                    'date' => now(),
                    'is_draft' => $isDraft
                ]
            );
        }

        // Actualizar resumen de notas (solo si no es borrador)
        if (!$isDraft) {
            $this->updateGradeSummary($subject, $grade, $trimester, $studentId);
        }
    }

    // Validar cantidad mínima de notas formativas
    protected function validateFormativeGradesCount($request, $subject)
    {
        // Calcular mínimo requerido basado en horas de la materia
        $minFormativeGrades = ceil($subject->hours_per_week * 0.8); // Ejemplo: 0.8 notas por hora semanal
        
        foreach ($request->grades as $studentId => $gradeData) {
            if (isset($gradeData['formative'])) {
                $count = count(array_filter($gradeData['formative'], function($value) {
                    return !is_null($value) && $value !== '';
                }));
                
                if ($count < $minFormativeGrades && !$request->has('is_draft')) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'grades' => "Debe ingresar al menos {$minFormativeGrades} notas formativas por estudiante."
                    ]);
                }
            }
        }
    }

    // Actualizar el resumen de notas del estudiante
    protected function updateGradeSummary($subject, $grade, $trimester, $studentId)
    {
        // Calcular promedio de notas formativas (solo las definitivas, no borradores)
        $formativeAvg = GradeEvaluation::formative()
            ->forSubject($subject->id)
            ->forTrimester($trimester->id)
            ->forStudent($studentId)
            ->where('is_draft', false)
            ->avg('value');

        // Obtener notas sumativas definitivas
        $summativeExam = GradeEvaluation::summative()
            ->forSubject($subject->id)
            ->forTrimester($trimester->id)
            ->forStudent($studentId)
            ->where('type', 'summative_exam')
            ->where('is_draft', false)
            ->first();

        $summativeProject = GradeEvaluation::summative()
            ->forSubject($subject->id)
            ->forTrimester($trimester->id)
            ->forStudent($studentId)
            ->where('type', 'summative_project')
            ->where('is_draft', false)
            ->first();

        // Crear o actualizar el resumen
        $summary = GradeSummary::updateOrCreate(
            [
                'subject_id' => $subject->id,
                'grade_id' => $grade->id,
                'trimester_id' => $trimester->id,
                'student_id' => $studentId
            ],
            [
                'formative_avg' => $formativeAvg,
                'summative_exam' => $summativeExam ? $summativeExam->value : null,
                'summative_project' => $summativeProject ? $summativeProject->value : null,
                'comments' => null
            ]
        );

        // Recalcular nota final
        $summary->recalculateFinalGrade();
    }

    // Descargar plantilla Excel
    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            'trimester_id' => 'required|exists:trimesters,id'
        ]);

        $subject = Subject::find($request->subject_id);
        $grade = Grade::find($request->grade_id);
        $trimester = Trimester::find($request->trimester_id);

        $activities = FormativeActivity::where('subject_id', $subject->id)
            ->where('trimester_id', $trimester->id)
            ->orderBy('due_date')
            ->get();

        $students = $grade->students()
            ->orderBy('lastname')
            ->orderBy('name')
            ->get();

        return Excel::download(
            new GradesTemplateExport($students, $activities), 
            "Plantilla_Notas_{$subject->name}_{$grade->name}_{$trimester->name}.xlsx"
        );
    }

    // Importar notas desde Excel
    public function import(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $subject = Subject::find($request->subject_id);
        $grade = Grade::find($request->grade_id);
        $trimester = Trimester::find($request->trimester_id);

        try {
            Excel::import(new GradesImport($subject, $grade, $trimester), $request->file('file'));

            // Actualizar todos los resúmenes de notas para este grupo
            $students = $grade->students()->pluck('users.id');
            foreach ($students as $studentId) {
                $this->updateGradeSummary($subject, $grade, $trimester, $studentId);
            }

            return redirect()->back()->with('success', 'Notas importadas correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al importar: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Copiar y pegar notas desde Excel
    public function pasteFromExcel(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'excel_data' => 'required|string'
        ]);

        $subject = Subject::find($request->subject_id);
        $grade = Grade::find($request->grade_id);
        $trimester = Trimester::find($request->trimester_id);

        try {
            $this->processPastedData($request->excel_data, $subject, $grade, $trimester);

            // Actualizar todos los resúmenes de notas para este grupo
            $students = $grade->students()->pluck('users.id');
            foreach ($students as $studentId) {
                $this->updateGradeSummary($subject, $grade, $trimester, $studentId);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Notas pegadas correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    // Procesar datos pegados desde Excel
    protected function processPastedData($data, $subject, $grade, $trimester)
    {
        $rows = explode("\n", trim($data));
        $headers = str_getcsv(array_shift($rows));
        
        // Validar estructura básica
        if (!in_array('Estudiante', $headers)) {
            throw new \Exception("El formato de los datos no es válido. La primera columna debe ser 'Estudiante'.");
        }

        // Obtener actividades formativas para validar columnas
        $activities = FormativeActivity::where('subject_id', $subject->id)
            ->where('trimester_id', $trimester->id)
            ->orderBy('due_date')
            ->get();

        foreach ($rows as $row) {
            $rowData = str_getcsv(trim($row));
            if (count($rowData) < 2) continue;

            $studentName = $rowData[0];
            $student = $grade->students()
                ->where(function($q) use ($studentName) {
                    $q->whereRaw("CONCAT(lastname, ' ', name) LIKE ?", ["%{$studentName}%"])
                      ->orWhereRaw("CONCAT(name, ' ', lastname) LIKE ?", ["%{$studentName}%"]);
                })
                ->first();
            
            if (!$student) continue;

            // Procesar notas formativas
            foreach ($activities as $index => $activity) {
                $colIndex = $index + 1; // +1 porque la primera columna es el nombre
                if (isset($rowData[$colIndex]) && is_numeric($rowData[$colIndex])) {
                    $value = min(10, max(0, floatval($rowData[$colIndex])));
                    
                    GradeEvaluation::updateOrCreate(
                        [
                            'subject_id' => $subject->id,
                            'grade_id' => $grade->id,
                            'trimester_id' => $trimester->id,
                            'student_id' => $student->id,
                            'activity_id' => $activity->id,
                            'type' => 'formative'
                        ],
                        [
                            'teacher_id' => auth()->id(),
                            'value' => $value,
                            'date' => now(),
                            'is_draft' => false
                        ]
                    );
                }
            }

            // Procesar notas sumativas (últimas columnas)
            $summativeExamIndex = count($headers) - 2; // Asumimos que las últimas 2 columnas son sumativas
            $summativeProjectIndex = count($headers) - 1;
            
            if (isset($rowData[$summativeExamIndex])) {
                $value = min(10, max(0, floatval($rowData[$summativeExamIndex])));
                
                GradeEvaluation::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'grade_id' => $grade->id,
                        'trimester_id' => $trimester->id,
                        'student_id' => $student->id,
                        'type' => 'summative_exam'
                    ],
                    [
                        'teacher_id' => auth()->id(),
                        'value' => $value,
                        'date' => now(),
                        'is_draft' => false
                    ]
                );
            }

            if (isset($rowData[$summativeProjectIndex])) {
                $value = min(10, max(0, floatval($rowData[$summativeProjectIndex])));
                
                GradeEvaluation::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'grade_id' => $grade->id,
                        'trimester_id' => $trimester->id,
                        'student_id' => $student->id,
                        'type' => 'summative_project'
                    ],
                    [
                        'teacher_id' => auth()->id(),
                        'value' => $value,
                        'date' => now(),
                        'is_draft' => false
                    ]
                );
            }
        }
    }
}