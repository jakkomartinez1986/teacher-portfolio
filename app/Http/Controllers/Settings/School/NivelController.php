<?php

namespace App\Http\Controllers\Settings\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\School\Nivel;
use App\Models\Settings\School\Shift;

class NivelController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-nivel')->only(['create', 'store']);
        $this->middleware('permission:editar-nivel')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.settings.school.nivel.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shifts = Shift::with('year')
            ->where('status', 1)
            ->orderBy('shift_name')
            ->get();
            
        return view('pages.settings.school.nivel.create-edit', compact('shifts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'nivel_name' => 'required|string|max:255|unique:nivels,nivel_name,NULL,id,shift_id,'.$request->shift_id,
            'status' => 'required|boolean',
        ]);

        // Convertir a mayúsculas
        $validated['nivel_name'] = strtoupper($validated['nivel_name']);

        Nivel::create($validated);

        return redirect()->route('settings.nivels.index')
            ->with('success', 'Nivel creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Nivel $nivel)
    {
        return view('pages.settings.school.nivel.show', compact('nivel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nivel $nivel)
    {
        $shifts = Shift::with('year')
            ->where('status', 1)
            ->orderBy('shift_name')
            ->get();
            
        return view('pages.settings.school.nivel.create-edit', compact('nivel', 'shifts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nivel $nivel)
    {
        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'nivel_name' => 'required|string|max:255|unique:nivels,nivel_name,'.$nivel->id.',id,shift_id,'.$request->shift_id,
            'status' => 'required|boolean',
        ]);

        // Convertir a mayúsculas
        $validated['nivel_name'] = strtoupper($validated['nivel_name']);

        $nivel->update($validated);

        return redirect()->route('settings.nivels.index')
            ->with('success', 'Nivel actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nivel $nivel)
    {
        //
    }
}
