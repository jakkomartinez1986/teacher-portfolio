<x-layouts.app :title="isset($area) ? __('Editar Área') : __('Crear Área')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.areas.index') }}">Áreas</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($area) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($area) ? 'Editar Área' : 'Crear Nueva Área' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($area) ? route('settings.areas.update', $area) : route('settings.areas.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($area))
                    @method('PUT')
                @endif

                <!-- Nombre del Área -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="area_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre del Área <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="area_name" type="text" name="area_name" 
                               value="{{ old('area_name', $area->area_name ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('area_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones con Flux -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('settings.areas.index') }}"
                        variant="outline"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="{{ isset($area) ? 'check-circle' : 'plus' }}" 
                        color="primary"
                    >
                        {{ isset($area) ? __('Actualizar Área') : __('Crear Área') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>