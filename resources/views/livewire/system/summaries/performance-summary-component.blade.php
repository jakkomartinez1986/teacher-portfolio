<div class="p-4 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Ingreso de Notas Académicas</h2>

        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                <select wire:model="gradeId" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Seleccione un curso</option>
                    
                    @foreach($grados as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->grade_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trimestre</label>
                <select wire:model="trimesterId" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Seleccione trimestre</option>
                    @foreach($trimestres as $trimester)
                        <option value="{{ $trimester->id }}">{{ $trimester->trimester_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Asignatura</label>
                <select wire:model="subjectId" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Seleccione asignatura</option>
                    @foreach($asignaturas as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                {{-- <button wire:click="loadData" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Buscar
                </button> --}}
                <flux:button wire:click="loadData">
                   Buscar
                </flux:button>
                 
            </div>
        </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    @if(is_array($students) && count($students) > 0) 
        <!-- Tarjeta de resumen -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex flex-wrap items-center justify-between">
                <div>
                    <h3 class="font-semibold text-blue-800">Resumen de Evaluación</h3>
                    <p class="text-sm text-blue-600">
                        Curso: <span class="font-medium">{{ $selectedGrade->name ?? '' }}</span> | 
                        Trimestre: <span class="font-medium">{{ $selectedTrimester->name ?? '' }}</span> | 
                        Asignatura: <span class="font-medium">{{ $selectedSubject->name ?? '' }}</span>
                    </p>
                </div>
                <div class="mt-2 sm:mt-0">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ count($students) }} estudiantes
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabla de notas -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                        @for ($i = 0; $i < 5; $i++)
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">F{{ $i+1 }}</th>
                        @endfor
                        <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Proyecto</th>
                        <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Examen</th>
                        <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prom. F.</th>
                        <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prom. S.</th>
                        <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Final</th>
                        <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Escala</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($students as $student)
                        @php $notas = $this->calcularNotas($student->id); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $student->name }}
                            </td>

                            @for ($i = 0; $i < 5; $i++)
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <input type="number" min="0" max="10" step="0.01"
                                        wire:model.lazy="formData.{{ $student->id }}.formative_scores.{{ $i }}"
                                        class="w-16 border rounded text-center focus:ring-blue-500 focus:border-blue-500 @error("formData.$student->id.formative_scores.$i") border-red-500 @enderror" />
                                </td>
                            @endfor

                            <td class="px-3 py-4 whitespace-nowrap">
                                <input type="number" min="0" max="10" step="0.01"
                                    wire:model.lazy="formData.{{ $student->id }}.integral_project"
                                    class="w-16 border rounded text-center focus:ring-blue-500 focus:border-blue-500 @error("formData.$student->id.integral_project") border-red-500 @enderror" />
                            </td>

                            <td class="px-3 py-4 whitespace-nowrap">
                                <input type="number" min="0" max="10" step="0.01"
                                    wire:model.lazy="formData.{{ $student->id }}.final_evaluation"
                                    class="w-16 border rounded text-center focus:ring-blue-500 focus:border-blue-500 @error("formData.$student->id.final_evaluation") border-red-500 @enderror" />
                            </td>

                            <td class="px-3 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $notas['formative_average'] }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $notas['summative_average'] }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-center font-semibold">
                                {{ $notas['final_grade'] }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $notas['grade_scale'] == 'DA' ? 'bg-green-100 text-green-800' : 
                                       ($notas['grade_scale'] == 'AA' ? 'bg-blue-100 text-blue-800' : 
                                       ($notas['grade_scale'] == 'PA' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ $notas['grade_scale'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="save"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Guardar Notas
            </button>
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay estudiantes para mostrar</h3>
            <p class="mt-1 text-sm text-gray-500">Seleccione un curso, trimestre y asignatura para comenzar.</p>
        </div>
    @endif
</div>