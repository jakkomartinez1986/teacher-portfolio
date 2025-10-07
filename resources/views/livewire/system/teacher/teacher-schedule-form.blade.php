<div>
    <!-- Selección de Tipo de Horario (solo si no está seleccionado) -->
    @if(!$scheduleTypeSelected)
    <div class="rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Selecciona el Tipo de Horario</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Elige el tipo de horario que vas a crear. Esta selección se aplicará a todas las horas que agregues.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($scheduleTypes as $value => $label)
            <div class="border-2 rounded-lg p-4 cursor-pointer transition-all duration-200 
                hover:border-blue-500 hover:shadow-md 
                {{ $scheduleType === $value ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600' }}"
                wire:click="selectScheduleType('{{ $value }}')">
                <div class="text-center">
                    <div class="mb-2">
                        @if($value === 'OFFICIAL')
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto">
                            <span class="text-green-600 dark:text-green-400">📚</span>
                        </div>
                        @elseif($value === 'EVALUATION')
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto">
                            <span class="text-red-600 dark:text-red-400">📝</span>
                        </div>
                        @elseif($value === 'TEST')
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto">
                            <span class="text-yellow-600 dark:text-yellow-400">🧪</span>
                        </div>
                        @elseif($value === 'MAKEUP')
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto">
                            <span class="text-purple-600 dark:text-purple-400">🔄</span>
                        </div>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $label }}</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                        @if($value === 'OFFICIAL')
                        Horario regular de clases
                        @elseif($value === 'EVALUATION')
                        Para exámenes y evaluaciones
                        @elseif($value === 'TEST')
                        Horario temporal de prueba
                        @elseif($value === 'MAKEUP')
                        Para clases de recuperación
                        @endif
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <!-- Formulario principal (solo se muestra después de seleccionar el tipo) -->
    <div class="rounded-lg shadow-md p-6 mb-6" x-data>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">
                {{ $editMode ? 'Editar Hora Clase' : 'Crear Nueva Hora Clase' }}
            </h2>
            
            <!-- Indicador del tipo de horario seleccionado -->
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Tipo seleccionado:</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                    @if($scheduleType === 'OFFICIAL') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                    @elseif($scheduleType === 'EVALUATION') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                    @elseif($scheduleType === 'TEST') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                    @elseif($scheduleType === 'MAKEUP') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                    @endif">
                    {{ $scheduleTypes[$scheduleType] }}
                </span>
                <button 
                    wire:click="changeScheduleType"
                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                    title="Cambiar tipo de horario"
                >
                    Cambiar
                </button>
            </div>
        </div>
        
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Selector de Área -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Área</label>
                        <select wire:model.live="selectedArea" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Seleccione un área</option>
                            @foreach($areasConAsignaturas as $area)
                                <option value="{{ $area['id'] }}">{{ $area['name'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedArea') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>               
                    <!-- Selector de Asignatura -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asignatura</label>
                        <select wire:model="selectedSubject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Seleccione una asignatura</option>
                            @if($selectedArea)
                                @foreach($areasConAsignaturas as $area)
                                    @if($area['id'] == $selectedArea)
                                        @foreach($area['subjects'] as $subject)
                                            <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @error('selectedSubject') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Selector de Jornada, Nivel y Grado -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jornada</label>
                        <select wire:model.live="selectedJornada" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Seleccione jornada</option>
                            @foreach($jornadas as $jornada)
                                <option value="{{ $jornada['id'] }}">{{ $jornada['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nivel</label>
                        <select wire:model.live="selectedNivel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Seleccione nivel</option>
                            @foreach($nivelesPorJornada as $nivel)
                                <option value="{{ $nivel['id'] }}">{{ $nivel['name'] }}-{{ $nivel['shift_name'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedNivel') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grado</label>
                        <select wire:model="selectedGrade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Seleccione grado</option>
                            @foreach($gradosPorNivel as $grado)
                                <option value="{{ $grado['id'] }}">{{ $grado['name'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedGrade') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            
            <!-- Información del horario -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <!-- Trimestre (solo para evaluación y recuperación) -->
                @if($requiresTrimester)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Trimestre *
                    </label>
                    <select 
                        wire:model="trimester_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600"
                        required
                    >
                        <option value="">Seleccionar Trimestre</option>
                        @foreach($trimestres as $trimestre)
                            <option value="{{ $trimestre->id }}">
                                {{ $trimestre->trimester_name }}
                            </option>
                        @endforeach
                    </select>
                    @if(!$trimester_id)
                        <span class="text-red-500 text-xs">El trimestre es requerido para este tipo de horario</span>
                    @endif
                </div>
                @else
                <!-- Para otros tipos, mostrar que es global -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Alcance del Horario
                    </label>
                    <div class="mt-1 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <strong>Global:</strong> Este horario aplica para <strong>todos los trimestres</strong> del año académico.
                        </p>
                    </div>
                </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Día</label>
                    <select wire:model="day" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                        <option value="">Seleccione día</option>
                        @foreach($diasSemana as $dia)
                            <option value="{{ $dia }}">{{ $dia }}</option>
                        @endforeach
                    </select>
                    @error('day') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Información adicional sobre el tipo seleccionado -->
            <div class="flex items-center">
                @if($scheduleType === 'OFFICIAL')
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                        <p class="text-sm text-green-800 dark:text-green-300">
                            <strong>Horario Oficial:</strong> Aplica para <strong>todos los trimestres</strong> del año académico.
                        </p>
                    </div>
                @elseif($scheduleType === 'EVALUATION')
                    <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                        <p class="text-sm text-red-800 dark:text-red-300">
                            <strong>Horario de Evaluación:</strong> Específico para un <strong>trimestre de evaluación</strong>.
                        </p>
                    </div>
                @elseif($scheduleType === 'TEST')
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                        <p class="text-sm text-yellow-800 dark:text-yellow-300">
                            <strong>Horario de Prueba:</strong> Aplica para <strong>todos los trimestres</strong> (configuración temporal).
                        </p>
                    </div>
                @elseif($scheduleType === 'MAKEUP')
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-md">
                        <p class="text-sm text-purple-800 dark:text-purple-300">
                            <strong>Horario de Recuperación:</strong> Específico para <strong>actividades de recuperación por trimestre</strong>.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Horas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora inicio</label>
                        <input type="time" wire:model="start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                        @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora fin</label>
                        <input type="time" wire:model="end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                        @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <!-- Información adicional sobre el tipo seleccionado -->
                <div class="flex items-center">
                    @if($scheduleType === 'OFFICIAL')
                        <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                            <p class="text-sm text-green-800 dark:text-green-300">
                                <strong>Horario Oficial:</strong> Aplica para todos los trimestres del año académico.
                            </p>
                        </div>
                    @elseif($scheduleType === 'EVALUATION')
                        <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                            <p class="text-sm text-red-800 dark:text-red-300">
                                <strong>Horario de Evaluación:</strong> Específico para un trimestre de evaluación.
                            </p>
                        </div>
                    @elseif($scheduleType === 'TEST')
                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                <strong>Horario de Prueba:</strong> Puede ser global o específico por trimestre.
                            </p>
                        </div>
                    @elseif($scheduleType === 'MAKEUP')
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-md">
                            <p class="text-sm text-purple-800 dark:text-purple-300">
                                <strong>Horario de Recuperación:</strong> Específico para actividades de recuperación por trimestre.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
                     
            <!-- Botones con Flux -->
            <div class="flex space-x-4 mt-6 pt-4 justify-end">
                <flux:button 
                    type="button"
                    icon="arrow-uturn-left" 
                    variant="outline"
                    wire:click="changeScheduleType"
                >
                    {{ __('Cambiar Tipo') }}
                </flux:button>
                
                <flux:button 
                    type="submit"
                    icon="{{ $editMode ? 'check-circle' : 'plus' }}" 
                    color="primary"
                >
                    {{ $editMode ? 'Actualizar' : 'Agregar Hora' }}
                </flux:button>
            </div>
        </form>
    </div>
    @endif
    
    <!-- Lista de horarios -->
    <div class="rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Mis Horas</h2>
        
        @if($horarios->count() > 0)
            <div class="p-4 space-y-4 overflow-x-auto rounded-lg shadow relative">
                <table class="min-w-full border-separate border-spacing-y-2 text-left">
                    <thead>
                        <tr class="text-xs border-b uppercase font-semibold tracking-wider">
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3">Día</th>
                            <th class="px-4 py-3">Horario</th>
                            <th class="px-4 py-3">Asignatura</th>
                            <th class="px-4 py-3">Grado</th>
                            <th class="px-4 py-3">Trimestre</th>
                            <th class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($horarios as $horario)
                        <tr class="hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors duration-200 border-b">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($horario->schedule_type === 'OFFICIAL') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($horario->schedule_type === 'EVALUATION') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @elseif($horario->schedule_type === 'TEST') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($horario->schedule_type === 'MAKEUP') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                                    {{ $scheduleTypes[$horario->schedule_type] ?? $horario->schedule_type }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $horario->day }}</td>
                            <td class="px-4 py-3">
                                {{ $horario->start_time->format('H:i') }} - {{ $horario->end_time->format('H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $horario->subject->subject_name }} ({{ $horario->subject->area->area_name }})
                            </td>
                            <td class="px-4 py-3">
                                {{ $horario->grade->grade_name }} - {{ $horario->grade->section }} ({{ $horario->grade->nivel->nivel_name }})
                            </td>
                            <td class="px-4 py-3">
                                @if($horario->trimester_id)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ $horario->trimester->trimester_name ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        Global
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="edit({{ $horario->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    Editar
                                </button>
                                <button wire:click="delete({{ $horario->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">No hay horarios registrados</p>
        @endif
    </div>   
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
                    Livewire.dispatch('deleteConfirmed', {"id": id});
                }
            });
        });
    });
</script>