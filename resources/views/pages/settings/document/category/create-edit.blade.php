<x-layouts.app :title="isset($documentCategory) ? __('Editar Categoría de Documento') : __('Crear Categoría de Documento')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.document-categories.index') }}">Categorías de Documento</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($documentCategory) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($documentCategory) ? 'Editar Categoría de Documento' : 'Crear Nueva Categoría de Documento' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($documentCategory) ? route('settings.document-categories.update', $documentCategory) : route('settings.document-categories.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($documentCategory))
                    @method('PUT')
                @endif

                <!-- Nombre -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="name" type="text" name="name" 
                               value="{{ old('name', $documentCategory->name ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Descripción
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <textarea id="description" name="description" rows="3"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">{{ old('description', $documentCategory->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones con Flux -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('settings.document-categories.index') }}"
                        variant="outline"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="{{ isset($documentCategory) ? 'check-circle' : 'plus' }}" 
                        color="primary"
                    >
                        {{ isset($documentCategory) ? __('Actualizar Categoría') : __('Crear Categoría') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>