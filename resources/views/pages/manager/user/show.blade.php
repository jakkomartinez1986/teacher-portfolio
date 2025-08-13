<x-layouts.app :title="__('Detalles del Usuario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.users.index') }}">Usuarios</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles del Usuario: {{ $user->full_name }}
                </h2>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Información principal -->
                <div class="space-y-4">
                    <div class="flex flex-col items-center">
                        <flux:avatar size="lg" src="{{asset($user->defaultUserPhotoUrl()) }}"/>
                        <h3 class="text-lg font-medium text-neutral-800 dark:text-neutral-200">{{ $user->full_name }}</h3>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $user->email }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Cédula</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $user->dni }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Contactos</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $user->contacts }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Dirección</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $user->address ?? 'No especificada' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Información de la escuela y roles -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Institucional</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Escuela</p>
                            <p class="text-neutral-800 dark:text-neutral-200">
                                {{ $user->school->name_school ?? 'No asignada' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Roles</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center rounded-full bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Estado</p>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $user->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $user->estadoUsuario() }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Firma y metadatos -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Firma</p>
                        <div class="mt-2">
                            <flux:avatar size="lg" src="{{asset($user->defaultSignaturePhotoUrl()) }}"/>
                            {{-- <img src="{{ $user->defaultSignaturePhotoUrl() }}" 
                                 alt="Firma" 
                                 class="h-20 w-48 object-contain border rounded-md"> --}}
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Adicional</h3>
                        <div class="mt-2 grid grid-cols-1 gap-4">
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Creado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Documentos -->
            <div class="mt-8 space-y-6">
                <h3 class="text-lg font-medium text-neutral-800 dark:text-neutral-200">Documentos Relacionados</h3>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Documentos creados -->
                    {{-- <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                        <h4 class="mb-3 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Documentos Creados ({{ $user->createdDocuments->count() }})
                        </h4>
                        @if($user->createdDocuments->count() > 0)
                            <ul class="space-y-2">
                                @foreach($user->createdDocuments->take(3) as $document)
                                    <li class="text-sm text-neutral-600 dark:text-neutral-400">
                                        <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ $document->title }} ({{ $document->created_at->format('d/m/Y') }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @if($user->createdDocuments->count() > 3)
                                <p class="mt-2 text-xs text-neutral-500">+{{ $user->createdDocuments->count() - 3 }} más</p>
                            @endif
                        @else
                            <p class="text-sm text-neutral-500">No ha creado documentos</p>
                        @endif
                    </div>
                     --}}
                     <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                        <h4 class="mb-3 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Documentos Creados ({{ $createdDocuments->count() }})
                        </h4>
                        @if($createdDocuments->count() > 0)
                            <ul class="space-y-2">
                                @foreach($createdDocuments as $document)
                                    <li class="text-sm text-neutral-600 dark:text-neutral-400">
                                        <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ $document->title }} ({{ $document->created_at->format('d/m/Y') }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-neutral-500">No ha creado documentos</p>
                        @endif
                    </div>
                    <!-- Documentos co-autorados -->
                    <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                        <h4 class="mb-3 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Documentos Co-Autorados ({{ $coAuthoredDocuments->count() }})
                        </h4>
                        @if($user->coAuthoredDocuments->count() > 0)
                            <ul class="space-y-2">
                                @foreach($coAuthoredDocuments->take(3) as $document)
                                    <li class="text-sm text-neutral-600 dark:text-neutral-400">
                                        <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ $document->title }} 
                                            @if($document->pivot->is_main_author)
                                                <span class="text-xs text-green-600">(Autor principal)</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @if($user->coAuthoredDocuments->count() > 3)
                                <p class="mt-2 text-xs text-neutral-500">+{{ $user->coAuthoredDocuments->count() - 3 }} más</p>
                            @endif
                        @else
                            <p class="text-sm text-neutral-500">No es co-autor de documentos</p>
                        @endif
                    </div>
                    
                    <!-- Documentos firmados -->
                    <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                        <h4 class="mb-3 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Documentos Firmados ({{ $signedDocuments->count() }})
                        </h4>
                        @if($signedDocuments->count() > 0)
                            <ul class="space-y-2">
                                @foreach($user->signedDocuments->take(3) as $document)
                                    <li class="text-sm text-neutral-600 dark:text-neutral-400">
                                        <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ $document->title }}
                                            <span class="text-xs {{ $document->pivot->status ? 'text-green-600' : 'text-yellow-600' }}">
                                                ({{ $document->pivot->status ? 'Firmado' : 'Pendiente' }})
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @if($user->signedDocuments->count() > 3)
                                <p class="mt-2 text-xs text-neutral-500">+{{ $user->signedDocuments->count() - 3 }} más</p>
                            @endif
                        @else
                            <p class="text-sm text-neutral-500">No ha firmado documentos</p>
                        @endif
                    </div>
                </div>
            </div>
             
            <!-- Botones de acción -->                           
            <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('admin.users.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                    @can('editar-user')
                        <flux:button icon="pencil" href="{{ route('admin.users.edit', $user->id) }}" > {{ __(' Editar Usuario') }}</flux:button>
                    @endcan
            </div>
        </div>
    </div>
</x-layouts.app>