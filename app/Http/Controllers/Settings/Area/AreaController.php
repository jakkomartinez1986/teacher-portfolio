<?php

namespace App\Http\Controllers\Settings\Area;

use App\Http\Controllers\Controller;
use App\Models\Settings\Area\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-area')->only(['create', 'store']);
        $this->middleware('permission:editar-area')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.settings.area.area.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.settings.area.area.create-edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_name' => 'required|string|max:255|unique:areas,area_name',
        ]);

        // Convertir a mayúsculas
        $validated['area_name'] = strtoupper($validated['area_name']);

        Area::create($validated);

        return redirect()->route('settings.areas.index')
            ->with('success', 'Área creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        return view('pages.settings.area.area.show', compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        return view('pages.settings.area.area.create-edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'area_name' => 'required|string|max:255|unique:areas,area_name,'.$area->id,
        ]);

        // Convertir a mayúsculas
        $validated['area_name'] = strtoupper($validated['area_name']);

        $area->update($validated);

        return redirect()->route('settings.areas.index')
            ->with('success', 'Área actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        //
    }
}
