<x-layouts.app :title="isset($documentType) ? __('Editar Tipo de Documento') : __('Crear Tipo de Documento')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.document-types.index') }}">Tipos de Documento</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($documentType) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($documentType) ? 'Editar Tipo de Documento' : 'Crear Nuevo Tipo de Documento' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($documentType) ? route('settings.document-types.update', $documentType) : route('settings.document-types.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($documentType))
                    @method('PUT')
                @endif

                <!-- Categoría -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="category_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Categoría <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="category_id" name="category_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $documentType->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nombre -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="name" type="text" name="name" 
                               value="{{ old('name', $documentType->name ?? '') }}" required
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
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">{{ old('description', $documentType->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Frecuencia -->
                {{-- <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="frequency" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Frecuencia
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="frequency" type="text" name="frequency" 
                               value="{{ old('frequency', $documentType->frequency ?? '') }}"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('frequency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> --}}
                <!-- Frecuencia -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="frequency" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Frecuencia <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="frequency" name="frequency" required
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione una frecuencia</option>
                            @foreach (['TRIMESTRAL', 'ANUAL', 'OCASIONAL'] as $option)
                                <option value="{{ $option }}"
                                    {{ old('frequency', $documentType->frequency ?? '') === $option ? 'selected' : '' }}>
                                    {{ ucfirst(strtolower($option)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('frequency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Requisitos de aprobación -->
                {{-- <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Requiere aprobación de:
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <div class="space-y-2">
                            
                            <div class="flex items-center">
                                <input id="requires_director" name="requires_director" type="checkbox" 
                                       {{ old('requires_director', $documentType->requires_director ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700">
                                <label for="requires_director" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                    Director Area
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="requires_vice_principal" name="requires_vice_principal" type="checkbox" 
                                       {{ old('requires_vice_principal', $documentType->requires_vice_principal ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700">
                                <label for="requires_vice_principal" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                    Vice Rector
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="requires_principal" name="requires_principal" type="checkbox" 
                                       {{ old('requires_principal', $documentType->requires_principal ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700">
                                <label for="requires_principal" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                    Rector
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="requires_dece" name="requires_dece" type="checkbox" 
                                       {{ old('requires_dece', $documentType->requires_dece ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700">
                                <label for="requires_dece" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                    DECE
                                </label>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Requiere aprobación de:
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <div class="space-y-2">
                
                            <div class="flex items-center">
                                <input type="hidden" name="requires_director" value="0">
                                <input id="requires_director" name="requires_director" type="checkbox"
                                       value="1"
                                       {{ old('requires_director', $documentType->requires_director ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700">
                                <label for="requires_director" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                    Director Área
                                </label>
                            </div>
                
                            <div class="flex items-center">
                                <input type="hidden" name="requires_vice_principal" value="0">
                                <input id="requires_vice_principal" name="requires_vice_principal" type="checkbox"
                                       value="1"
                                       {{ old('requires_vice_principal', $documentType->requires_vice_principal ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700">
                                <label for="requires_vice_principal" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                    Vice Rector
                                </label>
                            </div>
                
                            <div class="flex items-center">
                                <input type="hidden" name="requires_principal" value="0">
                                <input id="requires_principal" name="requires_principal" type="checkbox"
                                       value="1"
                                       {{ old('requires_principal', $documentType->requires_principal ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700">
                                <label for="requires_principal" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                    Rector
                                </label>
                            </div>
                
                            <div class="flex items-center">
                                <input type="hidden" name="requires_dece" value="0">
                                <input id="requires_dece" name="requires_dece" type="checkbox"
                                       value="1"
                                       {{ old('requires_dece', $documentType->requires_dece ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700">
                                <label for="requires_dece" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                    DECE
                                </label>
                            </div>
                
                        </div>
                    </div>
                </div>
                
                <!-- Botones con Flux -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('settings.document-types.index') }}"
                        variant="outline"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="{{ isset($documentType) ? 'check-circle' : 'plus' }}" 
                        color="primary"
                    >
                        {{ isset($documentType) ? __('Actualizar Tipo') : __('Crear Tipo') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>