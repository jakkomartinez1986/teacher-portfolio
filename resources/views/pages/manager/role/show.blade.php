<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.roles.index') }}">Roles</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Mostrar Rol</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-slot name="header">
                Detalle del Rol: {{ $role->name }}
            </x-slot>
        
            <div class="p-6 bg-white rounded shadow">
        
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700">Nombre del Rol</h2>
                    <p class="text-gray-900">{{ $role->name }}</p>
                </div>
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700">Descripcion</h2>
                    <p class="text-gray-900">{{ $role->description }}</p>
                </div>
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700">Permisos Asignados</h2>
                    
                    @if ($role->permissions->isEmpty())
                        <p class="text-gray-500">No hay permisos asignados.</p>
                    @else
                        <!-- Agrupamos los permisos asignados por módulo -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-3 gap-4 mt-4">
                            @foreach ($role->permissions->groupBy('module') as $module => $permissionsInModule)
                                <div class="bg-white p-4 rounded-lg shadow-md">
                                    <h3 class="text-md font-semibold text-gray-900">{{ ucfirst($module) }}</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-3 gap-4 mt-4">
                                        @foreach ($permissionsInModule as $permission)
                                            <div class="flex items-center space-x-2">
                                                <flux:badge color="emerald" size="sx">{{  $permission->name }}</flux:badge>  
                                                
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
        
                <div class="flex space-x-4 mt-6">                    
                    <flux:button icon="arrow-uturn-left" href="{{ route('admin.roles.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                    @can('editar-role')
                        <flux:button icon="pencil" href="{{ route('admin.roles.edit', $role->id) }}" > {{ __(' Editar Rol') }}</flux:button>
                    @endcan
                </div>
        
            </div>
            
        </div>
    </div>
   
</x-layouts.app>