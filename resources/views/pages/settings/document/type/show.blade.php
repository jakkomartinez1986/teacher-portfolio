<x-layouts.app :title="__('Detalles de Tipo de Documento')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.document-types.index') }}">Tipos de Documento</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles de Tipo de Documento: {{ $documentType->name }}
                </h2>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información del Tipo</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Categoría</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $documentType->category->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Nombre</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $documentType->name }}</p>
                        </div>
                        @if($documentType->description)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Descripción</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $documentType->description }}</p>
                        </div>
                        @endif
                        @if($documentType->frequency)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Frecuencia</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $documentType->frequency }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Requisitos de aprobación -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Requisitos de Aprobación</h3>
                    
                    <div class="space-y-2">
                        @if($documentType->requires_director)
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-2 text-neutral-800 dark:text-neutral-200">Requiere aprobación del Director</span>
                        </div>
                        @endif
                        
                        @if($documentType->requires_vice_principal)
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-2 text-neutral-800 dark:text-neutral-200">Requiere aprobación del Vice Principal</span>
                        </div>
                        @endif
                        
                        @if($documentType->requires_principal)
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-2 text-neutral-800 dark:text-neutral-200">Requiere aprobación del Principal</span>
                        </div>
                        @endif
                        
                        @if($documentType->requires_dece)
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-2 text-neutral-800 dark:text-neutral-200">Requiere aprobación del DECE</span>
                        </div>
                        @endif
                        
                        @if(!$documentType->requires_director && !$documentType->requires_vice_principal && !$documentType->requires_principal && !$documentType->requires_dece)
                        <div class="text-neutral-500 dark:text-neutral-400">
                            No requiere aprobaciones específicas
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
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $documentType->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $documentType->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Documentos relacionados -->
            @if($documentType->documents->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Documentos asociados</h3>
                    <div class="mt-4 space-y-2">
                        @foreach($documentType->documents as $document)
                            <div class="flex items-center justify-between rounded-md bg-neutral-100 p-3 dark:bg-neutral-700">
                                <span class="text-neutral-800 dark:text-neutral-200">{{ $document->title }}</span>
                                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ $document->created_at->format('d/m/Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Flujos de aprobación -->
            @if($documentType->approvalFlows->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Flujos de aprobación</h3>
                    <div class="mt-4 space-y-2">
                        @foreach($documentType->approvalFlows as $flow)
                            <div class="flex items-center justify-between rounded-md bg-neutral-100 p-3 dark:bg-neutral-700">
                                <span class="text-neutral-800 dark:text-neutral-200">{{ $flow->name }}</span>
                                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ $flow->approvers_count }} aprobadores</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Botones de acción -->                           
            <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('settings.document-types.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                @can('editar-tipo-documento')
                    <flux:button icon="pencil" href="{{ route('settings.document-types.edit', $documentType->id) }}" > {{ __(' Editar Tipo') }}</flux:button>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>