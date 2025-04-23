<?php

namespace App\Http\Controllers\Settings\School;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Settings\School\Grade;
use App\Models\Settings\School\Nivel;
use App\Models\Document\Document;

class GradeController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-grade')->only(['create', 'store']);
        $this->middleware('permission:editar-grade')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.settings.school.grade.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nivels = Nivel::with('shift')
            ->where('status', 1)
            ->orderBy('nivel_name')
            ->get();
        
        return view('pages.settings.school.grade.create-edit', compact('nivels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'grade_name.numeric' => 'El grado debe ser un número',
            'grade_name.min' => 'El grado mínimo es 1',
            'grade_name.max' => 'El grado máximo es 10',
            'grade_name.unique' => 'Esta combinación de grado y paralelo ya existe para este nivel',
            'section.regex' => 'La sección debe ser una sola letra mayúscula (A-Z)',
        ];
        
        $validated = $request->validate([
            'nivel_id' => 'required|exists:nivels,id',
            'grade_name' => [
                'required',
                'numeric',
                'min:1',
                'max:10',
                Rule::unique('grades')->where(function ($query) use ($request) {
                    return $query->where('nivel_id', $request->nivel_id)
                                ->where('section', strtoupper($request->section));
                })
            ],
            'section' => [
                'required',
                'string',
                'max:1',
                'regex:/^[A-Z]$/',
            ],
            'status' => 'required|boolean',
        ], $messages);
    
        $validated['section'] = strtoupper($validated['section']);
    
        Grade::create($validated);
    
        return redirect()->route('settings.grades.index')
            ->with('success', 'Grado creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        // Cargar documentos relacionados con asignaturas
        $grade->load(['documents' => function($query) {
            $query->with(['subjects' => function($q) {
                $q->select('subjects.id', 'subject_name');
            }])
            ->orderBy('documents.created_at', 'desc')
            ->limit(5);
        }]);
        
        return view('pages.settings.school.grade.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        $nivels = Nivel::with('shift')
            ->where('status', 1)
            ->orderBy('nivel_name')
            ->get();
            
        return view('pages.settings.school.grade.create-edit', compact('grade', 'nivels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $messages = [
            'grade_name.numeric' => 'El grado debe ser un número',
            'grade_name.min' => 'El grado mínimo es 1',
            'grade_name.max' => 'El grado máximo es 10',
            'grade_name.unique' => 'Esta combinación de grado y paralelo ya existe para este nivel',
            'section.regex' => 'La sección debe ser una sola letra mayúscula (A-Z)',
        ];
        
        $validated = $request->validate([
            'nivel_id' => 'required|exists:nivels,id',
            'grade_name' => [
                'required',
                'numeric',
                'min:1',
                'max:10',
                Rule::unique('grades')->where(function ($query) use ($request, $grade) {
                    return $query->where('nivel_id', $request->nivel_id)
                                ->where('section', strtoupper($request->section))
                                ->where('id', '!=', $grade->id);
                })
            ],
            'section' => [
                'required',
                'string',
                'max:1',
                'regex:/^[A-Z]$/',
            ],
            'status' => 'required|boolean',
        ], $messages);
    
        $validated['section'] = strtoupper($validated['section']);
    
        $grade->update($validated);
    
        return redirect()->route('settings.grades.index')
            ->with('success', 'Grado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        // Verificar si tiene documentos relacionados
        // if ($grade->documents()->exists()) {
        //     return redirect()->back()
        //         ->with('error', 'No se puede eliminar el grado porque tiene documentos asociados.');
        // }

        // $grade->delete();

        // return redirect()->route('settings.grades.index')
        //     ->with('success', 'Grado eliminado exitosamente.');
    }

    /**
     * Obtener documentos del grado
     */
    public function getDocuments(Grade $grade)
    {
        $documents = $grade->documents()
            ->with(['subjects', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($documents);
    }
}