<x-layouts.app :title="isset($calendarDay) ? __('Editar Día Calendario') : __('Crear Día Calendario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.calendar-days.index') }}">Días Calendario</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($calendarDay) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($calendarDay) ? 'Editar Día Calendario' : 'Crear Nuevo Día Calendario' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($calendarDay) ? route('settings.calendar-days.update', $calendarDay) : route('settings.calendar-days.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($calendarDay))
                    @method('PUT')
                @endif

                <!-- Año -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="year_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Año <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="year_id" name="year_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un Año</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ old('year_id', $calendarDay->year_id ?? '') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('year_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Trimestre -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="trimester_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Trimestre <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="trimester_id" name="trimester_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un Trimestre</option>
                            @foreach($trimesters as $trimester)
                                <option value="{{ $trimester->id }}" {{ old('trimester_id', $calendarDay->trimester_id ?? '') == $trimester->id ? 'selected' : '' }}>
                                    {{ $trimester->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('trimester_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Periodo -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="period" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Periodo <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="period" name="period" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un Periodo</option>
                            <option value="PRIMERO" {{ old('period', $calendarDay->period ?? '') == 'PRIMERO' ? 'selected' : '' }}>PRIMERO</option>
                            <option value="SEGUNDO" {{ old('period', $calendarDay->period ?? '') == 'SEGUNDO' ? 'selected' : '' }}>SEGUNDO</option>
                            <option value="TERCERO" {{ old('period', $calendarDay->period ?? '') == 'TERCERO' ? 'selected' : '' }}>TERCERO</option>
                        </select>
                        @error('period')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fecha -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Fecha <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                       <input id="date" type="date" name="date"
                            value="{{ old('date', isset($calendarDay) ? $calendarDay->date->format('Y-m-d') : '') }}" required
                            class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                </div>

                <!-- Nombre del Mes -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="month_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre del Mes <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="month_name" type="text" name="month_name" 
                               value="{{ old('month_name', $calendarDay->month_name ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('month_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nombre del Día -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="day_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre del Día <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="day_name" type="text" name="day_name" 
                               value="{{ old('day_name', $calendarDay->day_name ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('day_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Semana -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="week" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Semana <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="week" type="number" name="week" 
                               value="{{ old('week', $calendarDay->week ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('week')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Número de Día -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="day_number" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Número de Día <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="day_number" type="number" name="day_number" 
                               value="{{ old('day_number', $calendarDay->day_number ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('day_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actividad -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="activity" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Actividad
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <textarea id="activity" name="activity" rows="3"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">{{ old('activity', $calendarDay->activity ?? '') }}</textarea>
                        @error('activity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones con Flux -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('settings.calendar-days.index') }}"
                        variant="outline"
                    >
                        {{ __('Volver al Calendario') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="{{ isset($calendarDay) ? 'check-circle' : 'plus' }}" 
                        color="primary"
                    >
                        {{ isset($calendarDay) ? __('Actualizar Día') : __('Crear Día') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>