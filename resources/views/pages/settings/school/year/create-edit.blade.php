<x-layouts.app :title="isset($year) ? __('Editar Año Escolar') : __('Crear Año Escolar')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.years.index') }}">Años Escolares</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($year) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-4 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($year) ? 'Editar Año Escolar' : 'Crear Nuevo Año Escolar' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($year) ? route('settings.years.update', $year) : route('settings.years.store') }}" 
                  class="space-y-4">
                @csrf
                @if(isset($year))
                    @method('PUT')
                @endif

                <!-- Escuela -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="school_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Escuela <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="school_id" name="school_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione una escuela</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $year->school_id ?? '') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name_school }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mostrar el año calculado en lugar de input -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Año Escolar
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <div id="calculated_year" class="block w-full rounded-md border border-neutral-300 bg-neutral-50 px-3 py-2 text-neutral-800 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            @if(isset($year))
                                Gestión {{ $year->start_date->format('Y') }}-{{ $year->end_date->format('Y') }}
                            @else
                                (Se Generará automáticamente)
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Fecha de Inicio -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="start_date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Fecha de Inicio <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="start_date" type="date" name="start_date" 
                               value="{{ old('start_date', isset($year) ? $year->start_date->format('Y-m-d') : '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fecha de Fin -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="end_date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Fecha de Fin <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="end_date" type="date" name="end_date" 
                               value="{{ old('end_date', isset($year) ? $year->end_date->format('Y-m-d') : '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('end_date')
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
                            <option value="1" {{ old('status', $year->status ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('status', $year->status ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

               <!-- Botones -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('settings.years.index') }}"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    @if(isset($year))
                        @can('editar-year')
                            <flux:button 
                                type="submit"
                                icon="check-circle" 
                                color="primary"
                            >
                                {{ __('Actualizar Año Escolar') }}
                            </flux:button>
                        @endcan
                    @else
                        <flux:button 
                            type="submit"
                            icon="plus" 
                            color="primary"
                        >
                            {{ __('Crear Año Escolar') }}
                        </flux:button>
                    @endif
                </div>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const calculatedYearDiv = document.getElementById('calculated_year');
                
                function updateYearPreview() {
                    if (startDateInput.value && endDateInput.value) {
                        const startYear = new Date(startDateInput.value).getFullYear();
                        const endYear = new Date(endDateInput.value).getFullYear();
                        calculatedYearDiv.textContent = `Gestión ${startYear}-${endYear}`;
                    }
                }
                
                startDateInput.addEventListener('change', updateYearPreview);
                endDateInput.addEventListener('change', updateYearPreview);
            });
            </script>
    </div>
</x-layouts.app>