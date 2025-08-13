<x-layouts.app :title="__('Importar Días Calendario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.calendar-days.index') }}">Días Calendario</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Importar</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                Importar Días Calendario desde Excel
            </h2>
            @if(session('import_errors'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 dark:bg-red-800 dark:border-red-600 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                @if(session('success_message')) {{ session('success_message') }} @else Errores encontrados durante la importación @endif
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach(session('import_errors') as $error)
                                    <li>
                                        <strong>Fila {{ $error['row'] }}:</strong>
                                        @foreach($error['errors'] as $err)
                                            <div>{{ $err }}</div>
                                        @endforeach
                                        <div class="text-xs mt-1 p-2 bg-gray-100 rounded">
                                            <strong>Datos:</strong>
                                            @if(isset($error['values']['fecha_formateada']))
                                                Fecha: {{ $error['values']['fecha_formateada'] }}<br>
                                            @endif
                                            Periodo: {{ $error['values']['periodo'] ?? 'N/A' }}<br>
                                            Día: {{ $error['values']['dia_nombre'] ?? 'N/A' }}<br>
                                            Semana: {{ $error['values']['semana'] ?? 'N/A' }}
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 dark:bg-red-800 dark:border-red-600 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800 dark:text-red-200">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 dark:bg-yellow-800 dark:border-yellow-600">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                            Descargue la plantilla y complete los datos según el formato requerido. 
                            <a href="{{ route('settings.calendar-days.download-template') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600 dark:text-yellow-300 dark:hover:text-yellow-400">
                                Descargar plantilla
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            
           <form method="POST" action="{{ route('settings.calendar-days.import') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Año Lectivo -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="year_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Año Lectivo <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <select id="year_id" name="year_id" required
                                class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            <option value="">Seleccione un Año Lectivo</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->year_name }}</option>
                            @endforeach
                        </select>
                        @error('year_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Archivo -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="file" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Archivo Excel <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="file" type="file" name="file" accept=".xlsx,.xls" required
                               class="block w-full text-sm text-neutral-900 bg-neutral-100 rounded-lg border border-neutral-300 cursor-pointer focus:outline-none dark:text-neutral-400 dark:bg-neutral-700 dark:border-neutral-600">
                        @error('file')
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
                        {{ __('Cancelar') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="document-arrow-up" 
                        color="primary">
                        {{ __('Importar') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>