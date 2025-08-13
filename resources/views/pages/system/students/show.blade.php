<x-layouts.app :title="__('Detalles del Estudiante')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Inicio</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('students.students.index') }}">Estudiantes</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <!-- Encabezado con foto y datos básicos -->
            <div class="mb-6 flex flex-col items-start gap-6 md:flex-row">
                <!-- Foto de perfil con modal -->
                <div class="flex-shrink-0 group relative">
                    <img src="{{ asset($student->user->defaultUserPhotoUrl()) }}" 
                         alt="Foto del estudiante"
                         class="h-32 w-32 cursor-pointer rounded-full border-2 border-neutral-200 object-cover transition-transform duration-200 group-hover:scale-105 dark:border-neutral-600"
                         @click="showPhotoModal = true">
                    {{-- <flux:avatar size="lg" src="{{asset( $student->user->defaultUserPhotoUrl()) }}" @click="showPhotoModal = true"/>
                     --}}
                    <!-- Indicador de foto personalizada -->
                    @if($student->user->profile_photo_path)
                        <div class="absolute -right-1 -top-1 flex h-6 w-6 items-center justify-center rounded-full bg-blue-500 p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    @endif
                </div>
                
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">
                            {{ $student->user->full_name }}
                        </h2>
                        <flux:badge :color="$student->academic_status == 1 ? 'green' : ($student->academic_status == 2 ? 'purple' : 'red')">
                            {{ $student->academic_status_name }}
                        </flux:badge>
                    </div>
                    
                    <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="flex items-start gap-2">
                            <div class="mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">DNI</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $student->user->dni }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Código</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $student->student_code }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Grado Actual</p>
                                <p class="text-neutral-800 dark:text-neutral-200">
                                    {{ $student->currentGrade->grade_name }} {{ $student->currentGrade->section }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Fecha Matrícula</p>
                                <p class="text-neutral-800 dark:text-neutral-200">
                                    {{ $student->enrollment_date->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para foto ampliada -->
            <div x-data="{ showPhotoModal: false }" x-cloak>
                <div x-show="showPhotoModal" class="fixed inset-0 z-50 overflow-y-auto">
                    <div class="flex min-h-screen items-center justify-center p-4 text-center">
                        <div x-show="showPhotoModal" 
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" 
                             @click="showPhotoModal = false">
                        </div>

                        <div x-show="showPhotoModal"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="relative inline-block w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left align-middle shadow-xl transition-all dark:bg-neutral-800">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium leading-6 text-neutral-900 dark:text-neutral-100">
                                        Foto de perfil - {{ $student->user->getFullNameAttribute() }}
                                    </h3>
                                    <button @click="showPhotoModal = false" class="text-neutral-400 hover:text-neutral-500">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-4 flex justify-center">
                                      <flux:avatar size="lg" src="{{asset($student->user->defaultUserPhotoUrl()) }}"/>
                                    {{-- <img src="{{ $student->user->defaultUserPhotoUrl()}}" 
                                         alt="Foto ampliada del estudiante"
                                         class="max-h-[70vh] max-w-full rounded-lg object-contain"> --}}
                                         
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestañas de información -->
            <div x-data="{ tab: 'info' }" class="mb-6">
                <!-- Pestañas -->
                <div class="flex border-b border-neutral-300 dark:border-neutral-700">
                    <button @click="tab = 'info'" :class="tab === 'info' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-neutral-500'"
                        class="px-4 py-2 text-sm font-medium focus:outline-none">
                        Información General
                    </button>
                    <button @click="tab = 'history'" :class="tab === 'history' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-neutral-500'"
                        class="px-4 py-2 text-sm font-medium focus:outline-none">
                        Historial Académico
                    </button>
                </div>

                <!-- Contenido -->
                <div x-show="tab === 'info'" class="pt-4">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Información Personal -->
                        <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                            <h3 class="flex items-center gap-2 text-lg font-medium text-neutral-700 dark:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Información Personal
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Correo Electrónico</p>
                                        <p class="text-neutral-800 dark:text-neutral-200">
                                            {{ $student->user->email ?? 'No registrado' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Teléfono</p>
                                        <p class="text-neutral-800 dark:text-neutral-200">
                                            {{ $student->user->phone ?? 'No registrado' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Celular</p>
                                        <p class="text-neutral-800 dark:text-neutral-200">
                                            {{ $student->user->cellphone ?? 'No registrado' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Dirección</p>
                                        <p class="text-neutral-800 dark:text-neutral-200">
                                            {{ $student->user->address ?? 'No registrada' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Académica -->
                        <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                            <h3 class="flex items-center gap-2 text-lg font-medium text-neutral-700 dark:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Información Académica
                            </h3>
                            {{-- <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Información Adicional</p>
                                    <p class="text-neutral-800 dark:text-neutral-200 whitespace-pre-line">
                                        {{ $student->additional_info ?? 'No hay información adicional' }}
                                    </p>
                                </div>
                            </div> --}}
                            @php
                                $info = json_decode($student->additional_info, true);
                            @endphp

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Información Adicional</p>

                                    @if ($info)
                                        <ul class="list-disc pl-5 text-neutral-800 dark:text-neutral-200">
                                            @foreach($info as $clave => $valor)
                                                <li>
                                                    <strong class="capitalize">{{ str_replace('_', ' ', $clave) }}:</strong>
                                                    {{ is_array($valor) ? implode(', ', $valor) : $valor }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-neutral-800 dark:text-neutral-200">No hay información adicional</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'history'" class="pt-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-neutral-500 dark:text-neutral-400">Año</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-neutral-500 dark:text-neutral-400">Grado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-neutral-500 dark:text-neutral-400">Fecha Matrícula</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-neutral-500 dark:text-neutral-400">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                                @forelse($student->enrollments as $enrollment)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-800 dark:text-neutral-200">{{ $enrollment->academic_year }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-800 dark:text-neutral-200">{{ $enrollment->grade->grade_name }} {{ $enrollment->grade->section }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-800 dark:text-neutral-200">{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-800 dark:text-neutral-200">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $enrollment->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                                {{ $enrollment->status == 'active' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            No hay historial académico registrado
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end gap-3">
                <flux:button
                    color="gray"
                    variant="outline"
                    href="{{ route('students.students.index') }}"
                    tag="a"
                >
                    Volver
                </flux:button>

                @can('update', $student)
                    <flux:button
                        color="blue"
                        href="{{ route('students.students.edit', $student) }}"
                        tag="a"
                    >
                        <flux:icon name="pencil" class="h-4 w-4" />
                        Editar
                    </flux:button>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>