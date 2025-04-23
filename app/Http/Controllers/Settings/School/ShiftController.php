<?php

namespace App\Http\Controllers\Settings\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\School\Year;
use App\Models\Settings\School\Shift;

class ShiftController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-shift')->only(['create', 'store']);
        $this->middleware('permission:editar-shift')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.settings.school.shift.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $years = Year::orderBy('year_name')->get();
        return view('pages.settings.school.shift.create-edit', compact('years'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_id' => 'required|exists:years,id',
            'shift_name' => 'required|string|in:MATUTINA,VESPERTINA,INTENSIVO',
            'status' => 'required|boolean',
        ]);

        Shift::create($validated);

        return redirect()->route('settings.shifts.index')
            ->with('success', 'Jornada creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        return view('pages.settings.school.shift.show', compact('shift'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        $years = Year::orderBy('year_name')->get();
        return view('pages.settings.school.shift.create-edit', compact('shift', 'years'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'year_id' => 'required|exists:years,id',
            'shift_name' => 'required|string|in:MATUTINA,VESPERTINA,INTENSIVO',
            'status' => 'required|boolean',
        ]);

        $shift->update($validated);

        return redirect()->route('settings.shifts.index')
            ->with('success', 'Jornada actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        //
    }
}
