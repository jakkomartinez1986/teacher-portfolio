<div class="max-w-2xl mx-auto p-4 rounded-lg shadow-md">
    <h1 class="text-xl font-semibold mb-4">{{ __('Horario Docente ') }} </h1>
    
    <!-- Selector de días -->
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach ($diasDeLaSemana as $diaNombre => $diaFecha)         
        <button 
           wire:click.prevent="actualizarDia('{{ $diaNombre }}')" 
            class="py-1 px-3 border-b-2 transition-colors duration-200
            {{ $diaNombre == $diaSeleccionado ? 'border-green-500 bg-green-500 text-white rounded-md' : 'text-blue-600 dark:text-blue-400 border-blue-600 dark:border-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700' }}">
            {{ strtoupper($diaNombre) }}
        </button>
        @endforeach
    </div>

    <!-- Rango de semana (solo muestra si las propiedades existen) -->
    @isset($inicioSemanaLaboralFormatted, $finSemanaLaboralFormatted)
    <div class="flex items-center mb-4 text-gray-600 dark:text-gray-400">
        <span>{{ $inicioSemanaLaboralFormatted }} - {{ $finSemanaLaboralFormatted }}</span>
        <i class="far fa-calendar-alt ml-2"></i>
    </div>
    @endisset      
   
    <!-- Lista de horarios -->
    @if ($horarios->count() > 0)
        @foreach ($horarios as $horario)
        <div class="flex flex-wrap items-center justify-between p-3 rounded-lg shadow-md mb-4">
            <div class="flex flex-wrap items-center gap-2 sm:gap-4 w-full">
                <!-- Hora -->
                <div class="text-gray-600 dark:text-gray-400 text-sm flex-grow">
                    {{ $horario->start_time->format('H:i') }} - {{ $horario->end_time->format('H:i') }}
                </div>
                
                <!-- Asignatura -->
                <div class="text-gray-900 dark:text-white text-base font-semibold flex-grow">
                    {{ $horario->subject->subject_name }}
                </div>
                
                <!-- Duración -->
                <div class="text-gray-600 dark:text-gray-400 text-sm flex-grow">
                    {{ __('Duración: '). $horario->start_time->diffInMinutes($horario->end_time) }} min
                </div>
                
                <!-- Acciones -->
                <div class="ml-auto flex gap-2 sm:gap-3">
                    <flux:button.group>
                        <flux:button 
                            href="{{ route('settings.grades.show',$horario->grade) }}" 
                            icon="pencil" 
                            size="sm" 
                            color="primary" >                      
                        </flux:button>
                        <flux:button 
                            href="{{ route('settings.grades.show',$horario->grade) }}" 
                            icon="eye" 
                            size="sm" 
                            color="primary" >                      
                        </flux:button>
                        <flux:button 
                            wire:click.prevent="delete({{ $horario->id }})"
                            icon="trash" 
                            size="sm" 
                            color="primary" >                      
                        </flux:button>
                    </flux:button.group>
                    {{-- <flux:button.group>
                        <flux:button 
                            href="{{ route('settings.grades.show',$horario->grade) }}" 
                            size="sm" 
                            icon="pencil"
                            color="primary" >
                        </flux:button>
                        <flux:button href="" size="sm" icon="eye"color="primary"></flux:button>
                        <flux:button wire:click.prevent="" size="sm" icon="trash"color="primary"></flux:button>
                    </flux:button.group> --}}
                   
                </div>
            </div>
           
            <div class="flex flex-wrap items-center gap-2 sm:gap-4 w-full mt-2">
                <!-- Información del grado -->
                <div class="text-gray-600 dark:text-gray-400 text-sm flex-grow">
                    {{ __('Jornada: ') . ($horario->grade->nivel->shift->shift_name ?? 'N/A') . ' | Nivel: ' . ($horario->grade->nivel->nivel_name ?? 'N/A') . ' | Grado: ' . $horario->grade->grade_name . ' / ' . $horario->grade->section }}
                  
                </div>                                               
            </div>               
        </div>
        @endforeach
    @else
        <div class="flex flex-wrap items-center justify-between bg-gray-100 dark:bg-gray-700 p-3 rounded-lg shadow-md">
            <div class="flex items-center gap-4">          
                <div class="text-gray-900 dark:text-white text-base font-semibold">
                    {{ __('No hay horas clase para el día seleccionado') }}
                </div>
            </div>
        </div>
    @endif  

    <!-- Botones de acción -->
    <div class="flex space-x-4 mt-6 pt-4 justify-end"> 
        <flux:button 
            href="{{ route('academic.teacher-schedule.create') }}" 
            icon="plus" 
            color="primary"
        >
            {{ __('Agregar Hora Clase') }}
        </flux:button>
    </div>
</div>

{{-- @push('scripts') --}}
<script>
    document.addEventListener('livewire:initialized', function () {
        @this.on('swal',(event)=>{
            const data=event
            swal.fire({
                icon:data[0]['icon'],
                title:data[0]['title'],
                text:data[0]['text'],
            })
        })

  
       
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
{{-- @endpush --}}