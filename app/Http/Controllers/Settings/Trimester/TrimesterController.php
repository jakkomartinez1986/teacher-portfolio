<?php

namespace App\Http\Controllers\Settings\Trimester;

use App\Http\Controllers\Controller;
use App\Models\Settings\Trimester\Trimester;
use App\Models\Settings\School\Year;
use Illuminate\Http\Request;

class TrimesterController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        $this->middleware('permission:crear-trimester')->only(['create', 'store']);
        $this->middleware('permission:editar-trimester')->only(['edit', 'update']);
    }

    public function index()
    {
        $trimesters = Trimester::with('year')
            ->latest()
            ->paginate(10);
            
        return view('pages.settings.trimester.index', compact('trimesters'));
    }

    public function create()
    {
        $years = Year::active()->latest()->get();
        return view('pages.settings.trimester.create-edit', compact('years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_id' => 'required|exists:years,id',
            'trimester_name' => 'required|string|max:255|unique:trimesters,trimester_name',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);

        Trimester::create($validated);

        return redirect()->route('settings.trimesters.index')
            ->with('success', 'Trimestre creado exitosamente.');
    }

    public function show(Trimester $trimester)
    {
        $trimester->load('year');
        return view('pages.settings.trimester.show', compact('trimester'));
    }

    public function edit(Trimester $trimester)
    {
        $years = Year::active()->latest()->get();
        return view('pages.settings.trimester.create-edit', compact('trimester', 'years'));
    }

    public function update(Request $request, Trimester $trimester)
    {
        $validated = $request->validate([
            'year_id' => 'required|exists:years,id',
            'trimester_name' => 'required|string|max:255|unique:trimesters,trimester_name,'.$trimester->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);

        $trimester->update($validated);

        return redirect()->route('settings.trimesters.index')
            ->with('success', 'Trimestre actualizado exitosamente.');
    }

    public function destroy(Trimester $trimester)
    {
        $trimester->delete();
        return redirect()->route('settings.trimesters.index')
            ->with('success', 'Trimestre eliminado exitosamente.');
    }
}