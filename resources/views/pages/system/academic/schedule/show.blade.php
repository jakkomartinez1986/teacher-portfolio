<x-layouts.app :title="__('Detalles de Horario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('academic.class-schedules.index') }}">Horarios</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles de Horario: {{ $classSchedule->subject->subject_name }}
                </h2>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $classSchedule->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                    {{ $classSchedule->is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Principal</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Docente</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->teacher->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Asignatura</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->subject->subject_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Grado</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->grade->grade_name }} - {{ $classSchedule->grade->section }}</p>
                        </div>
                        {{-- <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Trimestre</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->trimester->trimester_name }}</p>
                        </div> --}}
                    </div>
                </div>
                
                <!-- Horario -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Horario</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Día</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->day }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Horario</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->schedule }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Duración</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->duration }}</p>
                        </div>
                        @if($classSchedule->classroom)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Aula</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->classroom }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Metadatos -->
                <div class="space-y-4">
                    <div class="pt-4">
                        <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Adicional</h3>
                        <div class="mt-2 grid grid-cols-1 gap-4">
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Creado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @if($classSchedule->notes)
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Notas</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $classSchedule->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botones de acción -->                           
            <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('academic.class-schedules.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                @can('editar-classschedule')
                    <flux:button icon="pencil" href="{{ route('academic.class-schedules.edit', $classSchedule->id) }}" > {{ __(' Editar Horario') }}</flux:button>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>