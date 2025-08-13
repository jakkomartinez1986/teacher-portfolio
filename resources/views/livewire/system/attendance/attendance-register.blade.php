<div>
    @if($readyToLoad)
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    {{ $isDaily ? 'Registro Diario de Novedades' : 'Registro de Novedades por Clase' }}
                </h2>
                {{-- <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    {{ $selectedDate }} - Todos los estudiantes se consideran PRESENTES a menos que se registre una novedad
                </p> --}}
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded me-2 dark:bg-green-900 dark:text-green-200">
                        {{ $selectedDate }}
                    </span>
                    - Todos los estudiantes se consideran
                    <span class="inline-flex items-center bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-200">
                        PRESENTES
                    </span>
                    a menos que se registre una novedad
                </p>

             

                <!-- Selector de clase (para registro por hora) -->
                @if(!$isDaily)
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="selectedSchedule" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Seleccione la clase
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select 
                            wire:model.live="selectedSchedule" 
                            id="selectedSchedule"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                            >
                            <option value="">-- Seleccione --</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}">
                                    {{ $schedule->subject->subject_name }} - {{ $schedule->grade->grade_name }} 
                                    ({{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif               
                <!-- Tema de clase -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Tema de la clase *
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input 
                            type="text" 
                            wire:model="classtopic"
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                            placeholder="Ingrese el tema desarrollado en la clase"
                            required
                        >                        
                        @error('classtopic') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- Observacion general -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                     <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Observacion General
                        </label>
                    </div>                   
                    <div class="md:col-span-9">
                        <textarea 
                            wire:model="generalObservation" 
                            placeholder="Ej: Varios estudiantes no han realizado la tarea asignada..."
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                            rows="3"
                        ></textarea>
                    </div>
                </div>
            </div>
                <!-- Lista de estudiantes para registrar novedades -->
                <div class="space-y-4">
                    @if(count($students) > 0)
                        @foreach($students as $student)
                        <div class="rounded-lg border border-neutral-200 p-4 hover:bg-neutral-200/30 dark:border-neutral-700 dark:hover:bg-neutral-600/30">
                            <div class="flex items-center justify-between">                            
                                <div>
                                    {{ $student->user->lastname }}, {{ $student->user->name }}
                                    @php
                                        $hasNovedad = isset($novedades[$student->id]);
                                        $status = $hasNovedad ? preg_replace('/\s+/', '', $novedades[$student->id]['status'] ?? null) : null;
                                        
                                        if (!$hasNovedad || $status === null) {
                                            $text = 'PRESENTE';
                                            $colorClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        } else {
                                            $text = $this->getStatusText($status);
                                            $colorClass = $this->getStatusColor($status);
                                        }
                                    @endphp
                                    
                                    <span class="ml-2 text-xs font-medium px-2 py-1 rounded-full {{ $colorClass }}">
                                        {{ $text }}
                                    </span>
                                </div>
                                <flux:button 
                                    wire:click="toggleNovedad({{ $student->id }})"
                                    size="sm"
                                    variant="outline">
                                    {{ isset($novedades[$student->id]['show']) && $novedades[$student->id]['show'] ? 'Cancelar' : 'Registrar Novedad' }}
                                </flux:button>
                            </div>

                            @if(isset($novedades[$student->id]['show']) && $novedades[$student->id]['show'])
                            <div class="mt-4 space-y-4">
                                <!-- Tipo de novedad -->
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                                    <div class="md:col-span-3">
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                            Tipo de Novedad *
                                        </label>
                                    </div>
                                    <div class="md:col-span-9">
                                        <select 
                                            wire:model.lazy="novedades.{{ $student->id }}.status"
                                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                                        >
                                            <option value="">-- Seleccione --</option>
                                            <option value="A">Atraso</option>
                                            <option value="I">Falta Injustificada</option>
                                            <option value="J">Falta Justificada</option>
                                            <option value="AA">Abandono Aula</option>
                                            <option value="AI">Abandono Institucional</option>
                                            <option value="P">Permiso</option>
                                            <option value="N">Novedad Estudiante</option>
                                        </select>
                                        @error('novedades.'.$student->id.'.status') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Campos condicionales -->
                                @if(($novedades[$student->id]['status'] ?? '') === 'A')
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                                    <div class="md:col-span-3">
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                            Hora de llegada *
                                        </label>
                                    </div>
                                    <div class="md:col-span-9">
                                        <input 
                                            type="time" 
                                            wire:model.lazy="novedades.{{ $student->id }}.arrival_time"
                                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                                        >
                                        @error('novedades.'.$student->id.'.arrival_time') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                @endif

                                @if(in_array($novedades[$student->id]['status'] ?? '', ['J', 'P']))
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                                    <div class="md:col-span-3">
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                            Justificación *
                                        </label>
                                    </div>
                                    <div class="md:col-span-9">
                                        <input 
                                            type="text" 
                                            wire:model.lazy="novedades.{{ $student->id }}.justification"
                                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                                            placeholder="Motivo de la falta/permiso"
                                        >
                                        @error('novedades.'.$student->id.'.justification') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                                    <div class="md:col-span-3">
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                            Comprobante (opcional)
                                        </label>
                                    </div>
                                    <div class="md:col-span-9">
                                        <input 
                                            type="file" 
                                            wire:model="justificationFiles.{{ $student->id }}"
                                            class="block w-full text-sm text-neutral-600
                                                file:mr-4 file:rounded-md file:border-0
                                                file:bg-primary-50 file:px-4 file:py-2
                                                file:text-sm file:font-semibold
                                                file:text-primary-700 hover:file:bg-primary-100
                                                dark:file:bg-neutral-700 dark:file:text-neutral-200"
                                        >
                                    </div>
                                    <!-- Para cada estudiante, en la sección de novedades -->
                                    @error('novedades.'.$student->id.'.status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                <!-- Observación individual para el estudiante -->
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                                    <div class="md:col-span-3">
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                            Observación individual
                                        </label>
                                    </div>
                                    <div class="md:col-span-9">
                                        <input list="observaciones" 
                                            wire:model.lazy="novedades.{{ $student->id }}.observation"
                                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 mb-2"
                                            placeholder="Seleccione o escriba una observación">
                                        
                                        <datalist id="observaciones">
                                            <option value="NO PRESENTA TAREAS">
                                            <option value="MAL COMPORTAMIENTO">
                                        </datalist>
                                        
                                        <textarea 
                                            wire:model.lazy="novedades.{{ $student->id }}.observation"
                                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200"
                                            rows="2"
                                            placeholder="O escriba aquí su observación personalizada"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach

                        <div class="flex justify-end space-x-4 pt-4">
                            <flux:button 
                                wire:click="save" 
                                wire:loading.attr="disabled"
                                color="primary"
                                icon="check-circle"
                            >
                                <span wire:loading wire:target="save">Guardando...</span>
                                <span wire:loading.remove wire:target="save">Guardar Novedades</span>
                            </flux:button>
                        </div>
                        @error('classtopic')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        @foreach($students as $student)
                            @error('novedades.' . $student->id . '.status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            @error('novedades.' . $student->id . '.arrival_time')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            @error('novedades.' . $student->id . '.justification')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        @endforeach
                    @else
                        <div class="rounded-lg border border-neutral-200 p-4 hover:bg-neutral-200/30 dark:border-neutral-700 dark:hover:bg-neutral-600/30">
                            @if($isDaily)
                                <p>No se encontraron estudiantes para este grado.</p>
                            @else
                                <p>Seleccione una clase para registrar novedades.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <p>Cargando...</p>
        </div>
    @endif
</div>
<script>
    document.addEventListener('livewire:initialized', function () {
        @this.on('swal', (event) => {
            const data = event;
            Swal.fire({
                icon: data[0]['icon'],
                title: data[0]['title'],
                text: data[0]['text'],
            });
        });

        Livewire.on('delete-prompt', (event) => {
            const data = event;
            const id = data[0]['id'];
            Swal.fire({
                icon: data[0]['icon'],
                title: data[0]['title'],
                text: data[0]['text'],
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteConfirmed', { id });
                }
            });
        });
    });
</script>