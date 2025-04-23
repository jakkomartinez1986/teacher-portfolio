<x-layouts.app :title="isset($subject) ? __('Editar Asignatura') : __('Crear Asignatura')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.subjects.index') }}">Asignaturas</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($subject) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($subject) ? 'Editar Asignatura' : 'Crear Nueva Asignatura' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($subject) ? route('settings.subjects.update', $subject) : route('settings.subjects.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($subject))
                    @method('PUT')
                @endif

                <!-- Área -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="area_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Área <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="area_id" name="area_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un Área</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $subject->area_id ?? '') == $area->id ? 'selected' : '' }}>
                                    {{ $area->area_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nombre de la Asignatura -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="subject_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre de la Asignatura <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="subject_name" type="text" name="subject_name" 
                               value="{{ old('subject_name', $subject->subject_name ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('subject_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones con Flux -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('settings.subjects.index') }}"
                        variant="outline"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="{{ isset($subject) ? 'check-circle' : 'plus' }}" 
                        color="primary"
                    >
                        {{ isset($subject) ? __('Actualizar Asignatura') : __('Crear Asignatura') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>