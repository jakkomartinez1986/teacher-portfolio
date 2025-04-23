<x-layouts.app :title="isset($classSchedule) ? __('Editar Horario') : __('Crear Horario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('academic.class-schedules.index') }}">Horarios</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($classSchedule) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($classSchedule) ? 'Editar Hora Clase' : 'Crear Nuevo Hora Clase' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($classSchedule) ? route('academic.class-schedules.update', $classSchedule) : route('academic.class-schedules.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($classSchedule))
                    @method('PUT')
                @endif

                <!-- Docente -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="teacher_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Docente <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="teacher_id" name="teacher_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un docente</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $classSchedule->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Asignatura -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="subject_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Asignatura <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="subject_id" name="subject_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione una asignatura</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $classSchedule->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->subject_name }} ({{ $subject->area->area_name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Grado -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="grade_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Grado <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="grade_id" name="grade_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un grado</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}" {{ old('grade_id', $classSchedule->grade_id ?? '') == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->grade_name }} - {{ $grade->section }} ({{ $grade->nivel->nivel_name ?? '' }}-{{ $grade->nivel->shift->shift_name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('grade_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- <!-- Trimestre -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="trimester_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Trimestre <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="trimester_id" name="trimester_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un trimestre</option>
                            @foreach($trimesters as $trimester)
                                <option value="{{ $trimester->id }}" {{ old('trimester_id', $classSchedule->trimester_id ?? '') == $trimester->id ? 'selected' : '' }}>
                                    {{ $trimester->trimester_name }} ({{ $trimester->start_date->format('d/m/Y') }} - {{ $trimester->end_date->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('trimester_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> --}}

                <!-- Día de la semana -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="day" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Día <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="day" name="day" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un día</option>
                            <option value="LUNES" {{ old('day', $classSchedule->day ?? '') == 'LUNES' ? 'selected' : '' }}>Lunes</option>
                            <option value="MARTES" {{ old('day', $classSchedule->day ?? '') == 'MARTES' ? 'selected' : '' }}>Martes</option>
                            <option value="MIÉRCOLES" {{ old('day', $classSchedule->day ?? '') == 'MIÉRCOLES' ? 'selected' : '' }}>Miércoles</option>
                            <option value="JUEVES" {{ old('day', $classSchedule->day ?? '') == 'JUEVES' ? 'selected' : '' }}>Jueves</option>
                            <option value="VIERNES" {{ old('day', $classSchedule->day ?? '') == 'VIERNES' ? 'selected' : '' }}>Viernes</option>
                            {{-- <option value="SÁBADO" {{ old('day', $classSchedule->day ?? '') == 'SÁBADO' ? 'selected' : '' }}>Sábado</option> --}}
                        </select>
                        @error('day')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Hora de inicio y fin -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Horario <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm text-neutral-500 dark:text-neutral-400">Hora inicio</label>
                            <input id="start_time" type="time" name="start_time" 
                                   value="{{ old('start_time', isset($classSchedule) ? $classSchedule->start_time->format('H:i') : '') }}" required
                                   class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm text-neutral-500 dark:text-neutral-400">Hora fin</label>
                            <input id="end_time" type="time" name="end_time" 
                                   value="{{ old('end_time', isset($classSchedule) ? $classSchedule->end_time->format('H:i') : '') }}" required
                                   class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Aula -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="classroom" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Aula
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="classroom" type="text" name="classroom" 
                               value="{{ old('classroom', $classSchedule->classroom ?? '') }}"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('classroom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Estado -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Estado
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" 
                                   {{ old('is_active', $classSchedule->is_active ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700">
                            <label for="is_active" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                Activo
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Notas -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="notes" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Notas
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <textarea id="notes" name="notes" rows="3"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">{{ old('notes', $classSchedule->notes ?? '') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones con Flux -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('academic.class-schedules.index') }}"
                        variant="outline"
                    >
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="{{ isset($classSchedule) ? 'check-circle' : 'plus' }}" 
                        color="primary"
                    >
                        {{ isset($classSchedule) ? __('Actualizar Hora Clase') : __('Crear Hora Clase') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>