<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use App\Services\ModelTableService;
use App\Http\Controllers\Controller;
use App\Models\Security\Spatie\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{

    protected $modelTableService;
   

    public function __construct(ModelTableService $modelTableService)
    {
        $this->modelTableService = $modelTableService;
         // Aplica el middleware para roles
         $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
         // Aplica el middleware para permisos
         $this->middleware('permission:crear-permission')->only(['create', 'store']);
         $this->middleware('permission:editar-permission')->only(['edit', 'update']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.manager.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todos los modelos con tablas
        $allModels = $this->modelTableService->getModelsForTables();
        
        // Obtener módulos que ya tienen permisos
        $existingModules = Permission::select('module')->distinct()->pluck('module')->toArray();
        
        // Filtrar modelos que no tienen permisos
        $missingModels = collect(array_keys($allModels))
            ->diff($existingModules)
            ->values();

        return view('pages.manager.permission.create', [
            'missingModels' => $missingModels,
            'allModels' => $allModels
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }

    public function generate(Request $request)
    {
        $request->validate([
            'models' => 'required|array',
            'models.*' => 'string',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $created = 0;
        foreach ($request->models as $model) {
            // Verificar si el modelo ya tiene permisos (por si acaso)
            $existing = Permission::where('module', $model)->exists();
            if ($existing) continue;

            // Crear permisos básicos
            $actions = ['ver', 'crear', 'editar', 'borrar'];
            foreach ($actions as $action) {
                Permission::create([
                    'name' => "{$action}-".strtolower($model),
                    'label' => ucfirst($action).' '.$model,
                    'module' => $model,
                    //'group_name' => 'default',
                    'guard_name' => 'web'
                ]);
                $created++;
            }
        }

        return redirect()
            ->route('admin.permissions.create')
            ->with('success', "Se crearon {$created} nuevos permisos para los modelos seleccionados");
    }
}
