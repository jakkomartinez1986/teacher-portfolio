<x-layouts.app :title="__('Detalles del Grado')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.grades.index') }}">Grados</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles del Grado: {{ $grade->nivel->nivel_name }} - {{ $grade->grade_name }} - Paralelo {{ $grade->section }}- Jornada {{ $grade->nivel->shift->shift_name }}
                </h2>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información del Grado</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Año/Jornada/Nivel</p>
                            <p class="text-neutral-800 dark:text-neutral-200">
                                {{ $grade->nivel->shift->year->year_name }} - 
                                {{ $grade->nivel->shift->shift_name }} - 
                                {{ $grade->nivel->nivel_name }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Nombre del Grado</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $grade->grade_name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Paralelo</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $grade->section }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Estado y metadatos -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Estado</p>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $grade->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $grade->status ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    
                    <div class="pt-4">
                        <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Adicional</h3>
                        <div class="mt-2 grid grid-cols-1 gap-4">
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Creado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $grade->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $grade->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Documentos -->
            <div class="mt-8 space-y-4">
                <h3 class="text-lg font-medium text-neutral-800 dark:text-neutral-200">Documentos Relacionados</h3>
                
                @if($grade->documents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Asignatura</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-neutral-200 dark:bg-neutral-800 dark:divide-neutral-700">
                                @foreach($grade->documents as $document)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">
                                            {{ $document->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            @foreach($document->subjects as $subject)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                                                    {{ $subject->subject_name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            {{ $document->created_at->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">No hay documentos asociados a este grado.</p>
                    </div>
                @endif
            </div>
            
            <!-- Botones de acción -->
            <div class="flex space-x-4 mt-6 pt-4 justify-end">
                <flux:button icon="arrow-uturn-left" href="{{ route('settings.grades.index') }}">
                    {{ __('Volver a la Lista') }}
                </flux:button>
                
                @can('editar-nivel')
                    <flux:button icon="pencil" href="{{ route('settings.grades.edit', $grade->id) }}">
                        {{ __('Editar Grado') }}
                    </flux:button>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>