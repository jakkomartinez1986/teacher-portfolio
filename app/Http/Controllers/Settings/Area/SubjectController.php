<?php

namespace App\Http\Controllers\Settings\Area;

use Illuminate\Http\Request;
use App\Models\Document\Document;
use App\Models\Settings\Area\Area;
use App\Http\Controllers\Controller;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;

class SubjectController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-subject')->only(['create', 'store']);
        $this->middleware('permission:editar-subject')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.settings.area.subject.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::orderBy('area_name')->get();
        return view('pages.settings.area.subject.create-edit', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'subject_name' => 'required|string|max:255|unique:subjects,subject_name,NULL,id,area_id,'.$request->area_id,
        ]);

        // Convertir a mayúsculas
        $validated['subject_name'] = strtoupper($validated['subject_name']);

        Subject::create($validated);

        return redirect()->route('settings.subjects.index')
            ->with('success', 'Asignatura creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        // Cargar los documentos relacionados con sus grados
        // $subject->load(['documents' => function($query) {
        //     $query->withPivot('grade_id');
        // }]);
        
        // return view('pages.settings.area.subject.show', compact('subject'));

        $subject->load(['documents' => function($query) {
            $query->withPivot('grade_id');
        }]);
        
        $availableDocuments = Document::whereNotIn('id', $subject->documents->pluck('id'))->get();
        $grades = Grade::all(); // Asegúrate de importar el modelo Grade
        
        return view('pages.settings.area.subject.show', compact('subject', 'availableDocuments', 'grades'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $areas = Area::orderBy('area_name')->get();
        return view('pages.settings.area.subject.create-edit', compact('subject', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'subject_name' => 'required|string|max:255|unique:subjects,subject_name,'.$subject->id.',id,area_id,'.$request->area_id,
        ]);

        // Convertir a mayúsculas
        $validated['subject_name'] = strtoupper($validated['subject_name']);

        $subject->update($validated);

        return redirect()->route('settings.subjects.index')
            ->with('success', 'Asignatura actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        //
    }
    
    /**
     * Método para asociar documentos a la asignatura
     */
    public function attachDocument(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'document_id' => 'required|exists:documents,id',
            'grade_id' => 'required|exists:grades,id'
        ]);
        
        $subject->documents()->attach($validated['document_id'], [
            'grade_id' => $validated['grade_id']
        ]);
        
        return back()->with('success', 'Documento asociado correctamente.');
    }
    
    /**
     * Método para desasociar documentos de la asignatura
     */
    public function detachDocument(Subject $subject, Document $document)
    {
        $subject->documents()->detach($document->id);
        
        return back()->with('success', 'Documento desasociado correctamente.');
    }
}