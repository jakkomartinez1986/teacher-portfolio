<x-layouts.app :title="__('Listado de Estudiantes')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Inicio</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Estudiantes</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <!-- Header con acciones -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 border-b border-neutral-200 dark:border-neutral-700 gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                        Gestión de Estudiantes
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        {{ $students->total() }} estudiantes registrados
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    <flux:button 
                        href="{{ route('students.students.create') }}" 
                        icon="plus"
                        class="w-full sm:w-auto"
                    >
                        Nuevo Estudiante
                    </flux:button>
                    
                    {{-- <flux:button 
                        href="{{ route('students.students.import.form') }}" 
                        icon="upload"
                        variant="outline"
                        class="w-full sm:w-auto"
                    >
                        Importar
                    </flux:button> --}}
                </div>
            </div>
            
            <div class="p-4">
                <!-- Filtros mejorados -->
                <div class="mb-6 bg-neutral-50 dark:bg-neutral-800 p-4 rounded-lg border border-neutral-200 dark:border-neutral-700">
                    <form action="{{ route('students.students.index') }}" method="GET" class="space-y-4 md:space-y-0">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="search" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Buscar</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                           class="block w-full pl-10 pr-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-neutral-700 dark:text-white" 
                                           placeholder="Nombre, apellido o DNI">
                                </div>
                            </div>
                            
                            <div>
                                <label for="grade_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Grado</label>
                                <select name="grade_id" id="grade_id" class="block w-full pl-3 pr-10 py-2 text-base border border-neutral-300 dark:border-neutral-600 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-neutral-700 dark:text-white">
                                    <option value="">Todos los grados</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->grade_name }} {{ $grade->section }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                <flux:button 
                                    type="submit" 
                                    icon="funnel"
                                    class="w-full md:w-auto"
                                >
                                    Filtrar
                                </flux:button>
                                
                                @if(request('search') || request('grade_id'))
                                <flux:button 
                                    href="{{ route('students.students.index') }}" 
                                    icon="x-mark"
                                    variant="ghost"
                                    class="w-full md:w-auto"
                                >
                                    Limpiar
                                </flux:button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Tarjeta contenedora de la tabla -->
                <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
                    <!-- Tabla responsive -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                        Código
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                        DNI
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                        Grado
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                                @forelse($students as $student)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" 
                                                         src="{{  asset( $student->user->defaultUserPhotoUrl()) }}" 
                                                         alt="{{ $student->user->full_name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                                       {{ $student->user->lastname }} {{ $student->user->name }}
                                                    </div>
                                                    <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                                        {{ $student->user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">
                                            {{ $student->student_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            {{ $student->user->dni }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            {{ $student->currentGrade->grade_name ?? 'N/A' }}
                                            @if($student->currentGrade->section ?? false)
                                                <span class="text-xs text-neutral-400 dark:text-neutral-500">({{ $student->currentGrade->section }})</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    0 => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                    1 => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                    2 => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                    3 => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                                ];
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$student->academic_status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} capitalize">
                                                {{ $student->academic_status_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <flux:button 
                                                    href="{{ route('students.students.show', $student) }}" 
                                                    icon="eye"
                                                    variant="ghost"
                                                    size="sm"
                                                    title="Ver detalles"
                                                />
                                                
                                                <flux:button 
                                                    href="{{ route('students.students.edit', $student) }}" 
                                                    icon="pencil"
                                                    variant="ghost"
                                                    size="sm"
                                                    title="Editar"
                                                />
                                                
                                                <form action="{{ route('students.students.destroy', $student) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <flux:button 
                                                        type="submit"
                                                        icon="trash"
                                                        variant="danger"
                                                        size="sm"
                                                        title="Eliminar"
                                                        onclick="return confirm('¿Estás seguro de eliminar este estudiante?')"
                                                    />
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            No se encontraron estudiantes
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Paginación -->
                <div class="mt-4">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>