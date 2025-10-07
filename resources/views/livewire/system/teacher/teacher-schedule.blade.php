<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 rounded-lg shadow-md">
    <div>
        <h1 class="text-xl font-semibold mb-4">{{ __('Horario Docente') }}</h1>
        
        <!-- Selector de Tipo de Horario -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tipo de Horario Activo
            </label>
            <div class="flex flex-wrap gap-2">
                @foreach($tiposHorario as $tipo => $config)
                    <button 
                        wire:click.prevent="cambiarTipoHorario('{{ $tipo }}')" 
                        class="flex items-center gap-2 py-2 px-4 rounded-lg border transition-all duration-200
                        {{ $tipoHorarioActivo == $tipo 
                            ? 'bg-' . $config['color'] . '-500 text-white border-' . $config['color'] . '-500' 
                            : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' 
                        }}"
                    >
                        <span class="text-lg">{{ $config['icono'] }}</span>
                        <span class="font-medium">{{ $config['nombre'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
            
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

        <!-- Rango de semana -->
        @isset($inicioSemanaLaboralFormatted, $finSemanaLaboralFormatted)
        <div class="flex items-center mb-4 text-gray-600 dark:text-gray-400">
            <span>{{ $inicioSemanaLaboralFormatted }} - {{ $finSemanaLaboralFormatted }}</span>
            <i class="far fa-calendar-alt ml-2"></i>
        </div>
        @endisset      
    
        <!-- Panel de Control del Tipo de Horario -->
        <div class="mb-6 p-4 rounded-lg bg-{{ $tiposHorario[$tipoHorarioActivo]['color'] }}-50 dark:bg-{{ $tiposHorario[$tipoHorarioActivo]['color'] }}-900/20 border border-{{ $tiposHorario[$tipoHorarioActivo]['color'] }}-200 dark:border-{{ $tiposHorario[$tipoHorarioActivo]['color'] }}-800">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $tiposHorario[$tipoHorarioActivo]['icono'] }}</span>
                    <div>
                        <h3 class="font-semibold text-{{ $tiposHorario[$tipoHorarioActivo]['color'] }}-800 dark:text-{{ $tiposHorario[$tipoHorarioActivo]['color'] }}-300 text-lg">
                            {{ $tiposHorario[$tipoHorarioActivo]['nombre'] }}
                        </h3>
                        <p class="text-sm text-{{ $tiposHorario[$tipoHorarioActivo]['color'] }}-600 dark:text-{{ $tiposHorario[$tipoHorarioActivo]['color'] }}-400">
                            {{ $horarios->count() }} horas para {{ $diaSeleccionado }}
                        </p>
                    </div>
                </div>
                
                <!-- Botones de Control Masivo -->
                <div class="flex flex-wrap gap-2">
                    @if($hayHorariosInactivos)
                    <button 
                        wire:click="activarTodosHorarios"
                        class="flex items-center gap-2 py-2 px-4 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="font-medium">Activar Todos</span>
                    </button>
                    @endif
                    
                    @if($hayHorariosActivos)
                    <button 
                        wire:click="desactivarTodosHorarios"
                        class="flex items-center gap-2 py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="font-medium">Desactivar Todos</span>
                    </button>
                    @endif
                    
                    <!-- Estadísticas rápidas -->
                    <div class="flex items-center gap-4 text-sm">
                        <span class="text-green-600 dark:text-green-400">
                            ✅ {{ $estadisticasHorarios['activos'] ?? 0 }} activos
                        </span>
                        <span class="text-red-600 dark:text-red-400">
                            ❌ {{ $estadisticasHorarios['inactivos'] ?? 0 }} inactivos
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de horarios -->
        @if ($horarios->count() > 0)
            @foreach ($horarios as $horario)
            <div class="flex flex-wrap items-center justify-between p-4 rounded-lg shadow-md mb-4 border-l-4 
                {{ $horario->is_active 
                    ? 'border-green-500 bg-white dark:bg-gray-800' 
                    : 'border-red-500 bg-gray-100 dark:bg-gray-700 opacity-75' 
                }}">
                <div class="flex flex-wrap items-center gap-2 sm:gap-4 w-full">
                    <!-- Estado del horario -->
                    <div class="flex items-center">
                        <button 
                            wire:click="toggleHorario({{ $horario->id }})"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                {{ $horario->is_active 
                                    ? 'bg-green-500' 
                                    : 'bg-gray-300 dark:bg-gray-600' 
                                }}"
                        >
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                {{ $horario->is_active ? 'translate-x-6' : 'translate-x-1' }}"
                            ></span>
                        </button>
                        <span class="ml-2 text-sm {{ $horario->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $horario->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    
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
                    </div>
                </div>
            
                <div class="flex flex-wrap items-center gap-2 sm:gap-4 w-full mt-2">
                    <!-- Información del grado -->
                    <div class="text-gray-600 dark:text-gray-400 text-sm flex-grow">
                        {{ __('Jornada: ') . ($horario->grade->nivel->shift->shift_name ?? 'N/A') . ' | Nivel: ' . ($horario->grade->nivel->nivel_name ?? 'N/A') . ' | Grado: ' . $horario->grade->grade_name . ' / ' . $horario->grade->section }}
                    </div>
                    
                    <!-- Información del trimestre si aplica -->
                    @if($horario->trimester_id)
                    <div class="text-sm bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-2 py-1 rounded">
                        Trimestre: {{ $horario->trimester->trimester_name ?? 'N/A' }}
                    </div>
                    @else
                    <div class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded">
                        Global (Todos los trimestres)
                    </div>
                    @endif
                </div>               
            </div>
            @endforeach
        @else
            <div class="flex flex-wrap items-center justify-between bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-md">
                <div class="flex items-center gap-4">          
                    <div class="text-gray-900 dark:text-white text-base font-semibold">
                        {{ __('No hay horas clase para el día y tipo seleccionado') }}
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

    <!-- Distributivo por tipo de horario -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
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
            Distributivo - {{ $tiposHorario[$tipoHorarioActivo]['nombre'] }}
            <span class="block text-base font-normal text-gray-600 dark:text-gray-400 mt-1">
                Total: {{ $horasTotales }} horas clase
                ({{ $totalHoras40 }} h en BT - 40 min | {{ $totalHoras45 }} h en EGB/BGU - 45 min)
            </span>
        </h1>

        <div class="overflow-x-auto rounded-lg shadow ring-1 ring-black ring-opacity-5">
            <table class="min-w-full border-separate border-spacing-y-2 text-left">
                <thead>
                    <tr class="text-xs border-b uppercase font-semibold tracking-wider">
                        <th class="px-4 py-3">Asignatura</th>
                        <th class="px-4 py-3">Curso</th>
                        <th class="px-4 py-3">Nivel</th>
                        <th class="px-4 py-3">Estado</th>
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
                                $activo = $primerHorario->is_active;
                            @endphp
                           
                            <tr class="hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors duration-200 border-b">
                                <td class="px-4 py-3">{{ $asignatura }}</td>
                                <td class="px-4 py-3">{{ $curso }}</td>
                                <td class="px-4 py-3">{{ $primerHorario->grade->nivel->nivel_name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs 
                                        {{ $activo 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' 
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' 
                                        }}">
                                        {{ $activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $bloques }} bloques de {{ $bloque }} min</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', function () {
        @this.on('swal',(event)=>{
            const data=event
            Swal.fire({
                icon:data[0]['icon'],
                title:data[0]['title'],
                text:data[0]['text'],
            })
        });

        @this.on('horarioActivado', (id) => {
            Swal.fire({
                icon: 'success',
                title: 'Hora Activada',
                text: 'La ha sido activado correctamente',
                timer: 2000,
                showConfirmButton: false
            });
        });

        @this.on('horarioDesactivado', (id) => {
            Swal.fire({
                icon: 'info',
                title: 'Horar Desactivada',
                text: 'La Hora ha sido desactivado',
                timer: 2000,
                showConfirmButton: false
            });
        });

        @this.on('todosHorariosActivados', (cantidad) => {
            Swal.fire({
                icon: 'success',
                title: 'Horas Activadas',
                text: cantidad + ' horas han sido activadas correctamente',
                timer: 3000,
                showConfirmButton: false
            });
        });

        @this.on('todosHorariosDesactivados', (cantidad) => {
            Swal.fire({
                icon: 'info',
                title: 'Horas Desactivadas',
                text: cantidad + ' horas han sido desactivadas',
                timer: 3000,
                showConfirmButton: false
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