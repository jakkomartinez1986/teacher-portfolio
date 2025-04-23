<x-layouts.app :title="isset($nivel) ? __('Editar Nivel') : __('Crear Nivel')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.nivels.index') }}">Niveles</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($nivel) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($nivel) ? 'Editar Nivel' : 'Crear Nuevo Nivel' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($nivel) ? route('settings.nivels.update', $nivel) : route('settings.nivels.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($nivel))
                    @method('PUT')
                @endif

                <!-- Turno -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="shift_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Año/Jornada <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="shift_id" name="shift_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un Año/Jornada</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}" {{ old('shift_id', $nivel->shift_id ?? '') == $shift->id ? 'selected' : '' }}>
                                    {{ $shift->year->year_name }}   - {{ $shift->shift_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('shift_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nombre del Nivel -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="nivel_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre del Nivel <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="nivel_name" type="text" name="nivel_name" 
                               value="{{ old('nivel_name', $nivel->nivel_name ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('nivel_name')
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
                            <option value="1" {{ old('status', $nivel->status ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('status', $nivel->status ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
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
                    href="{{ route('settings.nivels.index') }}"
                    variant="outline"
                >
                    {{ __('Volver a la Lista') }}
                </flux:button>
                
                <flux:button 
                    type="submit"
                    icon="{{ isset($nivel) ? 'check-circle' : 'plus' }}" 
                    color="primary"
                >
                    {{ isset($nivel) ? __('Actualizar Nivel') : __('Crear Nivel') }}
                </flux:button>
            </div>
            </form>
        </div>
    </div>
</x-layouts.app>