<x-layouts.app :title="isset($gradingSetting) ? __('Editar Configuración de Calificación') : __('Crear Configuración de Calificación')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.grading-settings.index') }}">Configuración de Calificación</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($gradingSetting) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($gradingSetting) ? 'Editar Configuración de Calificación' : 'Crear Nueva Configuración de Calificación' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($gradingSetting) ? route('settings.grading-settings.update', $gradingSetting) : route('settings.grading-settings.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($gradingSetting))
                    @method('PUT')
                @endif

                <!-- Año -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="year_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Año Lectivo <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="year_id" name="year_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un Año</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ old('year_id', $gradingSetting->year_id ?? '') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('year_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- <!-- Porcentaje Formativo -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="formative_percentage" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            % Evaluación Formativa <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="formative_percentage" type="number" name="formative_percentage" 
                            value="{{ old('formative_percentage', $gradingSetting->formative_percentage ?? '') }}" 
                            required
                            min="0"
                            max="100"
                            step="0.01"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('formative_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Porcentaje Sumativo -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="summative_percentage" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            % Evaluación Sumativa <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="summative_percentage" type="number" name="summative_percentage" 
                            value="{{ old('summative_percentage', $gradingSetting->summative_percentage ?? '') }}" 
                            required
                            min="0"
                            max="100"
                            step="0.01"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('summative_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Porcentaje Examen -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="exam_percentage" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            % Examen (Sumativo) <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="exam_percentage" type="number" name="exam_percentage" 
                            value="{{ old('exam_percentage', $gradingSetting->exam_percentage ?? '') }}" 
                            required
                            min="0"
                            max="100"
                            step="0.01"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('exam_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Porcentaje Proyecto -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="project_percentage" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            % Proyecto (Sumativo) <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="project_percentage" type="number" name="project_percentage" 
                            value="{{ old('project_percentage', $gradingSetting->project_percentage ?? '') }}" 
                            required
                            min="0"
                            max="100"
                            step="0.01"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('project_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> --}}

                <!-- Porcentaje Formativo -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="formative_percentage" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            % Evaluación Formativa <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="formative_percentage" type="number" name="formative_percentage" 
                            value="{{ old('formative_percentage', $gradingSetting->formative_percentage ?? '') }}" 
                            required
                            min="0"
                            max="100"
                            step="0.01"
                            oninput="calculateSummative()"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('formative_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Porcentaje Sumativo (calculado y mostrado, pero no editable) -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            % Evaluación Sumativa (Calculado)
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input type="text" 
                            id="summative_percentage_display"
                            readonly
                            class="block w-full rounded-md border-neutral-300 bg-neutral-100 shadow-sm dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        <input type="hidden" id="summative_percentage" name="summative_percentage" value="{{ old('summative_percentage', $gradingSetting->summative_percentage ?? '') }}">
                    </div>
                </div>

                <!-- Porcentaje Examen -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="exam_percentage" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            % Examen (Sumativo) <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="exam_percentage" type="number" name="exam_percentage" 
                            value="{{ old('exam_percentage', $gradingSetting->exam_percentage ?? '') }}" 
                            required
                            min="0"
                            max="100"
                            step="0.01"
                            oninput="calculateSummativeComponents()"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('exam_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Porcentaje Proyecto -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="project_percentage" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            % Proyecto (Sumativo) <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="project_percentage" type="number" name="project_percentage" 
                            value="{{ old('project_percentage', $gradingSetting->project_percentage ?? '') }}" 
                            required
                            min="0"
                            max="100"
                            step="0.01"
                            oninput="calculateSummativeComponents()"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('project_percentage')
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
                            <option value="1" {{ old('status', $gradingSetting->status ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('status', $gradingSetting->status ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Validación de porcentajes -->
                @if($errors->has('percentages'))
                    <div class="rounded-md bg-red-50 p-4 dark:bg-red-900 dark:bg-opacity-20">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400 dark:text-red-300" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    Error en los porcentajes
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <p>{{ $errors->first('percentages') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Botones con Flux -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('settings.grading-settings.index') }}"
                        variant="outline"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="{{ isset($gradingSetting) ? 'check-circle' : 'plus' }}" 
                        color="primary"
                    >
                        {{ isset($gradingSetting) ? __('Actualizar Configuración') : __('Crear Configuración') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
    <!-- JavaScript para los cálculos -->
<script>
    function calculateSummative() {
        const formative = parseFloat(document.getElementById('formative_percentage').value) || 0;
        const summative = 100 - formative;
        
        document.getElementById('summative_percentage_display').value = summative.toFixed(2) + '%';
        document.getElementById('summative_percentage').value = summative.toFixed(2);
        
        // Ajustar los componentes sumativos si es necesario
        calculateSummativeComponents();
    }

    function calculateSummativeComponents() {
        const exam = parseFloat(document.getElementById('exam_percentage').value) || 0;
        const project = parseFloat(document.getElementById('project_percentage').value) || 0;
        const summativeTotal = exam + project;
        
        const summativePercentage = parseFloat(document.getElementById('summative_percentage').value) || 0;
        
        if (summativeTotal > summativePercentage) {
            // Mostrar advertencia si la suma de componentes supera el sumativo
            alert('La suma de Examen y Proyecto (' + summativeTotal.toFixed(2) + '%) no puede ser mayor que el Sumativo (' + summativePercentage.toFixed(2) + '%)');
            
            // Ajustar los valores para que no excedan
            const ratio = summativePercentage / summativeTotal;
            document.getElementById('exam_percentage').value = (exam * ratio).toFixed(2);
            document.getElementById('project_percentage').value = (project * ratio).toFixed(2);
        }
    }

    // Inicializar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        calculateSummative();
        calculateSummativeComponents();
    });
</script>

</x-layouts.app>