<?php

namespace App\Http\Controllers\Settings\Document;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\Document\DocumentType;
use Illuminate\Validation\ValidationException;
use App\Models\Settings\Document\DocumentCategory;

class DocumentTypeController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-documenttype')->only(['create', 'store']);
        $this->middleware('permission:editar-documenttype')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.settings.document.type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DocumentCategory::orderBy('name')->get();
        return view('pages.settings.document.type.create-edit', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
       
    //     $validated = $request->validate([
    //         'category_id' => 'required|exists:document_categories,id',
    //         'name' => 'required|string|max:255|unique:document_types,name',
    //         'description' => 'nullable|string',
    //         'frequency' => 'nullable|string|max:255',
    //         'requires_director' => 'boolean',
    //         'requires_vice_principal' => 'boolean',
    //         'requires_principal' => 'boolean',
    //         'requires_dece' => 'boolean',
    //     ]);
      
    //     // Convertir a mayúsculas
    //     $validated['name'] = strtoupper($validated['name']);
    //     $validated['description'] = $validated['description'] ? strtoupper($validated['description']) : null;
    //     $validated['frequency'] = $validated['frequency'] ? strtoupper($validated['frequency']) : null;

    //     // Asegurar valores booleanos
    //     $validated['requires_director'] = $request->has('requires_director');
    //     $validated['requires_vice_principal'] = $request->has('requires_vice_principal');
    //     $validated['requires_principal'] = $request->has('requires_principal');
    //     $validated['requires_dece'] = $request->has('requires_dece');

    //     DocumentType::create($validated);

    //     return redirect()->route('settings.document-types.index')
    //         ->with('success', 'Tipo de documento creado exitosamente.');
    // }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:document_categories,id',
            'name' => 'required|string|max:255|unique:document_types,name',
            'description' => 'nullable|string|max:500',
            'frequency' => 'nullable|string|max:255',
            'requires_director' => 'sometimes|boolean',
            'requires_vice_principal' => 'sometimes|boolean',
            'requires_principal' => 'sometimes|boolean',
            'requires_dece' => 'sometimes|boolean',
        ]);
    
        // Convertir a mayúsculas
        $validated['name'] = strtoupper($validated['name']);
        $validated['description'] = isset($validated['description']) ? strtoupper($validated['description']) : null;
        $validated['frequency'] = isset($validated['frequency']) ? strtoupper($validated['frequency']) : null;
    
        // Asegurar valores booleanos (false cuando no están presentes)
        $validated['requires_director'] = $request->boolean('requires_director');
        $validated['requires_vice_principal'] = $request->boolean('requires_vice_principal');
        $validated['requires_principal'] = $request->boolean('requires_principal');
        $validated['requires_dece'] = $request->boolean('requires_dece');
    
        try {
            DocumentType::create($validated);
            
            return redirect()->route('settings.document-types.index')
                ->with('success', 'Tipo de documento creado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear el documento: '.$e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        return view('pages.settings.document.type.show', compact('documentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        $categories = DocumentCategory::orderBy('name')->get();
        return view('pages.settings.document.type.create-edit', compact('documentType', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentType $documentType)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:document_categories,id',
                'name' => 'required|string|max:255|unique:document_types,name,'.$documentType->id,
                'description' => 'nullable|string',
                'frequency' => 'nullable|string|max:255',
                'requires_director' => 'sometimes|boolean',
                'requires_vice_principal' => 'sometimes|boolean',
                'requires_principal' => 'sometimes|boolean',
                'requires_dece' => 'sometimes|boolean',
            ]);
        } catch (ValidationException $e) {
            dd($e->errors()); // Muestra exactamente qué campo falló y por qué
        }

        // Convertir a mayúsculas
        $validated['name'] = strtoupper($validated['name']);
        $validated['description'] = $validated['description'] ? strtoupper($validated['description']) : null;
        $validated['frequency'] = $validated['frequency'] ? strtoupper($validated['frequency']) : null;

        // Asegurar valores booleanos
        $validated['requires_director'] = $request->has('requires_director');
        $validated['requires_vice_principal'] = $request->has('requires_vice_principal');
        $validated['requires_principal'] = $request->has('requires_principal');
        $validated['requires_dece'] = $request->has('requires_dece');

        $documentType->update($validated);

        return redirect()->route('settings.document-types.index')
            ->with('success', 'Tipo de documento actualizado exitosamente.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        //
    }
}
