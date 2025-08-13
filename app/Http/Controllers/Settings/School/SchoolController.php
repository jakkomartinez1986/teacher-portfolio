<?php

namespace App\Http\Controllers\Settings\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\School\School;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-school')->only(['create', 'store']);
        $this->middleware('permission:editar-school')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.settings.school.school.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
        return view('pages.settings.school.school.create-edit',['school' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_school' => 'required|string|max:255',
            'distrit' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Procesar la imagen si se subió
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('schools/logos', 'public');
            $validated['logo_path'] = $path;
        }
        $validated['status'] = 1; // Activo por defecto
        School::create($validated);

        return redirect()->route('settings.schools.index')
                         ->with('success', 'Escuela creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        // Carga los usuarios relacionados con sus roles
        $school->load(['users' => function($query) {
            $query->with('roles')->orderBy('name');
        }]);
        
        return view('pages.settings.school.school.show', compact('school'));
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        //
        return view('pages.settings.school.school.create-edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name_school' => 'required|string|max:255',
            'distrit' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Procesar la imagen si se subió
        if ($request->hasFile('logo')) {
            // Eliminar la imagen anterior si existe
            if ($school->logo_path) {
                Storage::disk('public')->delete($school->logo_path);
            }
            
            $path = $request->file('logo')->store('schools/logos', 'public');
            $validated['logo_path'] = $path;
        }

        $school->update($validated);

        return redirect()->route('settings.schools.index')
                         ->with('success', 'Escuela actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        //
    }
}
