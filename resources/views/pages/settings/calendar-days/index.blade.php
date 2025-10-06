<x-layouts.app :title="__('Calendario Académico')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Calendario Académico</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <!-- Header y controles -->
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                        Calendario Académico
                    </h2>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">
                        {{ now()->translatedFormat('F Y') }}
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    @can('crear-calendarday')
                        <a href="{{ route('settings.calendar-days.create') }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i> Nuevo Día
                        </a>
                        
                        <a href="{{ route('settings.calendar-days.import-form') }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-file-import mr-2"></i> Importar
                        </a>
                    @endcan
                </div>
            </div>
            
            <!-- Filtros -->
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Año Académico -->
                <div>
                    <label for="year_id" class="block mb-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Año Académico
                    </label>
                    <select id="year_id" name="year_id" 
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-neutral-900 shadow-sm transition-all duration-200
                            focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:ring-opacity-50 focus:outline-none
                            dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-100 dark:placeholder-neutral-400
                            dark:focus:border-primary-500 dark:focus:ring-primary-800
                            hover:border-neutral-400 dark:hover:border-neutral-500">
                        <option value="" class="bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100">
                            Todos los años
                        </option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" 
                                {{ $selectedYear == $year->id ? 'selected' : '' }}
                                class="bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100">
                                {{ $year->year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Trimestre -->
                <div>
                    <label for="trimester_id" class="block mb-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Trimestre
                    </label>
                    <select id="trimester_id" name="trimester_id"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-neutral-900 shadow-sm transition-all duration-200
                            focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:ring-opacity-50 focus:outline-none
                            dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-100 dark:placeholder-neutral-400
                            dark:focus:border-primary-500 dark:focus:ring-primary-800
                            hover:border-neutral-400 dark:hover:border-neutral-500">
                        <option value="" class="bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100">
                            Todos los trimestres
                        </option>
                        @foreach($trimesters as $trimester)
                            <option value="{{ $trimester->id }}" 
                                {{ $selectedTrimester == $trimester->id ? 'selected' : '' }}
                                class="bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100">
                                {{ $trimester->trimester_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mes -->
                <div>
                    <label for="month" class="block mb-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Mes
                    </label>
                    <select id="month" name="month"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-neutral-900 shadow-sm transition-all duration-200
                            focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:ring-opacity-50 focus:outline-none
                            dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-100 dark:placeholder-neutral-400
                            dark:focus:border-primary-500 dark:focus:ring-primary-800
                            hover:border-neutral-400 dark:hover:border-neutral-500">
                        @foreach(range(1, 12) as $month)
                            @php
                                $date = Carbon\Carbon::create(null, $month, 1);
                            @endphp
                            <option value="{{ $month }}" 
                                {{ $selectedMonth == $month ? 'selected' : '' }}
                                class="bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100">
                                {{ $date->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Botón Filtrar -->
                <div class="flex items-end">                    
                    <button type="submit"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all duration-200
                            hover:bg-primary-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                            active:bg-primary-800 active:scale-95
                            dark:bg-primary-700 dark:hover:bg-primary-600 dark:focus:ring-offset-neutral-800">
                        <i class="fas fa-funnel text-xs"></i>
                        <span>Filtrar</span>
                    </button>
                </div>
            </form>

            <!-- Leyenda de Trimestres -->
            <div class="flex flex-wrap gap-4 p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-indigo-500"></div>
                    <span class="text-sm text-neutral-700 dark:text-neutral-300">Primer Trimestre</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-purple-500"></div>
                    <span class="text-sm text-neutral-700 dark:text-neutral-300">Segundo Trimestre</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-pink-500"></div>
                    <span class="text-sm text-neutral-700 dark:text-neutral-300">Tercer Trimestre</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-red-500"></div>
                    <span class="text-sm text-neutral-700 dark:text-neutral-300">Feriados</span>
                </div>
            </div>
            
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Calendario Principal -->
                <div class="w-full lg:w-2/3">
                    <div class="calendar-container bg-white dark:bg-neutral-800 rounded-lg shadow">
                        <!-- Header del Calendario -->
                        <div class="calendar-header bg-white dark:bg-neutral-800 p-4 border-b border-neutral-200 dark:border-neutral-700">
                            <div class="flex justify-between items-center mb-4">
                                <button onclick="changeMonth(-1)" class="p-2 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                
                                <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200" id="current-month">
                                    {{ $currentMonth->translatedFormat('F Y') }}
                                </h3>
                                
                                <button onclick="changeMonth(1)" class="p-2 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            
                            <!-- Días de la semana -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $day)
                                    <div class="text-center text-sm font-medium text-neutral-600 dark:text-neutral-400 py-2">
                                        {{ $day }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                       
                        <!-- Grid del Calendario -->
                        <div class="calendar-grid p-4">
                            <div class="grid grid-cols-7 gap-1">
                                @php
                                    $firstDay = $currentMonth->copy()->startOfMonth();
                                    $startDay = $firstDay->dayOfWeekIso - 1; // Lunes = 0
                                    $daysInMonth = $firstDay->daysInMonth;
                                    $currentDay = $firstDay->copy()->subDays($startDay);
                                @endphp

                                @for($i = 0; $i < 42; $i++) <!-- 6 semanas -->
                                    @php
                                        $isCurrentMonth = $currentDay->month == $currentMonth->month;
                                        $dateStr = $currentDay->format('Y-m-d');
                                        $dayEvents = $calendarDays->where('date', $dateStr);
                                        $isToday = $dateStr == now()->format('Y-m-d');
                                        
                                        // Obtener el color del trimestre
                                        $trimestreColor = '#f8fafc'; // Color por defecto
                                        $trimestreClass = '';
                                        
                                        if ($dayEvents->count() > 0) {
                                            $dayEvent = $dayEvents->first();
                                            $isHoliday = $dayEvent->activity && stripos(strtolower($dayEvent->activity), 'feriado') !== false;
                                            $trimestreColor = App\Http\Controllers\Settings\Calendar\CalendarDayController::getEventColorStatic(
                                                $dayEvent->period, 
                                                $isHoliday
                                            );
                                            
                                            // Clase CSS basada en el trimestre
                                            $periodUpper = strtoupper(trim($dayEvent->period));
                                            if (str_contains($periodUpper, 'PRIMER')) {
                                                $trimestreClass = 'trimestre-primero';
                                            } elseif (str_contains($periodUpper, 'SEGUNDO')) {
                                                $trimestreClass = 'trimestre-segundo';
                                            } elseif (str_contains($periodUpper, 'TERCER')) {
                                                $trimestreClass = 'trimestre-tercero';
                                            }
                                        }
                                    @endphp

                                    <div class="calendar-day min-h-24 p-2 border border-neutral-200 dark:border-neutral-700 
                                        {{ !$isCurrentMonth ? 'bg-neutral-50 dark:bg-neutral-900 text-neutral-400' : 'bg-white dark:bg-neutral-800' }}
                                        {{ $isToday ? 'ring-2 ring-blue-500' : '' }}
                                        {{ $trimestreClass }}
                                        relative group"
                                        data-date="{{ $dateStr }}"
                                        @if($dayEvents->count() > 0) style="border-left: 4px solid {{ $trimestreColor }}" @endif>
                                        
                                        <!-- Número del día -->
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="text-sm font-medium {{ $isToday ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                                {{ $currentDay->day }}
                                            </span>
                                            @if($dayEvents->count() > 0)
                                                <span class="text-xs px-1 rounded bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300">
                                                    {{ $dayEvents->first()->period }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Eventos del día -->
                                        <div class="calendar-events space-y-1 max-h-20 overflow-y-auto">
                                            @foreach($dayEvents as $event)
                                                @php
                                                    $isHolidayEvent = $event->activity && stripos(strtolower($event->activity), 'feriado') !== false;
                                                    $eventColor = App\Http\Controllers\Settings\Calendar\CalendarDayController::getEventColorStatic(
                                                        $event->period, 
                                                        $isHolidayEvent
                                                    );
                                                @endphp
                                                <div class="event-item text-xs p-1 rounded cursor-pointer hover:opacity-80 transition-opacity"
                                                    style="background-color: {{ $eventColor }}; color: white;"
                                                    onclick="window.location='{{ route('settings.calendar-days.edit', $event) }}'"
                                                    title="{{ $event->activity }}">
                                                    {{ Str::limit($event->activity, 20) }}
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Tooltip para más información -->
                                        @if($dayEvents->count() > 0)
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all rounded z-10"
                                                data-tippy-content="
                                                    <div class='text-sm'>
                                                        <strong>{{ $currentDay->translatedFormat('l, d F Y') }}</strong><br>
                                                        @foreach($dayEvents as $event)
                                                            <div class='mt-1'>
                                                                <strong>Trimestre:</strong> {{ $event->period }}<br>
                                                                <strong>Actividad:</strong> {{ $event->activity }}<br>
                                                                <strong>Semana:</strong> {{ $event->week }}<br>
                                                                <strong>Día:</strong> {{ $event->day_number }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                ">
                                            </div>
                                        @endif
                                    </div>

                                    @php
                                        $currentDay->addDay();
                                    @endphp
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Panel Lateral - Eventos del Mes -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow border border-neutral-200 dark:border-neutral-700">
                        <!-- Header del Panel -->
                        <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                            <h3 class="font-semibold text-lg text-neutral-800 dark:text-neutral-200">
                                Eventos de {{ $currentMonth->translatedFormat('F') }}
                            </h3>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                {{ $monthEvents->count() }} eventos programados
                            </p>
                        </div>

                        <!-- Lista de Eventos -->
                        <div class="p-4 max-h-96 overflow-y-auto">
                            @if($monthEvents->count() > 0)
                                <div class="space-y-3">
                                    @foreach($monthEvents as $event)
                                        @php
                                            $isHoliday = $event->activity && stripos(strtolower($event->activity), 'feriado') !== false;
                                            $eventColor = App\Http\Controllers\Settings\Calendar\CalendarDayController::getEventColorStatic(
                                                $event->period, 
                                                $isHoliday
                                            );
                                        @endphp
                                        
                                        <a href="{{ route('settings.calendar-days.edit', $event) }}" 
                                           class="block p-3 rounded-lg border-l-4 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors group"
                                           style="border-left-color: {{ $eventColor }}">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                                            {{ $event->date->format('d') }}
                                                        </div>
                                                        <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                                            {{ $event->date->translatedFormat('l') }}
                                                        </div>
                                                    </div>
                                                    <div class="font-semibold text-neutral-800 dark:text-neutral-200 group-hover:text-primary-600 dark:group-hover:text-primary-400">
                                                        {{ $event->activity ?? 'Día lectivo' }}
                                                    </div>
                                                    <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                                        {{ $event->period }} • Semana {{ $event->week }} • Día {{ $event->day_number }}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-neutral-500 dark:text-neutral-400">
                                    <i class="fas fa-calendar-times text-3xl mb-3"></i>
                                    <p>No hay eventos programados para este mes</p>
                                </div>
                            @endif
                        </div>

                        <!-- Eventos Próximos -->
                        <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
                            <h4 class="font-semibold text-md mb-3 text-neutral-800 dark:text-neutral-200">
                                Próximos Eventos
                            </h4>
                            <div class="space-y-2">
                                @foreach($upcomingEvents as $event)
                                    <a href="{{ route('settings.calendar-days.edit', $event) }}" 
                                       class="flex items-center gap-3 p-2 rounded hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                                        <div class="w-2 h-8 rounded-full" 
                                             style="background-color: {{ App\Http\Controllers\Settings\Calendar\CalendarDayController::getEventColorStatic($event->period, stripos(strtolower($event->activity), 'feriado') !== false) }}"></div>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                                {{ $event->date->format('M d') }}
                                            </div>
                                            <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ Str::limit($event->activity, 30) }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        // Inicializar tooltips
        tippy('[data-tippy-content]', {
            allowHTML: true,
            placement: 'top',
            interactive: true,
        });

        // Navegación entre meses
        function changeMonth(direction) {
            const currentMonth = document.getElementById('current-month').textContent;
            const date = new Date(currentMonth + ' 1');
            date.setMonth(date.getMonth() + direction);
            
            const year = date.getFullYear();
            const month = date.getMonth() + 1;
            
            // Recargar la página con el nuevo mes
            const url = new URL(window.location.href);
            url.searchParams.set('month', month);
            url.searchParams.set('year', year);
            window.location.href = url.toString();
        }

        // Auto-refresh cada 30 segundos para mantener los datos actualizados
        setTimeout(() => {
            window.location.reload();
        }, 30000);
    </script>
    @endpush

    @push('styles')
    <style>
        /* Estilos para los trimestres */
.trimestre-primero {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(99, 102, 241, 0.1) 100%) !important;
}

.trimestre-segundo {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.05) 0%, rgba(139, 92, 246, 0.1) 100%) !important;
}

.trimestre-tercero {
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.05) 0%, rgba(236, 72, 153, 0.1) 100%) !important;
}

/* Estilos para modo oscuro */
.dark .trimestre-primero {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0.15) 100%) !important;
}

.dark .trimestre-segundo {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(139, 92, 246, 0.15) 100%) !important;
}

.dark .trimestre-tercero {
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.1) 0%, rgba(236, 72, 153, 0.15) 100%) !important;
}

/* Colores de borde más pronunciados */
.calendar-day[style*="border-left"] {
    border-left-width: 6px !important;
}

/* Efecto hover mejorado */
.calendar-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    z-index: 20;
}

/* Estilos específicos para días con eventos */
.calendar-day.has-events {
    border-bottom: 2px solid;
    border-bottom-color: inherit;
}
        .calendar-day {
            transition: all 0.2s ease;
        }

        .calendar-day:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .event-item {
            font-size: 0.7rem;
            line-height: 1.2;
            word-break: break-word;
        }

        .calendar-events::-webkit-scrollbar {
            width: 3px;
        }

        .calendar-events::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .calendar-events::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .calendar-events::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Estilos para modo oscuro */
        .dark .calendar-events::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .calendar-events::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        .dark .calendar-events::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
    @endpush
</x-layouts.app>