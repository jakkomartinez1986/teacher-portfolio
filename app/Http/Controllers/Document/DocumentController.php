<?php

namespace App\Http\Controllers\Document;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Document\Document;
use App\Http\Controllers\Controller;
use App\Models\Settings\School\Year;
use Illuminate\Support\Facades\Auth;
use App\Models\Document\ApprovalFlow;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use Illuminate\Support\Facades\Storage;
use App\Models\Document\DocumentSignature;
use App\Models\Settings\Trimester\Trimester;
use App\Models\Settings\Document\DocumentType;

class DocumentController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-document')->only(['create', 'store']);
        $this->middleware('permission:editar-document')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.document.document.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = DocumentType::all();
        $years = Year::all();
        $trimesters = Trimester::all();
        $subjects = Subject::all();
        $grades = Grade::all();
        $users = User::where('status', true)->get();

        return view('pages.document.document.create-edit', compact(
            'types', 'years', 'trimesters', 'subjects', 'grades', 'users'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_id' => 'required|exists:document_types,id',
            'year_id' => 'required|exists:years,id',
            'trimester_id' => 'nullable|exists:trimesters,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'authors' => 'required|array',
            'authors.*' => 'exists:users,id',
            'subjects' => 'sometimes|array',
            'subjects.*' => 'exists:subjects,id',
            'grades' => 'sometimes|array',
            'grades.*' => 'exists:grades,id',
        ]);

        // Guardar el archivo
        $filePath = $request->file('file')->store('documents', 'public');
        $validated['title'] = strtoupper($validated['title']);
        $validated['description'] = strtoupper($validated['description']);
        // Crear el documento
        $document = Document::create([
            'type_id' => $validated['type_id'],
            'creator_id' => Auth::id(),
            'year_id' => $validated['year_id'],
            'trimester_id' => $validated['trimester_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'status' => 'DRAFT',
        ]);

        // Asignar autores
        foreach ($validated['authors'] as $index => $authorId) {
            $document->authors()->attach($authorId, [
                'is_main_author' => $index === 0 // El primer autor es el principal
            ]);
        }

        // Asignar materias y grados si existen
        if (isset($validated['subjects'])) {
            foreach ($validated['subjects'] as $subjectId) {
                foreach ($validated['grades'] as $gradeId) {
                    $document->subjects()->attach($subjectId, ['grade_id' => $gradeId]);
                }
            }
        }

        // Iniciar flujo de aprobación si no es borrador
        if ($request->status === 'PENDING_REVIEW') {
            $this->initApprovalFlow($document);
        }

        return redirect()->route('documents.documents.show', $document)
            ->with('success', 'Documento creado exitosamente.');
      

    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $document->load([
            'type', 
            'creator', 
            'year', 
            'trimester', 
            'authors', 
            'subjects', 
            'grades',
            'signatures.user',
            'signatures.role'
        ]);      
        return view('pages.document.document.show', compact('document'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        if ($document->status !== 'DRAFT') {
            return redirect()->back()->with('error', 'Solo documentos en estado DRAFT pueden ser editados.');
        }

        $types = DocumentType::all();
        $years = Year::all();
        $trimesters = Trimester::all();
        $subjects = Subject::all();
        $grades = Grade::all();
        $users = User::where('status', true)->get();

        return view('pages.document.document.create-edit', compact(
            'document', 'types', 'years', 'trimesters', 'subjects', 'grades', 'users'
        ));
      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        if ($document->status !== 'DRAFT') {
            return redirect()->back()->with('error', 'Solo documentos en estado DRAFT pueden ser editados.');
        }

        $validated = $request->validate([
            'type_id' => 'required|exists:document_types,id',
            'year_id' => 'required|exists:years,id',
            'trimester_id' => 'nullable|exists:trimesters,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'authors' => 'required|array',
            'authors.*' => 'exists:users,id',
            'subjects' => 'sometimes|array',
            'subjects.*' => 'exists:subjects,id',
            'grades' => 'sometimes|array',
            'grades.*' => 'exists:grades,id',
            'status' => 'sometimes|in:DRAFT,PENDING_REVIEW'
        ]);

        // Actualizar archivo si se proporciona uno nuevo
        if ($request->hasFile('file')) {
            Storage::delete($document->file_path);
            $filePath = $request->file('file')->store('documents', 'public');
            $validated['file_path'] = $filePath;
        }
        $validated['title'] = strtoupper($validated['title']);
        $validated['description'] = strtoupper($validated['description']);
        $document->update($validated);

        // Sincronizar autores
        $authorsData = [];
        foreach ($validated['authors'] as $index => $authorId) {
            $authorsData[$authorId] = ['is_main_author' => $index === 0];
        }
        $document->authors()->sync($authorsData);

        // Sincronizar materias y grados si existen
        if (isset($validated['subjects'])) {
            $document->subjects()->detach();
            $document->grades()->detach();
            
            foreach ($validated['subjects'] as $subjectId) {
                foreach ($validated['grades'] as $gradeId) {
                    $document->subjects()->attach($subjectId, ['grade_id' => $gradeId]);
                }
            }
        }

        // Iniciar flujo de aprobación si se solicita revisión
        if (isset($validated['status']) && $validated['status'] === 'PENDING_REVIEW') {
            $this->initApprovalFlow($document);
        }

        return redirect()->route('documents.documents.show', $document)
            ->with('success', 'Documento actualizado exitosamente.');
    }

    public function submitForReview(Document $document)
    {
        if ($document->status !== 'DRAFT') {
            return redirect()->back()->with('error', 'Solo documentos en estado DRAFT pueden ser enviados para revisión.');
        }

        $document->update(['status' => 'PENDING_REVIEW']);
        $this->initApprovalFlow($document);

        return redirect()->back()->with('success', 'Documento enviado para revisión.');
    
    }
    protected function initApprovalFlow(Document $document)
    {
        $approvalFlows = ApprovalFlow::where('document_type_id', $document->type_id)
            ->orderBy('approval_order')
            ->get();

        foreach ($approvalFlows as $flow) {
            DocumentSignature::create([
                'document_id' => $document->id,
                'role_id' => $flow->role_id,
                'status' => 'PENDING'
            ]);
        }

        // Notificar a los primeros aprobadores
        $this->notifyApprovers($document);
    }
    protected function notifyApprovers(Document $document)
    {
        // Implementar notificación (se verá más adelante con Livewire)
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }
}
