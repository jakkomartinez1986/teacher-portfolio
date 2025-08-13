<x-layouts.app :title="__('Detalle del Día Calendario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('calendar-days.index') }}">Días Calendario</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalle</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                Detalle del Día Calendario
            </h2>
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Información del día -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3 font-medium text-neutral-700 dark:text-neutral-300">
                        Fecha:
                    </div>
                    <div class="md:col-span-9">
                        {{ $calendarDay->date->format('d/m/Y') }}
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3 font-medium text-neutral-700 dark:text-neutral-300">
                        Año Lectivo:
                    </div>
                    <div class="md:col-span-9">
                        {{ $calendarDay->year->year_name }}
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3 font-medium text-neutral-700 dark:text-neutral-300">
                        Trimestre:
                    </div>
                    <div class="md:col-span-9">
                        {{ $calendarDay->trimester->name }}
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3 font-medium text-neutral-700 dark:text-neutral-300">
                        Periodo:
                    </div>
                    <div class="md:col-span-9">
                        {{ $calendarDay->period }}
                    </div>
                </div>
                
                @if($calendarDay->activity)
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3 font-medium text-neutral-700 dark:text-neutral-300">
                        Actividad:
                    </div>
                    <div class="md:col-span-9">
                        {{ $calendarDay->activity }}
                    </div>
                </div>
                @endif
                
                <!-- Botón para volver -->
                <div class="flex justify-end mt-6">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('calendar-days.index') }}"
                        color="primary"
                    >
                        Volver al Calendario
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>