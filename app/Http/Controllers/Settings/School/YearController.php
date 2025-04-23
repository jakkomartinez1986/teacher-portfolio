<?php

namespace App\Http\Controllers\Settings\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\School\Year;
use App\Models\Settings\School\School;

class YearController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-year')->only(['create', 'store']);
        $this->middleware('permission:editar-year')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.settings.school.year.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('pages.settings.school.school.create-edit',['school' => null]);
        $schools = School::all();
        return view('pages.settings.school.year.create-edit', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);
    
        // Generar el nombre del año automáticamente
        $startYear = date('Y', strtotime($validated['start_date']));
        $endYear = date('Y', strtotime($validated['end_date']));
        $validated['year_name'] = 'Gestión ' . $startYear . '-' . $endYear;
    
        Year::create($validated);
    
        return redirect()->route('settings.years.index')
                         ->with('success', 'Año escolar creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Year $year)
    {
        //
        return view('pages.settings.school.year.show', compact('year'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Year $year)
    {
        //
        $schools = School::all();
        return view('pages.settings.school.year.create-edit', compact('year', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Year $year)
    {
        
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);
    
        // Generar el nombre del año automáticamente
        $startYear = date('Y', strtotime($validated['start_date']));
        $endYear = date('Y', strtotime($validated['end_date']));
        $validated['year_name'] = 'Gestión ' . $startYear . '-' . $endYear;
    
        $year->update($validated);
    
        return redirect()->route('settings.years.index')
                         ->with('success', 'Año escolar actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Year $year)
    {
        //
    }
}
