<?php

namespace App\Http\Controllers\Manager\User;

use Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Settings\School\School;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-user')->only(['create', 'store']);
        $this->middleware('permission:editar-user')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.manager.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::orderBy('name_school')->get();
        $roles = $this->getFilteredRoles();
        return view('pages.manager.user.create-edit', compact('schools', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            //'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'cellphone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'required|integer|in:0,1',
            'school_id' => 'nullable|exists:schools,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Convertir a mayúsculas
        $validated['name'] = strtoupper($validated['name']);
        $validated['lastname'] = strtoupper($validated['lastname']);
        $validated['address'] = strtoupper($validated['address'] ?? '');

        // Encriptar contraseña
         $validated['password'] = Hash::make($validated['dni']);

        // Crear usuario
        $user = User::create($validated);

        // Asignar roles
        $roles = Role::whereIn('id', $validated['roles'])->get();
        $user->syncRoles($roles);

        // Manejo de imágenes
        $this->handleUserPhotos($user, $request);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
            // Obtener documentos creados
            $createdDocuments = $user->createdDocuments()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

            // Obtener documentos co-autorados
            $coAuthoredDocuments = $user->coAuthoredDocuments()
                ->orderBy('documents.created_at', 'desc')
                ->limit(5)
                ->get();

            // Obtener documentos firmados
            $signedDocuments = $user->signedDocuments()
                ->orderBy('document_signatures.signed_at', 'desc')
                ->limit(5)
                ->get();

            return view('pages.manager.user.show', compact(
                'user',
                'createdDocuments',
                'coAuthoredDocuments',
                'signedDocuments'
            ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $schools = School::orderBy('name_school')->get();
        $roles = $this->getFilteredRoles();
        return view('pages.manager.user.create-edit', compact('user', 'schools', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //dd($user);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:users,dni,'.$user->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            //'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'cellphone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'required|integer|in:0,1',
            'school_id' => 'nullable|exists:schools,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_profile_photo' => 'nullable|boolean',
            'remove_signature_photo' => 'nullable|boolean',
        ]);

        // Convertir a mayúsculas
        $validated['name'] = strtoupper($validated['name']);
        $validated['lastname'] = strtoupper($validated['lastname']);
        $validated['address'] = strtoupper($validated['address'] ?? '');

        // Actualizar contraseña solo si se proporcionó
        // if ($validated['password']) {
        //     $validated['password'] = Hash::make($validated['password']);
        // } else {
        //     unset($validated['password']);
        // }

        // Actualizar usuario
        $user->update($validated);

        // Asignar roles
        $roles = Role::whereIn('id', $validated['roles'])->get();
        $user->syncRoles($roles);

        // Manejo de imágenes
        $this->handleUserPhotos($user, $request);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
       
    }

    /**
     * Maneja la subida y eliminación de fotos de perfil y firma
     */
    protected function handleUserPhotos(User $user, Request $request)
    {
        try {
            // Manejar foto de perfil
            if ($request->hasFile('profile_photo_path')) {
                // Eliminar foto anterior si existe
                if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                
                // Guardar nueva foto y almacenar la ruta
                $path = $request->file('profile_photo_path')->store('users/profile-photos', 'public');
                $user->profile_photo_path = $path;
            } elseif ($request->boolean('remove_profile_photo')) {
                // Eliminar foto si se marcó la opción y existe
                if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $user->profile_photo_path = null;
            }

            // Manejar firma
            if ($request->hasFile('signature_photo_path')) {
                // Eliminar firma anterior si existe
                if ($user->signature_photo_path && Storage::disk('public')->exists($user->signature_photo_path)) {
                    Storage::disk('public')->delete($user->signature_photo_path);
                }
                
                // Guardar nueva firma y almacenar la ruta
                $path = $request->file('signature_photo_path')->store('users/signature-photos', 'public');
                $user->signature_photo_path = $path;
            } elseif ($request->boolean('remove_signature_photo')) {
                // Eliminar firma si se marcó la opción y existe
                if ($user->signature_photo_path && Storage::disk('public')->exists($user->signature_photo_path)) {
                    Storage::disk('public')->delete($user->signature_photo_path);
                }
                $user->signature_photo_path = null;
            }

            // Guardar cambios si hubo modificaciones
            if ($user->isDirty(['profile_photo_path', 'signature_photo_path'])) {
                $user->save();
            }

        } catch (\Exception $e) {
            Log::error('Error al manejar fotos de usuario: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Filtra los roles según los permisos del usuario autenticado
     */
    protected function getFilteredRoles()
    {
        $user = auth()->user();
        
        // Si el usuario tiene el rol de 'super-admin', muestra todos los roles
        if ($user->hasRole('SUPER-ADMIN')) {
            return Role::orderBy('name')->get();
        }
        
        // De lo contrario, excluye los roles 'super-admin' y 'admin'
        return Role::whereNotIn('name', ['SUPER-ADMIN', 'ADMIN'])
                   ->orderBy('name')
                   ->get();
    }
}