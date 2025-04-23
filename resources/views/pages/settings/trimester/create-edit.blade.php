<x-layouts.app :title="isset($trimester) ? __('Editar Trimestre') : __('Crear Trimestre')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.trimesters.index') }}">Trimestres</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($trimester) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($trimester) ? 'Editar Trimestre' : 'Crear Nuevo Trimestre' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($trimester) ? route('settings.trimesters.update', $trimester) : route('settings.trimesters.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($trimester))
                    @method('PUT')
                @endif

                <!-- Nombre del Trimestre -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="trimester_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre del Trimestre <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="trimester_name" type="text" name="trimester_name" 
                               value="{{ old('trimester_name', $trimester->trimester_name ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('trimester_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                               value="{{ old('start_date', isset($trimester) ? $trimester->start_date->format('Y-m-d') : '') }}" required
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
                               value="{{ old('end_date', isset($trimester) ? $trimester->end_date->format('Y-m-d') : '') }}" required
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
                            <option value="1" {{ old('status', $trimester->status ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('status', $trimester->status ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                {{-- <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('settings.trimesters.index') }}" 
                       class="inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-600">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        {{ isset($trimester) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div> --}}

                 <!-- Botones -->
                 <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('settings.trimesters.index') }}"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    @if(isset($trimester))
                        @can('editar-trimester')
                            <flux:button 
                                type="submit"
                                icon="check-circle" 
                                color="primary"
                            >
                                {{ __('Actualizar Periodo') }}
                            </flux:button>
                        @endcan
                    @else
                        <flux:button 
                            type="submit"
                            icon="plus" 
                            color="primary"
                        >
                            {{ __('Crear Periodo') }}
                        </flux:button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>