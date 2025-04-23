<x-layouts.app :title="isset($document) ? __('Editar Documento') : __('Crear Documento')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('documents.documents.index') }}">Documentos</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($document) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($document) ? 'Editar Documento' : 'Crear Nuevo Documento' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($document) ? route('documents.documents.update', $document) : route('documents.documents.store') }}" 
                  class="space-y-6"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($document))
                    @method('PUT')
                @endif

                <!-- Tipo de Documento -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="type_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Tipo de Documento <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="type_id" name="type_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un tipo</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ (isset($document) && $document->type_id == $type->id) || old('type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Año -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="year_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Año <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="year_id" name="year_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un año</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ (isset($document) && $document->year_id == $year->id) || old('year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('year_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Trimestre (Opcional) -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="trimester_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Trimestre
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="trimester_id" name="trimester_id"
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">No aplica</option>
                            @foreach($trimesters as $trimester)
                                <option value="{{ $trimester->id }}" {{ (isset($document) && $document->trimester_id == $trimester->id) || old('trimester_id') == $trimester->id ? 'selected' : '' }}>
                                    {{ $trimester->trimester_name }} 
                                </option>
                            @endforeach
                        </select>
                        @error('trimester_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Título -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="title" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Título <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="title" type="text" name="title" 
                               value="{{ old('title', $document->title ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('title')
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
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">{{ old('description', $document->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Archivo -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="file" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Archivo {{ isset($document) ? '(Opcional)' : '(*Requerido)' }}
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="file" type="file" name="file" {{ isset($document) ? '' : 'required' }}
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if(isset($document) && $document->file_path)
                            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                                Archivo actual: <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-primary-600 hover:underline">Ver archivo</a>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Autores -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Autores <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Seleccione al menos un autor</p>
                    </div>
                    <div class="md:col-span-9">
                        <select id="authors" name="authors[]" multiple required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ (isset($document) && $document->authors->contains($user->id)) || in_array($user->id, old('authors', [])) ? 'selected' : '' }}>
                                    {{ $user->getFullNameAttribute() }}
                                </option>
                            @endforeach
                        </select>
                        @error('authors')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Materias y Grados -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Asociar a Materias y Grados
                        </label>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Opcional</p>
                    </div>
                    <div class="md:col-span-9">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="subjects" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Materias
                                </label>
                                <select id="subjects" name="subjects[]" multiple
                                        class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" 
                                            {{ (isset($document) && $document->subjects->contains($subject->id)) || in_array($subject->id, old('subjects', [])) ? 'selected' : '' }}>
                                            {{ $subject->subject_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="grades" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Grados
                                </label>
                                <select id="grades" name="grades[]" multiple
                                        class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}" 
                                            {{ (isset($document) && $document->grades->contains($grade->id)) || in_array($grade->id, old('grades', [])) ? 'selected' : '' }}>
                                            {{ $grade->grade_name }}-{{ $grade->section }}-{{ $grade->nivel->shift->shift_name }}- {{ $grade->nivel->nivel_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones con Flux -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('documents.documents.index') }}"
                        variant="outline"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    @if(!isset($document) || $document->status === 'DRAFT')
                        <flux:button 
                            type="submit"
                            name="status"
                            value="DRAFT"
                            icon="document" 
                            color="secondary"
                        >
                            {{ __('Guardar como Borrador') }}
                        </flux:button>
                        
                        <flux:button 
                            type="submit"
                            name="status"
                            value="PENDING_REVIEW"
                            icon="paper-airplane" 
                            color="primary"
                        >
                            {{ isset($document) ? __('Enviar para Revisión') : __('Crear y Enviar') }}
                        </flux:button>
                    @else
                        <flux:button 
                            type="submit"
                            icon="check-circle" 
                            color="primary"
                        >
                            {{ __('Actualizar Documento') }}
                        </flux:button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Inicializar selects múltiples
            document.addEventListener('DOMContentLoaded', function() {
                new TomSelect('#authors', {
                    plugins: ['remove_button'],
                    create: false,
                    maxItems: null
                });

                new TomSelect('#subjects', {
                    plugins: ['remove_button'],
                    create: false,
                    maxItems: null
                });

                new TomSelect('#grades', {
                    plugins: ['remove_button'],
                    create: false,
                    maxItems: null
                });
            });
        </script>
    @endpush
</x-layouts.app>