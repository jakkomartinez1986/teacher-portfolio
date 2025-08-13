<x-layouts.app :title="__('Calendario Académico')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Calendario Académico</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200  p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <!-- Header y controles -->
            <div class="flex flex-wrap justify-between items-center gap-4">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Calendario Académico
                </h2>
                
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
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="year_id" class="block mb-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Año Académico</label>
                    <select 
                        id="year_id" 
                        name="year_id" 
                        class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-neutral-700 shadow-sm transition-colors
                            focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50
                            dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:focus:border-primary-500 dark:focus:ring-primary-800"
                    >
                        <option value="">Todos los años</option>
                        @foreach($years as $year)
                            <option 
                                value="{{ $year->id }}" 
                                {{ $selectedYear == $year->id ? 'selected' : '' }}
                                class="bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-300"
                            >
                                {{ $year->year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="trimester_id" class="block mb-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Trimestre</label>
                    <select 
                        id="trimester_id" 
                        name="trimester_id" 
                        class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-neutral-700 shadow-sm transition-colors
                            focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50
                            dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:focus:border-primary-500 dark:focus:ring-primary-800"
                    >
                        <option value="">Todos los trimestres</option>
                        @foreach($trimesters as $trimester)
                            <option 
                                value="{{ $trimester->id }}" 
                                {{ $selectedTrimester == $trimester->id ? 'selected' : '' }}
                                class="bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-300"
                            >
                                {{ $trimester->trimester_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">                    
                    <flux:button    type="submit"  icon="funnel" 
                    variant="primary" color="indigo">Filtrar
                    </flux:button>
                </div>
            </form>
            
           <div class="flex flex-col lg:flex-row gap-6">
    <!-- Calendario -->
    <div class="w-full lg:w-2/3">
        <div id="calendar" class="calendar-container">
            <!-- Mes y controles de navegación -->
            <div class="calendar-header">
                <div class="calendar-nav">
                    <button id="prev-month" class="calendar-nav-button">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h3 id="current-month" class="calendar-month-title">
                        {{ now()->translatedFormat('F Y') }}
                    </h3>
                    <button id="next-month" class="calendar-nav-button">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="calendar-days-header">
                    @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $day)
                        <div class="calendar-day-header">{{ $day }}</div>
                    @endforeach
                </div>
            </div>
            
            <!-- Días del calendario -->
            <div class="calendar-grid">
                @php
                    $firstDay = now()->startOfMonth();
                    $startDay = $firstDay->dayOfWeekIso - 1; // Ajuste para que empiece en lunes
                    $daysInMonth = $firstDay->daysInMonth;
                @endphp
                
                @for($i = 0; $i < 6; $i++)
                    <div class="calendar-week">
                        @for($j = 0; $j < 7; $j++)
                            @php
                                $dayNumber = $i * 7 + $j - $startDay + 1;
                                $isCurrentMonth = $dayNumber > 0 && $dayNumber <= $daysInMonth;
                                $currentDate = $isCurrentMonth 
                                    ? $firstDay->copy()->addDays($dayNumber - 1)
                                    : null;
                                $dateStr = $currentDate ? $currentDate->format('Y-m-d') : '';
                                $dayEvents = $events->where('start', $dateStr);
                            @endphp
                            
                            <div class="calendar-day {{ !$isCurrentMonth ? 'empty' : '' }} 
                                {{ $dateStr === $currentDate ? 'today' : '' }}
                                {{ $dayEvents->count() ? 'has-events' : '' }}"
                                data-date="{{ $dateStr }}">
                                
                                @if($isCurrentMonth)
                                    <div class="calendar-day-number">{{ $dayNumber }}</div>
                                    <div class="calendar-events">
                                        @foreach($dayEvents->take(2) as $event)
                                            <div class="calendar-event" 
                                                 style="background-color: {{ $event['color'] }}"
                                                 title="{{ $event['title'] }}">
                                                {{ Str::limit($event['title'], 10) }}
                                            </div>
                                        @endforeach
                                        @if($dayEvents->count() > 2)
                                            <div class="calendar-event-more">
                                                +{{ $dayEvents->count() - 2 }} más
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                    @if($dayNumber >= $daysInMonth) @break @endif
                @endfor
            </div>
        </div>
        
        <!-- Botón para agregar evento -->
        <div class="mt-4 text-center">
            <a href="{{ route('settings.calendar-days.create') }}" 
               class="btn btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Agregar Evento
            </a>
        </div>
    </div>
    
    <!-- Panel de próximos eventos -->
    <div class="w-full lg:w-1/3">
        <div class="bg-neutral-50 dark:bg-neutral-700 rounded-lg p-4 h-full">
            <h3 class="font-semibold text-lg mb-4 dark:text-white">Próximos Eventos</h3>
            
            @if($upcomingEvents->count())
                <div class="space-y-3">
                    @foreach($upcomingEvents as $event)
                        <a href="{{ route('settings.calendar-days.edit', $event) }}" 
                           class="block hover:bg-neutral-100 dark:hover:bg-neutral-600 p-3 rounded-lg transition-colors">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-sm text-neutral-600 dark:text-neutral-300">
                                        {{ $event->date->translatedFormat('D, M d') }}
                                    </div>
                                    <div class="font-semibold dark:text-white">{{ $event->activity }}</div>
                                </div>
                                <div class="w-3 h-3 rounded-full mt-1" 
                                     style="background-color: {{ $this->getEventColor($event->period, str_contains(strtolower($event->activity), 'feriado')) }}"></div>
                            </div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                {{ $event->period }} • Semana {{ $event->week }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-neutral-500 dark:text-neutral-400 italic">
                    No hay eventos próximos en los próximos 7 días
                </div>
            @endif
            
            <!-- Vista rápida de hoy -->
            @php
                $todayEvents = $events->where('start', now()->format('Y-m-d'));
            @endphp
            @if($todayEvents->count())
                <div class="mt-6 pt-4 border-t border-neutral-200 dark:border-neutral-600">
                    <h4 class="font-semibold text-md mb-3 dark:text-white">Eventos de Hoy</h4>
                    <div class="space-y-2">
                        @foreach($todayEvents as $event)
                            <div class="flex items-start gap-2 p-2 bg-neutral-100 dark:bg-neutral-600 rounded">
                                <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0" 
                                     style="background-color: {{ $event['color'] }}"></div>
                                <div>
                                    <div class="font-medium">{{ $event['title'] }}</div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">
                                        {{ $event['extendedProps']['trimester'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
        </div>
    </div>

    @push('scripts')
        <script>
         document.addEventListener('DOMContentLoaded', function() {
    // Navegación del calendario
    document.getElementById('prev-month').addEventListener('click', function() {
        // Lógica para cambiar al mes anterior
        window.location.href = "{{ route('settings.calendar-days.index') }}?month=" + 
            new Date(document.getElementById('current-month').textContent + ' 1')
            .setMonth(new Date(document.getElementById('current-month').textContent + ' 1').getMonth() - 1);
    });

    document.getElementById('next-month').addEventListener('click', function() {
        // Lógica para cambiar al mes siguiente
        window.location.href = "{{ route('settings.calendar-days.index') }}?month=" + 
            new Date(document.getElementById('current-month').textContent + ' 1')
            .setMonth(new Date(document.getElementById('current-month').textContent + ' 1').getMonth() + 1);
    });

    // Click en día para agregar evento
    document.querySelectorAll('.calendar-day:not(.empty)').forEach(day => {
        day.addEventListener('click', function() {
            const date = this.getAttribute('data-date');
            if (date) {
                window.location.href = "{{ route('settings.calendar-days.create') }}?date=" + date;
            }
        });
    });

    // Mostrar tooltips para eventos
    tippy('.calendar-event', {
        content(reference) {
            return reference.getAttribute('title');
        },
    });
});
        </script>
    @endpush

    @push('styles')
        <style>
           /* Estilos para el calendario */
.calendar-container {
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.calendar-header {
    background-color: #f8fafc;
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.calendar-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.calendar-month-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
}

.calendar-nav-button {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.2s;
}

.calendar-nav-button:hover {
    background-color: #e2e8f0;
}

.calendar-days-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-weight: 600;
    color: #64748b;
    padding: 0.5rem 0;
}

.calendar-grid {
    display: grid;
    grid-template-rows: repeat(6, minmax(100px, 1fr));
    background-color: white;
}

.calendar-week {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.calendar-day {
    border: 1px solid #e2e8f0;
    padding: 0.5rem;
    min-height: 100px;
    position: relative;
}

.calendar-day.empty {
    background-color: #f8fafc;
}

.calendar-day.today {
    background-color: #eff6ff;
}

.calendar-day.has-events {
    background-color: #f0fdf4;
}

.calendar-day-number {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.calendar-events {
    max-height: 80px;
    overflow: hidden;
}

.calendar-event {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    margin: 0.1rem 0;
    border-radius: 0.25rem;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
}

.calendar-event-more {
    font-size: 0.65rem;
    color: #64748b;
    text-align: center;
    margin-top: 0.25rem;
}

/* Modo oscuro */
.dark .calendar-container {
    border-color: #334155;
    background-color: #1e293b;
}

.dark .calendar-header {
    background-color: #1e293b;
    border-color: #334155;
}

.dark .calendar-month-title {
    color: #f8fafc;
}

.dark .calendar-nav-button:hover {
    background-color: #334155;
}

.dark .calendar-days-header {
    color: #94a3b8;
}

.dark .calendar-grid {
    background-color: #1e293b;
}

.dark .calendar-day {
    border-color: #334155;
}

.dark .calendar-day.empty {
    background-color: #1e293b;
}

.dark .calendar-day.today {
    background-color: #1e3a8a;
}

.dark .calendar-day.has-events {
    background-color: #14532d;
}

.dark .calendar-event-more {
    color: #94a3b8;
}
        </style>
    @endpush
</x-layouts.app>