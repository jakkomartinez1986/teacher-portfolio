<?php

namespace App\Http\Controllers\Settings\Document;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Settings\Document\DocumentCategory;

class DocumentCategoryController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-documentcategory')->only(['create', 'store']);
        $this->middleware('permission:editar-documentcategory')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.settings.document.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.settings.document.category.create-edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:document_categories,name',
            'description' => 'nullable|string',
        ]);

        // Convertir a mayúsculas
        $validated['name'] = strtoupper($validated['name']);

        DocumentCategory::create($validated);

        return redirect()->route('settings.document-categories.index')
            ->with('success', 'Categoría de documento creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentCategory $documentCategory)
    {
        
        return view('pages.settings.document.category.show', compact('documentCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentCategory $documentCategory)
    {
        return view('pages.settings.document.category.create-edit', compact('documentCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentCategory $documentCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:document_categories,name,'.$documentCategory->id,
            'description' => 'nullable|string',
        ]);

        // Convertir a mayúsculas
        $validated['name'] = strtoupper($validated['name']);

        $documentCategory->update($validated);

        return redirect()->route('settings.document-categories.index')
            ->with('success', 'Categoría de documento actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentCategory $documentCategory)
    {
        //
    }
}
