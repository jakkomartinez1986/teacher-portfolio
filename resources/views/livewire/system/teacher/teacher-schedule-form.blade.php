<div>
    <!-- Formulario -->
    <div class=" rounded-lg shadow-md p-6 mb-6" x-data>
        <h2 class="text-xl font-semibold mb-4">
            {{ $editMode ? 'Editar Hora Clase' : 'Crear Nueva Hora Clase' }}
        </h2>
        
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Selector de Área y Asignatura -->
                {{-- <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Área</label>
                        <select wire:model.live="selectedArea" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Seleccione un área</option>
                            @foreach($areasConAsignaturas as $area)
                            @if($area['id'] == $selectedArea)
                                @foreach($area['subjects'] as $subject)
                                    <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                                @endforeach
                            @endif
                        @endforeach
                        </select>
                        @error('selectedArea') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asignatura</label>
                        <select wire:model.live="selectedSubject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Seleccione una asignatura</option>
                            @if($selectedArea)
                                @foreach($areasConAsignaturas->where('id', $selectedArea)->first()['subjects'] ?? [] as $subject)
                                    <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('selectedSubject') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div> --}}
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
            </div>
            
            {{-- <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clase</label>
                <input type="text" wire:model="classroom" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                @error('classroom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div> --}}
            
        
             <!-- Botones con Flux -->
             <div class="flex space-x-4 mt-6 pt-4 justify-end">
                <flux:button 
                    icon="arrow-uturn-left" 
                    href="{{ route('academic.teacher-schedule.index') }}"
                    variant="outline"
                >
                    {{ __('Cancelar') }}
                </flux:button>
                
                <flux:button 
                    type="submit"
                    icon="{{ isset($grade) ? 'check-circle' : 'plus' }}" 
                    color="primary"
                >
                {{ $editMode ? 'Actualizar' : 'Guardar' }}
                </flux:button>
            </div>
        </form>
    </div>
    
    <!-- Lista de horarios -->
    <div class=" rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Mis Horas</h2>
        
        @if($horarios->count() > 0)
            <div class="p-4 space-y-4 overflow-x-auto rounded-lg shadow relative">
                <table class="min-w-full border-separate border-spacing-y-2 text-left">
                    <thead>
                        <tr class="text-xs border-b uppercase font-semibold tracking-wider">
                            <th class="px-4 py-3">Día</th>
                            <th class="px-4 py-3">Horario</th>
                            <th class="px-4 py-3">Asignatura</th>
                            <th class="px-4 py-3">Grado</th>
                            <th class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody  class="text-sm ">
                        @foreach($horarios as $horario)
                        <tr class="hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors duration-200 border-b">
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
    Livewire.on('delete-prompt',(event)=>{
           
           const data=event            
           const id=data[0]['id'];
           Swal.fire({
               icon:data[0]['icon'],
               title:data[0]['title'],
               text:data[0]['text'],
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               cancelButtonText: 'Cancelar',
               confirmButtonText: 'Sí, eliminarlo!'
           }).then((result) => {
               if (result.isConfirmed) {
                 
                   Livewire.dispatch('deleteConfirmed', {"id":id});
                   
               }
           });
       });
   
});
</script>