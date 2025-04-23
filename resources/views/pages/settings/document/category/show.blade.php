<x-layouts.app :title="__('Detalles de Categoría de Documento')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.document-categories.index') }}">Categorías de Documento</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles de Categoría: {{ $documentCategory->name }}
                </h2>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información de la Categoría</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Nombre</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $documentCategory->name }}</p>
                        </div>
                        @if($documentCategory->description)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Descripción</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $documentCategory->description }}</p>
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
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $documentCategory->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $documentCategory->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lista de Tipos de Documento relacionados -->
            @if($documentCategory->types->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Tipos de documento asociados</h3>
                    <div class="mt-4 space-y-2">
                        @foreach($documentCategory->types as $type)
                            <div class="flex items-center justify-between rounded-md bg-neutral-100 p-3 dark:bg-neutral-700">
                                <span class="text-neutral-800 dark:text-neutral-200">{{ $type->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Botones de acción -->                           
            <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('settings.document-categories.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                @can('editar-documentcategory')
                    <flux:button icon="pencil" href="{{ route('settings.document-categories.edit', $documentCategory->id) }}" > {{ __(' Editar Categoría') }}</flux:button>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>