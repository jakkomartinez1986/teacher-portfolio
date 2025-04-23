<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Security\Spatie\Role;
use App\Models\Security\Spatie\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-role')->only(['create', 'store']);
        $this->middleware('permission:editar-role')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.manager.role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        //$role= new Role();
        return view('pages.manager.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'required|string',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id,guard_name,web',
        ]);
    
        try {
            DB::beginTransaction();
    
            $role = Role::create([
                'name' => strtoupper($validatedData['name']),
                'description' => $validatedData['description'],
                'guard_name' => 'web', // Asegurar el guard_name
            ]);
    
            // Obtener los nombres de los permisos basados en los IDs
            $permissionNames = Permission::whereIn('id', $validatedData['permissions'])
                ->where('guard_name', 'web')
                ->pluck('name')
                ->toArray();
    
            $role->syncPermissions($permissionNames);
    
            DB::commit();
    
            return redirect()->route('admin.roles.index')
                ->with('success', 'Rol creado correctamente.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el rol: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('pages.manager.role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('pages.manager.role.edit', compact('role', 'permissions', 'rolePermissions'));
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'description' => 'required|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id,guard_name,web',
        ]);
       // Actualizar el nombre y descripción del rol
        $role->update([
            'name' => strtoupper($request->name),
            'description' => $request->description,
        ]);
        // Actualizar permisos
        // 1. Obtener los nombres de los permisos basados en los IDs
        $permissionNames = Permission::whereIn('id', $validated['permissions'])
        ->pluck('name')
        ->toArray();

        // 2. Sincronizar (asignar nuevos y revocar los no incluidos)
        $role->syncPermissions($permissionNames);
        
        // Puedes agregar el resto de la lógica de actualización aquí...
    
       
        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
