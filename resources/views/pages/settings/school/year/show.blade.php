<x-layouts.app :title="__('Detalles del Año Escolar')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.years.index') }}">Años Escolares</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <!-- Encabezado con acciones -->
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles del Año Escolar: {{ $year->year_name }}
                </h2>

            </div>
            
            <!-- Información principal -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Detalles del año -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Básica</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Escuela</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $year->school->name_school }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Nombre del Año</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $year->year_name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Período</p>
                            <p class="text-neutral-800 dark:text-neutral-200">
                                {{ $year->start_date->format('d/m/Y') }} - {{ $year->end_date->format('d/m/Y') }}
                                <span class="text-sm text-neutral-500">({{ $year->start_date->diffInDays($year->end_date) }} días)</span>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Estado</p>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $year->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $year->status ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Turnos asociados -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Jornadas</h3>
                       
                    </div>
                    
                    @if($year->shifts->isEmpty())
                        <div class="rounded-md bg-neutral-50 p-4 dark:bg-neutral-700">
                            <p class="text-center text-neutral-500 dark:text-neutral-300">No hay jornadas registradas para este año</p>
                        </div>
                    @else
                        <div class="overflow-hidden rounded-md border border-neutral-200 dark:border-neutral-700">
                            <ul class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach($year->shifts as $shift)
                                    <li class="p-3 hover:bg-neutral-50 dark:hover:bg-neutral-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-neutral-800 dark:text-neutral-200">{{ $shift->name }}</p>
                                                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                                    {{ $shift->start_time }} - {{ $shift->end_time }}
                                                </p>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('settings.shifts.edit', $shift) }}" 
                                                   class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                                    Editar
                                                </a>
                                               
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Información adicional -->
            <div class="pt-4">
                <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Adicional</h3>
                <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Creado el</p>
                        <p class="text-neutral-800 dark:text-neutral-200">{{ $year->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                        <p class="text-neutral-800 dark:text-neutral-200">{{ $year->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($year->deleted_at)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Eliminado el</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $year->deleted_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
             <!-- Botones de acción -->                           
             <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('settings.years.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                    @can('editar-year')
                        <flux:button icon="pencil" href="{{ route('settings.years.edit', $year->id) }}" > {{ __(' Editar Año Escolar') }}</flux:button>
                    @endcan
            </div>
        </div>
    </div>
</x-layouts.app>