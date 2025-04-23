<x-layouts.app :title="isset($shift) ? __('Editar Turno') : __('Crear Turno')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.shifts.index') }}">Jornada</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($shift) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($shift) ? 'Editar Jornada' : 'Crear Nueva Jornada' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($shift) ? route('settings.shifts.update', $shift) : route('settings.shifts.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($shift))
                    @method('PUT')
                @endif

                <!-- Año Escolar -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="year_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Año Escolar <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="year_id" name="year_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un año escolar</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ old('year_id', $shift->year_id ?? '') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('year_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nombre del Turno -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="shift_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Jornada <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="shift_name" name="shift_name" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione una Jornada</option>
                            <option value="MATUTINA" {{ old('shift_name', $shift->shift_name ?? '') == 'MATUTINA' ? 'selected' : '' }}>Matutina</option>
                            <option value="VESPERTINA" {{ old('shift_name', $shift->shift_name ?? '') == 'VESPERTINA' ? 'selected' : '' }}>Vespertina</option>
                            <option value="INTENSIVO" {{ old('shift_name', $shift->shift_name ?? '') == 'INTENSIVO' ? 'selected' : '' }}>Intensivo</option>
                        </select>
                        @error('shift_name')
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
                            <option value="1" {{ old('status', $shift->status ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('status', $shift->status ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
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
                        href="{{ route('settings.shifts.index') }}"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    @if(isset($shift))
                        @can('editar-shift')
                            <flux:button 
                                type="submit"
                                icon="check-circle" 
                                color="primary"
                            >
                                {{ __('Actualizar Jornada Escolar') }}
                            </flux:button>
                        @endcan
                    @else
                        <flux:button 
                            type="submit"
                            icon="plus" 
                            color="primary"
                        >
                            {{ __('Crear Jornada Escolar') }}
                        </flux:button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>