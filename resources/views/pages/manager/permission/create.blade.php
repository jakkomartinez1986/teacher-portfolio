

<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.permissions.index') }}">Roles</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Crear Permisos</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
           
            <div class="container mx-auto px-4 py-8">
                <h1 class="text-2xl font-bold mb-6">Generar Permisos Automáticos</h1>
            
                <div class="bg-white rounded-lg shadow-md p-6">
                    @if($missingModels->isNotEmpty())
                    <form action="{{ route('admin.permissions.generate') }}" method="POST">
                        @csrf
            
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="models">
                                Modelos sin permisos creados:
                            </label>
                            <select name="models[]" id="models" multiple 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    size="8">
                                @foreach($missingModels as $model)
                                <option value="{{ $model }}" selected>{{ $model }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                Se crearán permisos básicos (ver, crear, editar, borrar) para los modelos seleccionados
                            </p>
                        </div>
            
                        <div class="flex items-center justify-end">
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Generar Permisos
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Todos los modelos ya tienen permisos asociados. No hay nuevos modelos sin permisos.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
   
</x-layouts.app>