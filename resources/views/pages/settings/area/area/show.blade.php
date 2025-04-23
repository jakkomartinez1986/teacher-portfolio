<x-layouts.app :title="__('Detalles del Área')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.areas.index') }}">Áreas</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles del Área: {{ $area->area_name }}
                </h2>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información del Área</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Nombre del Área</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $area->area_name }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Metadatos -->
                <div class="space-y-4">
                    <div class="pt-4">
                        <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Adicional</h3>
                        <div class="mt-2 grid grid-cols-1 gap-4">
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Creado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $area->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $area->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lista de Asignaturas relacionadas -->
            @if($area->subjects->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Asignaturas asociadas</h3>
                    <div class="mt-4 space-y-2">
                        @foreach($area->subjects as $subject)
                            <div class="flex items-center justify-between rounded-md bg-neutral-100 p-3 dark:bg-neutral-700">
                                <span class="text-neutral-800 dark:text-neutral-200">{{ $subject->subject_name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Botones de acción -->                           
            <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('settings.areas.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                @can('editar-area')
                    <flux:button icon="pencil" href="{{ route('settings.areas.edit', $area->id) }}" > {{ __(' Editar Área') }}</flux:button>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>