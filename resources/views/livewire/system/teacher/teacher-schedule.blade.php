

<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 rounded-lg shadow-md">
    <div>
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
                            @php
                                    $user = auth()->user();
                                    $esTutor = $user->hasRole('TUTOR');
                                    $asignaturaEsAcompanamiento = $horario->subject->subject_name === 'Acompañamiento integral en el aula';
                                    //$tutorDelCurso = $user->grades->contains($horario->grade_id ?? null); // Ajusta esto según tu relación
                            @endphp
                            <flux:button.group>                        

                                @if ($esTutor && $asignaturaEsAcompanamiento)
                                <flux:button 
                                        href="{{ route('students.students.import.form', $horario->grade) }}" 
                                        icon="user-plus" 
                                        size="sm" 
                                        color="primary">                      
                                    </flux:button>

                                @endif
                                <flux:button 
                                    href="{{ route('settings.grades.show',$horario->grade) }}" 
                                    icon="clipboard-document-check" 
                                    size="sm" 
                                    color="primary" >                      
                                </flux:button>
                                <flux:button 
                                    href="{{ route('academic.grading-summary.index',$horario->grade) }}" 
                                    icon="pencil" 
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
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @php
            $totalMinutos40 = 0;
            $totalMinutos45 = 0;

            foreach ($horariodocente as $h) {
                $nivel = strtolower($h->grade->nivel->nivel_name ?? '');
                $minutos = $h->start_time->diffInMinutes($h->end_time);

                if (str_contains($nivel, 'bachillerato_técnico')) {
                    $totalMinutos40 += $minutos;
                } else {
                    $totalMinutos45 += $minutos;
                }
            }

            $totalHoras40 = round($totalMinutos40 / 40, 2);
            $totalHoras45 = round($totalMinutos45 / 45, 2);
            $horasTotales = round($totalHoras40 + $totalHoras45, 2);
        @endphp

        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
            Distributivo
            <span class="block text-base font-normal text-gray-600 dark:text-gray-400 mt-1">
                Total: {{ $horasTotales }} horas clase
                ({{ $totalHoras40 }} h en BT - 40 min | {{ $totalHoras45 }} h en EGB/BGU - 45 min)
            </span>
        </h1>

        <div class="overflow-x-auto rounded-lg shadow ring-1 ring-black ring-opacity-5">
            <table class="min-w-full border-separate border-spacing-y-2 text-left ">
                <thead>
                    <tr class="text-xs border-b uppercase font-semibold tracking-wider">
                        <th class="px-4 py-3">Asignatura</th>
                        <th class="px-4 py-3">Curso</th>
                        <th class="px-4 py-3">Nivel</th>
                        <th class="px-4 py-3">Bloques</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach ($horariodocente->groupBy(['subject.subject_name', 'grade.grade_name']) as $asignatura => $cursos)
                        @foreach ($cursos as $curso => $horas)
                            @php
                                $primerHorario = $horas->first();
                                $nivel = strtolower($primerHorario->grade->nivel->nivel_name ?? 'N/A');
                                $minutosTotales = $horas->sum(fn($h) => $h->start_time->diffInMinutes($h->end_time));
                                $bloque = str_contains($nivel, 'bachillerato_técnico') ? 40 : 45;
                                $bloques = intdiv($minutosTotales, $bloque);
                            @endphp
                           
                             <tr class="hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors duration-200 border-b">
                                <td class="px-4 py-3">{{ $asignatura }}</td>
                                <td class="px-4 py-3">{{ $curso }}</td>
                                <td class="px-4 py-3">{{ $primerHorario->grade->nivel->nivel_name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $bloques }} bloques de {{ $bloque }} min</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


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