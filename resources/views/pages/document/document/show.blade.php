<x-layouts.app :title="__('Detalles del Documento')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('documents.documents.index') }}">Documentos</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    {{ $document->title }}
                </h2>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                        {{ $document->status === 'DRAFT' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $document->status === 'PENDING_REVIEW' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $document->status === 'APPROVED' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $document->status === 'REJECTED' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $document->status === 'ARCHIVED' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ __($document->status) }}
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información del Documento</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Tipo</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $document->type->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Año</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $document->year->name }}</p>
                        </div>
                        @if($document->trimester)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Trimestre</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $document->trimester->name }}</p>
                        </div>
                        @endif
                        @if($document->description)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Descripción</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $document->description }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Archivo</p>
                            <a href="{{ Storage::url($document->file_path) }}" target="_blank" 
                               class="text-primary-600 hover:underline flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Ver documento
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Autores -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Autores</h3>
                    
                    <div class="space-y-2">
                        @foreach($document->authors as $author)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-neutral-800 dark:text-neutral-200">{{ $author->getFullNameAttribute() }}</span>
                                    @if($author->pivot->is_main_author)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            Principal
                                        </span>
                                    @endif
                                </div> 
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Materias y Grados relacionados -->
            @if($document->subjects->count() > 0 || $document->grades->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Asociado a</h3>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        @if($document->subjects->count() > 0)
                            <div>
                                <h4 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Materias</h4>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($document->subjects as $subject)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            {{ $subject->subject_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if($document->grades->count() > 0)
                            <div>
                                <h4 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Grados</h4>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($document->grades as $grade)
                                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                                            {{-- {{ $grade->grade_name }} --}}
                                            {{ $grade->grade_name }}-{{ $grade->section }}-{{ $grade->nivel->shift->shift_name }}- {{ $grade->nivel->nivel_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Flujo de aprobación -->
            @if($document->signatures->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Flujo de Aprobación</h3>
                    <div class="mt-4 space-y-4">
                        @foreach($document->signatures as $signature)
                            <div class="rounded-md border p-4 
                                {{ $signature->status === 'APPROVED' ? 'border-green-200 bg-green-50 dark:border-green-900 dark:bg-green-900/30' : '' }}
                                {{ $signature->status === 'REJECTED' ? 'border-red-200 bg-red-50 dark:border-red-900 dark:bg-red-900/30' : '' }}
                                {{ $signature->status === 'PENDING' ? 'border-yellow-200 bg-yellow-50 dark:border-yellow-900 dark:bg-yellow-900/30' : '' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @if($signature->user)
                                                <img class="h-10 w-10 rounded-full" src="{{ $signature->user->defaultUserPhotoUrl() }}" alt="{{ $signature->user->name }}">
                                            @else
                                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-neutral-200 dark:bg-neutral-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-neutral-800 dark:text-neutral-200">
                                                {{ $signature->role->name }}
                                            </h4>
                                            @if($signature->user)
                                                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                                    {{ $signature->user->name }}
                                                </p>
                                            @else
                                                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                                    Pendiente de asignación
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                                            {{ $signature->status === 'APPROVED' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $signature->status === 'REJECTED' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $signature->status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                            {{ __($signature->status) }}
                                        </span>
                                        @if($signature->signed_at)
                                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ $signature->signed_at->format('d/m/Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if($signature->comments)
                                    <div class="mt-3 rounded bg-white p-3 text-sm text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                        {{ $signature->comments }}
                                    </div>
                                @endif
                                
                                {{-- @if($signature->status === 'PENDING' && auth()->user() && auth()->user()->hasRole($signature->role->name)) --}}
                                    <div class="mt-3 flex justify-end gap-2">
                                        <form method="POST" action="{{ route('documents.document-signatures.reject', $signature) }}">
                                            @csrf
                                            <input type="hidden" name="comments" value="Rechazado por {{ auth()->user()->name }}">
                                            <flux:button type="submit" size="sm" variant="outline" color="red">
                                                Rechazar
                                            </flux:button>
                                        </form>
                                        <form method="POST" action="{{ route('documents.document-signatures.approve', $signature) }}">
                                            @csrf
                                            <flux:button type="submit" size="sm" color="green">
                                                Aprobar
                                            </flux:button>
                                        </form>
                                    </div>
                                {{-- @endif --}}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Botones de acción -->                           
            <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('documents.documents.index') }}"> 
                    {{ __('Volver a la Lista') }}
                </flux:button>
                
                @if($document->status === 'DRAFT' && auth()->user()->can('update', $document))
                    <flux:button icon="pencil" href="{{ route('documents.documents.edit', $document) }}"> 
                        {{ __('Editar Documento') }}
                    </flux:button>
                    
                    <form method="POST" action="{{ route('documents.documents.submit-review', $document) }}">
                        @csrf
                        <flux:button type="submit" icon="paper-airplane" color="primary">
                            {{ __('Enviar para Revisión') }}
                        </flux:button>
                    </form>
                @endif
            </div>
        </div>
        
        {{-- @livewire('document.document-notifications', ['document' => $document]) --}}
    </div>
</x-layouts.app>