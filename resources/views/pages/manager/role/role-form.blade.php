@csrf
<div class="p-4 space-y-4">
    <!-- Nombre del rol -->
    <x-input name="name" label="Nombre del Rol" value="{{ old('name', $role->name ?? '') }}" required />
    <!-- Nombre del rol -->
    <x-input name="description" label="Descripción" value="{{ old('description', $role->description ?? '') }}" required />
    <!-- Permisos -->
    <div class="space-y-2 mt-4">
        <label class="text-sm font-semibold text-gray-700">Permisos</label>
        <!-- Agrupamos los permisos por módulo -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
            @foreach($permissions->groupBy('module') as $module => $permissionsInModule)
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-md font-semibold text-gray-900">{{ ucfirst($module) }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                        @foreach($permissionsInModule as $permission)
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="permission_{{ $permission->id }}" 
                                    name="permissions[]" 
                                    value="{{ $permission->id }}" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}
                                >
                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Botón de guardar -->
    <div class="mt-6 flex justify-end">
        <x-button type="submit">
            Guardar
        </x-button>
    </div>
</div>