{{-- <x-layouts.app :title="isset($classSchedule) ? __('Editar Horario') : __('Crear Horario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('academic.class-schedules.index') }}">Alumnos Tutoria</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Importar Alumnos</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
               {{ $grado }}
            </h2>
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h3>Importar Estudiantes</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('students.students.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="grade_id">Grado *</label>
                                <select name="grade_id" id="grade_id" class="form-control" required>
                                    <option value="">Seleccione un grado</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}">{{ $grade->grade_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="enrollment_date">Fecha de Matrícula *</label>
                                <input type="date" name="enrollment_date" id="enrollment_date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="file">Archivo Excel *</label>
                                <input type="file" name="file" id="file" class="form-control-file" accept=".xlsx,.xls,.csv" required>
                                <small class="form-text text-muted">
                                    <a href="{{ route('students.students.import.template') }}">Descargar plantilla</a>
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Importar
                            </button>
                            <a href="{{ route('students.students.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                        </form>

                        @if(session('import_errors'))
                            <div class="mt-4">
                                <h5>Errores de importación:</h5>
                                <ul class="list-group">
                                    @foreach(session('import_errors') as $error)
                                        <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

          
        </div>
    </div>
</x-layouts.app> --}}

<x-layouts.app :title="__('Importar Estudiantes')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Inicio</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('students.students.index') }}">Estudiantes</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Importar Estudiantes</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="py-8">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="space-y-6">
                        <!-- Encabezado -->
                        <div class="text-center">
                            <h1 class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                Importar Estudiantes desde Archivo de: {{ $nombregrado }}
                            </h1>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Suba un archivo Excel con la información de los estudiantes
                            </p>
                        </div>

                        <!-- Card del formulario -->
                        <div class="shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                            <form action="{{ route('students.students.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                                @csrf

                                <!-- Selección de Grado -->
                                <div>
                                    <label for="grade_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Grado/Curso *
                                    </label>
                                    <select name="grade_id" id="grade_id" required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">Seleccione un grado...</option>
                                        @foreach($grades as $grado)
                                            <option value="{{ $grado->id }}" @selected(old('grade_id') == $grado->id)>
                                                {{ $grado->grade_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Fecha de Matrícula -->
                                <div>
                                    <label for="enrollment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Fecha de Matrícula *
                                    </label>
                                    <input type="date" name="enrollment_date" id="enrollment_date" required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
                                        value="{{ old('enrollment_date', now()->format('Y-m-d')) }}">
                                </div>

                                <!-- Subida de archivo -->
                                <div>
                                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Archivo Excel/CSV *
                                    </label>
                                    <div class="mt-1 flex items-center">
                                        <label for="file" class="cursor-pointer">
                                            <div class="relative">
                                                <input type="file" name="file" id="file" required accept=".xlsx,.xls,.csv"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                                <div class="flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    Seleccionar archivo
                                                </div>
                                            </div>
                                        </label>
                                        <span class="ml-3 text-sm text-gray-500 dark:text-gray-400" id="file-name">
                                            Ningún archivo seleccionado
                                        </span>
                                    </div>
                                  <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold text-blue-600 dark:text-blue-400">Formatos soportados:</span>
                                        <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full dark:bg-green-900 dark:text-green-200 mx-1">.xlsx</span>
                                        <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full dark:bg-green-900 dark:text-green-200 mx-1">.xls</span>
                                        <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full dark:bg-green-900 dark:text-green-200">.csv</span>
                                        <a href="{{ route('students.students.import.template') }}" class="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline ml-2">
                                            Descargar plantilla
                                        </a>
                                    </p>
                                </div>

                                <!-- Errores de validación -->
                                @if($errors->any())
                                    <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                                    Corrija los siguientes errores:
                                                </h3>
                                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        @foreach($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Errores de importación -->
                                @if(session('import_errors'))
                                    <div class="rounded-md bg-yellow-50 dark:bg-yellow-900/20 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                                    Se encontraron {{ count(session('import_errors')) }} errores en la importación
                                                </h3>
                                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                                    <ul class="list-disc pl-5 space-y-1 max-h-60 overflow-y-auto">
                                                        @foreach(session('import_errors') as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Botones de acción -->
                                <div class="flex justify-end space-x-3 pt-4">
                                    <a href="{{ route('students.students.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        Cancelar
                                    </a>
                                    {{-- <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                       
                                    </button> --}}
                                    <flux:button type="submit" icon="cloud-arrow-up"> Importar Estudiantes</flux:button>
                                </div>
                            </form>
                        </div>

                        <!-- Instrucciones -->
                        <div class="bg-primary-50/50 dark:bg-neutral-800 border border-primary-100 dark:border-neutral-700 rounded-lg p-4 transition-colors duration-200">
                            <div class="flex items-start gap-3">
                                <!-- Ícono adaptable -->
                                <svg class="h-5 w-5 flex-shrink-0 text-primary-600 dark:text-primary-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-primary-800 dark:text-primary-200 mb-2">
                                        Instrucciones para la importación:
                                    </h3>
                                    <ul class="text-sm space-y-2 list-disc pl-5 marker:text-primary-500 dark:marker:text-primary-400">
                                        <li class="text-neutral-700 dark:text-neutral-300">
                                            El archivo debe contener las columnas: 
                                            <span class="font-mono bg-primary-100 dark:bg-neutral-700 text-primary-800 dark:text-primary-200 px-1.5 py-0.5 rounded text-xs">nombres</span>, 
                                            <span class="font-mono bg-primary-100 dark:bg-neutral-700 text-primary-800 dark:text-primary-200 px-1.5 py-0.5 rounded text-xs">apellidos</span>,
                                            <span class="font-mono bg-primary-100 dark:bg-neutral-700 text-primary-800 dark:text-primary-200 px-1.5 py-0.5 rounded text-xs">dni</span>
                                           
                                        </li>
                                        <li class="text-neutral-700 dark:text-neutral-300">
                                            Columnas opcionales: 
                                            <span class="font-mono bg-primary-100 dark:bg-neutral-700 text-primary-800 dark:text-primary-200 px-1.5 py-0.5 rounded text-xs">email</span>, 
                                            <span class="font-mono bg-primary-100 dark:bg-neutral-700 text-primary-800 dark:text-primary-200 px-1.5 py-0.5 rounded text-xs">telefono</span>, 
                                            <span class="font-mono bg-primary-100 dark:bg-neutral-700 text-primary-800 dark:text-primary-200 px-1.5 py-0.5 rounded text-xs">celular</span>, 
                                            <span class="font-mono bg-primary-100 dark:bg-neutral-700 text-primary-800 dark:text-primary-200 px-1.5 py-0.5 rounded text-xs">direccion</span>
                                        </li>
                                        <li class="text-neutral-700 dark:text-neutral-300">
                                            Descargue la <a href="{{ route('students.students.import.template') }}" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">plantilla</a> para asegurar el formato correcto
                                        </li>
                                        <li class="text-neutral-700 dark:text-neutral-300">
                                            El sistema generará credenciales automáticamente si no se proporciona email
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para mostrar el nombre del archivo seleccionado -->
    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Ningún archivo seleccionado';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
</x-layouts.app>