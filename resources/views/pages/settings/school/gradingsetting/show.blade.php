<x-layouts.app :title="__('Detalles de Configuración de Calificación')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.grading-settings.index') }}">Configuración de Calificación</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles de Configuración de Calificación: {{ $gradingSetting->year->year_name }}
                </h2>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Porcentajes de Evaluación</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Año Lectivo</p>
                            <p class="text-neutral-800 dark:text-neutral-200">
                                {{ $gradingSetting->year->year_name }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Evaluación Formativa</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $gradingSetting->formative_percentage }}%</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Evaluación Sumativa</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $gradingSetting->summative_percentage }}%</p>
                        </div>
                    </div>
                </div>
                
                <!-- Porcentajes Sumativos -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Desglose Sumativo</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Examen</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $gradingSetting->exam_percentage }}%</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Proyecto</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $gradingSetting->project_percentage }}%</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Sumativo</p>
                            <p class="text-neutral-800 dark:text-neutral-200">
                                {{ $gradingSetting->exam_percentage + $gradingSetting->project_percentage }}%
                                @if(abs(($gradingSetting->exam_percentage + $gradingSetting->project_percentage) - $gradingSetting->summative_percentage) > 0.01)
                                    <span class="ml-2 text-xs text-red-500">(No coincide con el sumativo configurado)</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado y metadatos -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mt-6">
                <div>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Estado</p>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $gradingSetting->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $gradingSetting->status ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                
                <div class="space-y-2">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Creado el</p>
                        <p class="text-neutral-800 dark:text-neutral-200">{{ $gradingSetting->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                        <p class="text-neutral-800 dark:text-neutral-200">{{ $gradingSetting->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="flex space-x-4 mt-6 pt-4 justify-end">
                <flux:button icon="arrow-uturn-left" href="{{ route('settings.grading-settings.index') }}">
                    {{ __('Volver a la Lista') }}
                </flux:button>
                
                @can('editar-grading-setting')
                    <flux:button icon="pencil" href="{{ route('settings.grading-settings.edit', $gradingSetting->id) }}">
                        {{ __('Editar Configuración') }}
                    </flux:button>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>