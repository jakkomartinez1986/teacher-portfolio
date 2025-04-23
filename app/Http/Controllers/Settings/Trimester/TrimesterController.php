<?php

namespace App\Http\Controllers\Settings\Trimester;

use App\Http\Controllers\Controller;
use App\Models\Settings\Trimester\Trimester;
use Illuminate\Http\Request;

class TrimesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-trimester')->only(['create', 'store']);
        $this->middleware('permission:editar-trimester')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    public function index()
    {
        //
        return view('pages.settings.trimester.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pages.settings.trimester.create-edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'trimester_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|integer|in:0,1',
        ]);

        Trimester::create($validated);

        return redirect()->route('settings.trimesters.index')
                         ->with('success', 'Trimestre creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trimester $trimester)
    {
        return view('pages.settings.trimester.show', compact('trimester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trimester $trimester)
    {
        return view('pages.settings.trimester.create-edit', compact('trimester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trimester $trimester)
    {
        $validated = $request->validate([
            'trimester_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|integer|in:0,1',
        ]);

        $trimester->update($validated);

        return redirect()->route('settings.trimesters.index')
                         ->with('success', 'Trimestre actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trimester $trimester)
    {
        //
    }
}
