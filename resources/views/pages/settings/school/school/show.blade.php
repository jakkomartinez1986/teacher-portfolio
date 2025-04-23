<x-layouts.app :title="__('User')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.schools.index') }}">Colegio</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Ver Colegio</flux:breadcrumbs.item>
        </flux:breadcrumbs>       
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
         
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <!-- Header con logo y nombre -->
                            <div class="flex flex-col lg:flex-row items-center gap-6 mb-8">
                                <div class="shrink-0">
                                    <img class="h-24 w-24 rounded-full object-cover" 
                                         src="{{ $school->defaultSchoolPhotoUrl() }}" 
                                         alt="{{ $school->name_school }} logo">
                                </div>
                                <div class="text-center lg:text-left">
                                    <h1 class="text-2xl font-bold">{{ $school->name_school }}</h1>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $school->distrit }}</p>
                                </div>
                            </div>
            
                            <!-- Información principal en grid responsive -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <!-- Columna 1 -->
                                <div class="space-y-4">
                                    <div>
                                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Ubicación</h2>
                                        <p class="mt-1">{{ $school->location }}</p>
                                    </div>
                                    
                                    <div>
                                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dirección</h2>
                                        <p class="mt-1">{{ $school->address }}</p>
                                    </div>
                                    
                                    <div>
                                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono</h2>
                                        <p class="mt-1">{{ $school->phone }}</p>
                                    </div>
                                </div>
                                
                                <!-- Columna 2 -->
                                <div class="space-y-4">
                                    <div>
                                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h2>
                                        <p class="mt-1">
                                            <a href="mailto:{{ $school->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $school->email }}
                                            </a>
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Sitio Web</h2>
                                        <p class="mt-1">
                                            @if($school->website)
                                                <a href="{{ $school->website }}" target="_blank" 
                                                   class="text-blue-600 dark:text-blue-400 hover:underline">
                                                    {{ $school->website }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">No especificado</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
            
                            <!-- Sección de usuarios (profesores/estudiantes) -->
                            <div class="mt-10">
                                <h2 class="text-lg font-medium mb-6 text-primary-600 dark:text-primary-400">Estadísticas</h2>
                                
                                <!-- Tres columnas principales -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                                    <!-- Columna de Estudiantes -->
                                    <div class="bg-card dark:bg-card-dark p-6 rounded-lg shadow border border-border dark:border-border-dark">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-medium text-heading dark:text-heading-dark">Estudiantes</h3>
                                            <span class="text-2xl font-bold text-accent dark:text-accent-dark">
                                                3200
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-3 text-muted dark:text-muted-dark">
                                            <div class="flex justify-between">
                                                <span>Hombres:</span>
                                                <span class="font-medium text-heading dark:text-heading-dark">1200</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Mujeres:</span>
                                                <span class="font-medium text-heading dark:text-heading-dark">2000</span>
                                            </div>
                                            <div class="pt-2 mt-2 border-t border-border dark:border-border-dark">
                                                <div class="flex justify-between">
                                                    <span>Otros:</span>
                                                    <span class="font-medium text-heading dark:text-heading-dark">0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Columna de Docentes -->
                                    <div class="bg-card dark:bg-card-dark p-6 rounded-lg shadow border border-border dark:border-border-dark">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-medium text-heading dark:text-heading-dark">Docentes</h3>
                                            <span class="text-2xl font-bold text-accent dark:text-accent-dark">
                                                190
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-3 text-muted dark:text-muted-dark">
                                            <div class="flex justify-between">
                                                <span>Matutina:</span>
                                                <span class="font-medium text-heading dark:text-heading-dark">83</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Vespertina:</span>
                                                <span class="font-medium text-heading dark:text-heading-dark">50</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Compartida:</span>
                                                <span class="font-medium text-heading dark:text-heading-dark">7</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Intensiva:</span>
                                                <span class="font-medium text-heading dark:text-heading-dark">12</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Columna de Administrativos -->
                                    <div class="bg-card dark:bg-card-dark p-6 rounded-lg shadow border border-border dark:border-border-dark">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-medium text-heading dark:text-heading-dark">Administrativos</h3>
                                            <span class="text-2xl font-bold text-accent dark:text-accent-dark">
                                                4
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-3 text-muted dark:text-muted-dark">
                                            <div class="flex justify-between">
                                                <span>Directivos:</span>
                                                <span class="font-medium text-heading dark:text-heading-dark">2</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Secretaría:</span>
                                                <span class="font-medium text-heading dark:text-heading-dark">1</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Otros:</span>
                                                <span class="font-medium text-heading dark:text-heading-dark">1</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Fila del Total General con espaciado mejorado -->
                                <div class="mt-8">
                                    <div class="bg-card dark:bg-card-dark p-6 rounded-lg shadow border border-border dark:border-border-dark">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div>
                                                <h3 class="text-lg font-medium text-heading dark:text-heading-dark">Total de Usuarios</h3>
                                                <p class="text-sm text-muted dark:text-muted-dark">Resumen general de toda la comunidad educativa</p>
                                            </div>
                                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                                <span class="text-3xl font-bold text-accent dark:text-accent-dark">
                                                    {{ $school->users->count() }}
                                                </span>
                                                <div class="space-y-2">
                                                    <div class="flex items-center">
                                                        <span class="h-3 w-3 rounded-full bg-[--color-primary] mr-2"></span>
                                                        <span class="text-sm text-muted dark:text-muted-dark">Estudiantes: 89%</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="h-3 w-3 rounded-full bg-[--color-secondary] mr-2"></span>
                                                        <span class="text-sm text-muted dark:text-muted-dark">Docentes: 12%</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="h-3 w-3 rounded-full bg-[--color-tertiary] mr-2"></span>
                                                        <span class="text-sm text-muted dark:text-muted-dark">Administrativos: 1%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Botones de acción -->                           
                            <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                                <flux:button icon="arrow-uturn-left" href="{{ route('settings.schools.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                                    @can('editar-school')
                                        <flux:button icon="pencil" href="{{ route('settings.schools.edit', $school->id) }}" > {{ __(' Editar Escuela') }}</flux:button>
                                    @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</x-layouts.app>