<x-layouts.app :title="isset($grade) ? __('Editar Grado') : __('Crear Grado')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.grades.index') }}">Grados</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($grade) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($grade) ? 'Editar Grado' : 'Crear Nuevo Grado' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($grade) ? route('settings.grades.update', $grade) : route('settings.grades.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($grade))
                    @method('PUT')
                @endif

                <!-- Nivel -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="nivel_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Año/Jornada/Nivel <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="nivel_id" name="nivel_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un Nivel</option>
                            @foreach($nivels as $nivel)
                                <option value="{{ $nivel->id }}" {{ old('nivel_id', $grade->nivel_id ?? '') == $nivel->id ? 'selected' : '' }}>
                                    {{ $nivel->shift->year->year_name }} - {{ $nivel->shift->shift_name }} - {{ $nivel->nivel_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('nivel_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nombre del Grado -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="grade_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre del Grado <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="grade_name" type="number" name="grade_name" 
                            value="{{ old('grade_name', $grade->grade_name ?? '') }}" 
                            required
                            min="1"
                            max="8"
                            title="El grado debe ser un número entre 1 y 8"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('grade_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sección -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="section" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Paralelo <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="section" type="text" name="section" 
                            value="{{ old('section', $grade->section ?? '') }}" 
                            required
                            pattern="[A-Z]"
                            maxlength="1"
                            title="La sección debe ser una letra mayúscula (A-Z)"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('section')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Estado -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Estado <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="status" name="status" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="1" {{ old('status', $grade->status ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('status', $grade->status ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones con Flux -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('settings.grades.index') }}"
                        variant="outline"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="{{ isset($grade) ? 'check-circle' : 'plus' }}" 
                        color="primary"
                    >
                        {{ isset($grade) ? __('Actualizar Grado') : __('Crear Grado') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>