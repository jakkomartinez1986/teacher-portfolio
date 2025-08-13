<x-layouts.app :title="__('Detalles del Trimestre')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.trimesters.index') }}">Trimestres</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles del Trimestre: {{ $trimester->trimester_name }}
                </h2>
                
                @if($trimester->isCurrentlyActive())
                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                        Trimestre Activo Actualmente
                    </span>
                @endif
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información del Trimestre</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Nombre</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $trimester->trimester_name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Año Escolar</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $trimester->year->year_name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Período</p>
                            <p class="text-neutral-800 dark:text-neutral-200">
                                {{ $trimester->start_date->format('d/m/Y') }} - {{ $trimester->end_date->format('d/m/Y') }}
                                <span class="text-sm text-neutral-500">({{ $trimester->duration_days }} días)</span>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Estado</p>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $trimester->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $trimester->status_name }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Información adicional -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Adicional</h3>
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Creado el</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $trimester->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $trimester->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($trimester->deleted_at)
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Eliminado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $trimester->deleted_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Botones de acción -->                           
            <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('settings.trimesters.index') }}" > 
                    {{ __('Volver a la Lista') }}
                </flux:button>
                @can('editar-trimester')
                    <flux:button icon="pencil" href="{{ route('settings.trimesters.edit', $trimester->id) }}" > 
                        {{ __('Editar Trimestre') }}
                    </flux:button>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>