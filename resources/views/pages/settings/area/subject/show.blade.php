<x-layouts.app :title="__('Detalles de la Asignatura')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.subjects.index') }}">Asignaturas</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles de la Asignatura: {{ $subject->subject_name }}
                </h2>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información de la Asignatura</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Área</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $subject->area->area_name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Nombre de la Asignatura</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $subject->subject_name }}</p>
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
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $subject->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $subject->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <!-- Documentos relacionados -->
            <div class="mt-8 space-y-4">
                <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Documentos Asociados</h3>
                
                @if($subject->documents->isEmpty())
                    <p class="text-neutral-500 dark:text-neutral-400">No hay documentos asociados a esta asignatura.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Documento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Grado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-neutral-200 dark:bg-neutral-800 dark:divide-neutral-700">
                                @foreach($subject->documents as $document)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">{{ $document->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">
                                            {{ $document->pivot->grade->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <form action="{{ route('settings.subjects.detach-document', [$subject, $document]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    Desasociar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                
                <!-- Formulario para asociar nuevos documentos -->
                <div class="mt-6 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                    <h4 class="text-md font-medium text-neutral-700 dark:text-neutral-300 mb-4">Asociar nuevo documento</h4>
                    <form action="{{ route('settings.subjects.attach-document', $subject) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="document_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Documento</label>
                                <select id="document_id" name="document_id" required class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                    <option value="">Seleccione un documento</option>
                                    @foreach($availableDocuments as $document)
                                        <option value="{{ $document->id }}">{{ ($document->title) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="grade_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Grado</label>
                                <select id="grade_id" name="grade_id" required class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                    <option value="">Seleccione un grado</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}">{{  ($grade->nivel->nivel_name .' / '.$grade->grade_name .' / ' .$grade->section.' / ' .$grade->nivel->shift->shift_name) }}</option>
                                        {{-- {{ $grade->nivel->nivel_name }} - {{ $grade->grade_name }} - Paralelo {{ $grade->section }}- Jornada {{ $grade->nivel->shift->shift_name }} --}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            {{-- <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                
                            </button> --}}
                            @can('editar-subject')
                                <flux:button icon="clipboard-document" type="submit" > {{ __(' Asociar Documento') }}</flux:button>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
            <!-- Botones de acción -->                           
            <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('settings.subjects.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                @can('editar-subject')
                    <flux:button icon="pencil" href="{{ route('settings.subjects.edit', $subject->id) }}" > {{ __(' Editar Asignatura') }}</flux:button>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>