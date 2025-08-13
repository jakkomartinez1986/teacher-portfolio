<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Encabezado con filtros -->
    <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
        <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
            Docente: Dashboard de Asistencias y Novedades
        </h2>
        
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                <p>{{ session('error') }}</p>
            </div>
        @endif

       
        <!-- Filtros de fecha -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Rango de fechas
                </label>
            </div>
            <div class="md:col-span-9 flex items-center gap-2">
                <input type="date" wire:model="startDate" wire:change="loadData" 
                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                <span class="text-neutral-500 dark:text-neutral-400">a</span>
                <input type="date" wire:model="endDate" wire:change="loadData" 
                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
            </div>
        </div>
    </div>

    @if(count($grades) > 0)
        <!-- Resumen Estadístico -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="rounded-lg border border-neutral-200 bg-blue-50 p-4 dark:border-neutral-700 dark:bg-blue-900/30">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Días Registrados</h3>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-100">{{ $summary['total_days'] }}</p>
            </div>
            
            <div class="rounded-lg border border-neutral-200 bg-green-50 p-4 dark:border-neutral-700 dark:bg-green-900/30">
                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Novedades</h3>
                <p class="text-2xl font-bold text-green-600 dark:text-green-100">{{ $summary['attendance_rate'] }}%</p>
            </div>
            
            <div class="rounded-lg border border-neutral-200 bg-yellow-50 p-4 dark:border-neutral-700 dark:bg-yellow-900/30">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Atrasos</h3>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-100">{{ $summary['status_counts']['A'] }}</p>
            </div>
            
            <div class="rounded-lg border border-neutral-200 bg-red-50 p-4 dark:border-neutral-700 dark:bg-red-900/30">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Faltas Totales</h3>
                <p class="text-2xl font-bold text-red-600 dark:text-red-100">{{ $summary['status_counts']['I'] + $summary['status_counts']['J'] }}</p>
            </div>
        </div>

        <!-- Detalle de Asistencias -->
        @if(count($attendanceData) > 0)
            @foreach($attendanceData as $date => $dateData)
                <div x-data="{ open: false }" class="border border-neutral-200 rounded-lg overflow-hidden dark:border-neutral-700">
                    <!-- Encabezado del colapsable -->
                    <button @click="open = !open"
                        class="w-full text-left px-4 py-3 bg-neutral-50 hover:bg-neutral-100 dark:bg-neutral-700 dark:hover:bg-neutral-600 flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-neutral-800 dark:text-neutral-100">
                                {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                            </h3>
                            <span class="text-sm text-neutral-600 dark:text-neutral-300 italic">
                                {{ count($dateData['classes']) }} {{ Str::plural('clase', count($dateData['classes'])) }} registrada(s)
                            </span>
                        </div>
                        <svg x-show="!open" class="h-4 w-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <svg x-show="open" class="h-4 w-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>

                    <!-- Contenido expandible -->
                    <div x-show="open" x-transition class="p-4 border-t border-neutral-200 space-y-6 dark:border-neutral-700">
                        @foreach($dateData['classes'] as $class)
                            <div class="border-b border-neutral-200 pb-4 last:border-b-0 dark:border-neutral-700">
                                <div class="mb-3">
                                    <h4 class="font-medium text-neutral-800 dark:text-neutral-100">
                                        {{ $class['class'] ?? 'Clase sin asignatura' }}
                                    </h4>
                                    @if($class['grade'])
                                        <p class="text-sm text-neutral-600 dark:text-neutral-300"><strong>Grado:</strong> {{ $class['grade'] }}</p>
                                    @endif    
                                    @if($class['tutor'])
                                        <p class="text-sm text-neutral-600 dark:text-neutral-300"><strong>Tutor:</strong> {{ $class['tutor'] }}</p>
                                    @endif                                 
                                    @if($class['classtopic'])
                                        <p class="text-sm text-neutral-600 dark:text-neutral-300"><strong>Tema:</strong> {{ $class['classtopic'] }}</p>
                                    @endif
                                    @if($class['observation'])
                                        <p class="text-sm text-neutral-600 dark:text-neutral-300"><strong>Observación:</strong> {{ $class['observation'] }}</p>
                                    @endif
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full table-auto text-sm border border-neutral-200 dark:border-neutral-700">
                                        <thead class="bg-neutral-50 dark:bg-neutral-700">
                                            <tr>
                                                <th class="border border-neutral-200 px-3 py-2 text-left text-sm font-medium text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">Estudiante</th>
                                                <th class="border border-neutral-200 px-3 py-2 text-center text-sm font-medium text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">Estado</th>
                                                <th class="border border-neutral-200 px-3 py-2 text-center text-sm font-medium text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">Hora Llegada</th>
                                                <th class="border border-neutral-200 px-3 py-2 text-sm font-medium text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">Justificación / Obs</th>
                                                <th class="border border-neutral-200 px-3 py-2 text-center text-sm font-medium text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($class['attendances'] as $att)
                                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                                    <td class="border border-neutral-200 px-3 py-2 text-neutral-800 dark:border-neutral-700 dark:text-neutral-200">
                                                        {{ $att['student_name'] }}
                                                    </td>
                                                    <td class="border border-neutral-200 px-3 py-2 text-center dark:border-neutral-700">
                                                        <span class="px-2 py-1 rounded text-xs font-medium {{ $att['status_color'] }}">
                                                            {{ $att['status_text'] }}
                                                        </span>
                                                    </td>
                                                    <td class="border border-neutral-200 px-3 py-2 text-center text-neutral-600 dark:border-neutral-700 dark:text-neutral-300">
                                                        {{ $att['arrival_time'] ?? '-' }}
                                                    </td>
                                                    <td class="border border-neutral-200 px-3 py-2 text-sm text-neutral-600 dark:border-neutral-700 dark:text-neutral-300">
                                                        {{ $att['justification'] ?? '-' }}
                                                        @if($att['observation'])
                                                            <br><span class="text-xs italic">{{ $att['observation'] }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="border border-neutral-200 px-3 py-2 text-center dark:border-neutral-700">
                                                        @if($att['status_text'] && $att['phone'])
                                                            @if(!$att['notification_sent_at'])
                                                               @php
                                                                    $teacherName = Auth::user()->full_name ?? 'Docente Asignado';
                                                                    $teacherContact = Auth::user()->contacts ?? 'Número no disponible';
                                                                    $subject = $class['class'] ?? 'Asignatura no especificada';

                                                                    $message = rawurlencode(
                                                                        "🔹 *COMUNICADO OFICIAL - SEGUIMIENTO ACADÉMICO* 🔹\n\n" .
                                                                        "Estimado/a Representante de *{$att['student_name']}*:\n\n" .
                                                                        "📚 *Asignatura:* {$subject}\n" .
                                                                        "👨🏫 *Docente:* {$teacherName}\n" .
                                                                        "📅 *Fecha del reporte:* " . \Carbon\Carbon::parse($date)->format('d/m/Y') . "\n" .
                                                                        "⚠️ *Situación reportada:* {$att['status_text']}\n" .
                                                                        "📝 *Observación:* {$att['observation']}\n\n" .
                                                                        "📌 *Acción requerida:*\n" .
                                                                        "Se solicita su presencia en la institución educativa para mantener una reunión con el docente de la asignatura, a fin de:\n" .
                                                                        "1. Analizar las causas que han originado esta situación.\n" .
                                                                        "2. Establecer estrategias de mejora académica.\n\n" .
                                                                        "📄 *Base legal:*\n" .
                                                                        "Según el *Art. 6 literal g)* de la *Ley Orgánica de Educación Intercultural (LOEI)*, es deber del representante legal: *“Vigilar la asistencia, el progreso académico y la conducta de sus hijos o representados.”*\n\n" .
                                                                        "📅 *Horario de atención:*\n" .
                                                                        "Puede acudir durante el horario establecido para atención a padres de familia o coordinar previamente.\n\n" .
                                                                        "Atentamente,\n" .
                                                                        "*{$teacherName}*\n" .
                                                                        "Docente de *{$subject}*\n" .
                                                                        "📱 Contacto: {$teacherContact}"
                                                                    );

                                                                    $phone = preg_replace('/[^0-9]/', '', $att['phone']);
                                                                    if (str_starts_with($phone, '0')) {
                                                                        $phone = '593' . substr($phone, 1);
                                                                    }

                                                                    $isMobile = preg_match('/Android|iPhone|iPad|iPod/i', request()->userAgent());
                                                                    $waUrl = "https://" . ($isMobile ? 'api' : 'web') . ".whatsapp.com/send?phone={$phone}&text={$message}";
                                                                @endphp


                                                                <div class="flex flex-col items-center gap-2">
                                                                    <a href="{{ $waUrl }}" target="_blank"
                                                                       onclick="window.open(this.href, 'WhatsApp', 'width=600,height=600'); return false;"
                                                                       class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                                        </svg>
                                                                        Enviar Comunicado
                                                                    </a>
                                                                    <button wire:click="markAsSent({{ $att['id'] }})" 
                                                                            class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline transition-colors duration-200">
                                                                        Marcar como enviado
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <span class="text-xs text-green-600 dark:text-green-400">
                                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                    Enviado: {{ \Carbon\Carbon::parse($att['notification_sent_at'])->format('d/m/Y H:i') }}
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="text-xs italic text-neutral-500 dark:text-neutral-400">
                                                                @if(!$att['phone'])
                                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                    </svg>
                                                                    Sin número registrado
                                                                @else
                                                                    Sin novedad
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="rounded-xl border border-neutral-200 bg-white p-6 text-center dark:border-neutral-700 dark:bg-neutral-800">
                <p class="text-neutral-600 dark:text-neutral-400">
                    No hay registros de asistencia para el rango de fechas seleccionado.
                </p>
            </div>
        @endif
    @else
        <div class="rounded-xl border border-neutral-200 bg-white p-6 text-center dark:border-neutral-700 dark:bg-neutral-800">
            <p class="text-neutral-600 dark:text-neutral-400">
                No tienes cursos asignados actualmente.
            </p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Manejar el evento de notificación exitosa
        Livewire.on('notify-success', (message) => {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#10B981",
                stopOnFocus: true,
            }).showToast();
            
            // Recargar los datos después de 1 segundo
            setTimeout(() => {
                Livewire.dispatch('refresh-attendance-data');
            }, 1000);
        });
    });
</script>
@endpush
