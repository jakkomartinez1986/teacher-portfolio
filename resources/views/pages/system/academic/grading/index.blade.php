<x-layouts.app :title="__('Calificaciones')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Calificaciones</flux:breadcrumbs.item>   
        </flux:breadcrumbs>      

        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" class="bg-green-500 text-white p-4 rounded-md shadow-md flex justify-between items-center">
                <div>{{ session('success') }}</div>
                <button @click="show = false" class="text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-4">
            <!-- Filtros -->
            <form action="{{ route('academic.grading-summary.index') }}" method="GET" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Materia -->
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Materia</label>
                        <select name="subject_id" id="subject_id" class="w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                            <option value="">Seleccione una materia</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $selectedSubject && $selectedSubject->id == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->subject_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Trimestre -->
                    <div>
                        <label for="trimester_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Trimestre</label>
                        <select name="trimester_id" id="trimester_id" class="w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                            <option value="">Seleccione un trimestre</option>
                            @foreach($trimesters as $trimester)
                                <option value="{{ $trimester->id }}" {{ $selectedTrimester && $selectedTrimester->id == $trimester->id ? 'selected' : '' }}>
                                    {{ $trimester->trimester_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Curso -->
                    <div>
                        <label for="grade_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Curso</label>
                        <select name="grade_id" id="grade_id" class="w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                            <option value="">Seleccione un curso</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}" {{ $selectedGrade && $selectedGrade->id == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->grade_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                  <!-- Botón Buscar -->
                <div class="flex items-end">
                    <flux:button 
                        type="submit" 
                        icon="magnifying-glass" 
                        color="primary"
                        class="w-full justify-center"
                    >
                        Buscar
                    </flux:button>
                </div>
                </div>
            </form>
            
            @if($selectedSubject && $selectedTrimester && $selectedGrade)
                <!-- Información del curso -->
                <div class="mb-6 p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                        <div class="space-y-1">
                            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">
                                {{ $selectedSubject->name }} - {{ $selectedGrade->name }}
                            </h3>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                                <span class="font-medium">Trimestre:</span> {{ $selectedTrimester->name }} | 
                                <span class="font-medium">Paralelo:</span> ÚNICO | 
                                <span class="font-medium">Jornada:</span> MATUTINA
                            </p>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <button class="bg-green-600 hover:bg-green-700 text-white text-sm rounded-md px-3 py-2 flex items-center gap-1" data-toggle="modal" data-target="#importModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Importar Excel
                            </button>
                            
                            <button class="bg-yellow-600 hover:bg-yellow-700 text-white text-sm rounded-md px-3 py-2 flex items-center gap-1" id="pasteFromExcelBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                Pegar desde Excel
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario de notas -->
                <form action="{{ route('academic.grading-summary.store') }}" method="POST" id="gradesForm">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
                    <input type="hidden" name="grade_id" value="{{ $selectedGrade->id }}">
                    <input type="hidden" name="trimester_id" value="{{ $selectedTrimester->id }}">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-600">
                            <thead class="bg-neutral-50 dark:bg-neutral-700">
                                <tr>
                                    <th rowspan="2" class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">#</th>
                                    <th rowspan="2" class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Estudiantes</th>
                                    
                                    <!-- Evaluación Formativa 70% -->
                                    {{-- @if($activities->count() > 0) --}}
                                        <th colspan="{{ $activities->count() }}" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider bg-blue-600">
                                            Evaluación Formativa 70%
                                        </th>
                                    {{-- @endif --}}
                                    
                                    <!-- Evaluación Sumativa 30% -->
                                    <th colspan="2" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider bg-green-600">
                                        Evaluación Sumativa 30%
                                    </th>
                                    
                                    <th rowspan="2" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider bg-indigo-600">
                                        Nota Final
                                    </th>
                                </tr>
                                
                                <tr>
                                    <!-- Columnas de actividades formativas -->
                                    @foreach($activities as $activity)
                                        <th class="px-3 py-2 text-center text-xs font-medium text-white uppercase tracking-wider bg-blue-500" title="{{ $activity->name }}">
                                            {{ Str::limit($activity->name, 10) }}
                                        </th>
                                    @endforeach
                                    
                                    <!-- Columnas de evaluación sumativa -->
                                    <th class="px-3 py-2 text-center text-xs font-medium text-white uppercase tracking-wider bg-green-500">Examen</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-white uppercase tracking-wider bg-green-500">Proyecto</th>
                                </tr>
                            </thead>
                            
                            <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-600">
                                @foreach($students as $index => $student)
                                    @php
                                        $summary = $gradeSummaries->get($student->id);
                                    @endphp
                                    
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-300 text-center">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                            {{ $student->lastname }} {{ $student->name }}
                                            <input type="hidden" name="grades[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                        </td>
                                        
                                        <!-- Notas formativas -->
                                        @foreach($activities as $activity)
                                            @php
                                                $gradeValue = $formativeGrades[$student->id][$activity->id][0]->value ?? '';
                                            @endphp
                                            
                                            <td class="px-2 py-2 whitespace-nowrap text-center">
                                                <input type="number" 
                                                       name="grades[{{ $student->id }}][formative][{{ $activity->id }}]" 
                                                       value="{{ $gradeValue }}" 
                                                       min="0" max="10" step="0.01" 
                                                       class="w-16 mx-auto px-2 py-1 border border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 rounded-md text-center focus:ring-primary-500 focus:border-primary-500"
                                                       data-student="{{ $student->id }}"
                                                       data-type="formative">
                                            </td>
                                        @endforeach
                                        
                                        <!-- Notas sumativas -->
                                        @php
                                            $examGrade = $summativeGrades[$student->id]['summative_exam'][0]->value ?? '';
                                            $projectGrade = $summativeGrades[$student->id]['summative_project'][0]->value ?? '';
                                        @endphp
                                        
                                        <td class="px-2 py-2 whitespace-nowrap text-center">
                                            <input type="number" 
                                                   name="grades[{{ $student->id }}][summative_exam]" 
                                                   value="{{ $examGrade }}" 
                                                   min="0" max="10" step="0.01" 
                                                   class="w-16 mx-auto px-2 py-1 border border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 rounded-md text-center focus:ring-primary-500 focus:border-primary-500"
                                                   data-student="{{ $student->id }}"
                                                   data-type="summative_exam">
                                        </td>
                                        
                                        <td class="px-2 py-2 whitespace-nowrap text-center">
                                            <input type="number" 
                                                   name="grades[{{ $student->id }}][summative_project]" 
                                                   value="{{ $projectGrade }}" 
                                                   min="0" max="10" step="0.01" 
                                                   class="w-16 mx-auto px-2 py-1 border border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 rounded-md text-center focus:ring-primary-500 focus:border-primary-500"
                                                   data-student="{{ $student->id }}"
                                                   data-type="summative_project">
                                        </td>
                                        
                                        <!-- Nota final -->
                                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                            <span class="inline-block px-2 py-1 rounded-full {{ $summary && $summary->approved ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ $summary ? number_format($summary->final_grade, 2) : '0.00' }}
                                            </span>
                                            <input type="hidden" class="final-grade" 
                                                   value="{{ $summary ? $summary->final_grade : 0 }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm text-sm font-medium text-neutral-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" id="saveDraftBtn">
                            Guardar como Borrador
                        </button>
                        
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Guardar Notas Definitivas
                        </button>
                    </div>
                </form>
            @else
                <div class="p-8 text-center bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-neutral-200">Seleccione una materia, trimestre y curso</h3>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Para comenzar a registrar calificaciones, seleccione los filtros arriba y haga clic en Buscar.</p>
                </div>
            @endif
        </div>
    </div> 
   
    @push('scripts')
    <script>
        $(document).ready(function() {
            // Mostrar modal para pegar desde Excel
            $('#pasteFromExcelBtn').click(function() {
                $('#pasteExcelModal').modal('show');
            });
            
            // Enviar formulario para pegar desde Excel
            $('#pasteExcelForm').submit(function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: "{{ route('academic.grading-summary.paste-from-excel') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        $('#pasteExcelModal').find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#pasteExcelModal').modal('hide');
                            Swal.fire({
                                title: 'Éxito',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON?.message || 'Error desconocido';
                        Swal.fire({
                            title: 'Error',
                            text: error,
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        $('#pasteExcelModal').find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-paste"></i> Pegar y Guardar');
                    }
                });
            });
            
            // Validar y formatear notas al salir del campo
            $('.grade-input').blur(function() {
                let value = parseFloat($(this).val());
                
                if (isNaN(value)) {
                    $(this).val('');
                    return;
                }
                
                // Asegurar que esté entre 0 y 10
                if (value < 0) {
                    $(this).val(0);
                } else if (value > 10) {
                    $(this).val(10);
                } else {
                    // Formatear a 2 decimales
                    $(this).val(value.toFixed(2));
                }
                
                // Calcular nota final para este estudiante
                calculateFinalGrade($(this).data('student'));
            });
            
            // Calcular nota final para un estudiante
            function calculateFinalGrade(studentId) {
                // Obtener todas las notas formativas del estudiante
                let formativeGrades = $(`.grade-input[data-student="${studentId}"][data-type="formative"]`)
                    .map(function() {
                        let val = parseFloat($(this).val());
                        return isNaN(val) ? 0 : val;
                    }).get();
                
                // Calcular promedio formativo
                let formativeAvg = formativeGrades.length > 0 ? 
                    formativeGrades.reduce((a, b) => a + b, 0) / formativeGrades.length : 0;
                
                // Obtener notas sumativas
                let examGrade = parseFloat($(`.grade-input[data-student="${studentId}"][data-type="summative_exam"]`).val()) || 0;
                let projectGrade = parseFloat($(`.grade-input[data-student="${studentId}"][data-type="summative_project"]`).val()) || 0;
                
                // Calcular nota final (70% formativo + 30% sumativo)
                let summativeAvg = (examGrade + projectGrade) / 2;
                let finalGrade = (formativeAvg * 0.7) + (summativeAvg * 0.3);
                
                // Actualizar visualización
                $(`tr:has(input[name="grades[${studentId}][student_id]"]) .final-grade`).val(finalGrade.toFixed(2));
                $(`tr:has(input[name="grades[${studentId}][student_id]"]) td:last-child`)
                    .text(finalGrade.toFixed(2))
                    .toggleClass('text-success', finalGrade >= 7)
                    .toggleClass('text-danger', finalGrade < 7);
            }
            
            // Guardar como borrador
            $('#saveDraftBtn').click(function() {
                $('#gradesForm').append('<input type="hidden" name="is_draft" value="1">');
                $('#gradesForm').submit();
            });
            
            // Validar antes de enviar el formulario
            $('#gradesForm').submit(function(e) {
                let isValid = true;
                let errorMessage = '';
                
                // Validar que todas las notas estén entre 0 y 10
                $('.grade-input').each(function() {
                    let value = parseFloat($(this).val());
                    if (!isNaN(value) && (value < 0 || value > 10)) {
                        isValid = false;
                        errorMessage = 'Todas las notas deben estar entre 0 y 10';
                        return false;
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error'
                    });
                    return false;
                }
                
                // Mostrar confirmación si no es borrador
                if (!$('input[name="is_draft"]').length) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Guardar notas definitivas?',
                        text: 'Esta acción guardará las notas de forma permanente. ¿Desea continuar?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, guardar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#gradesForm').off('submit').submit();
                        }
                    });
                }
            });
            
            // Configurar importación de Excel
            $('#importForm').submit(function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: '¿Importar notas desde Excel?',
                    text: 'Esta acción sobrescribirá las notas existentes para este grupo. ¿Desea continuar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, importar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).off('submit').submit();
                    }
                });
            });
        });
    </script>
    @endpush
    <style>
        .table th {
            vertical-align: middle;
        }
        .grade-input {
            max-width: 80px;
            margin: 0 auto;
        }
        .final-grade {
            font-weight: bold;
            font-size: 1.1em;
        }
        .text-success {
            color: #28a745 !important;
        }
        .text-danger {
            color: #dc3545 !important;
        }
        .bg-primary {
            background-color: #4e73df !important;
        }
        .bg-success {
            background-color: #1cc88a !important;
        }
        .bg-info {
            background-color: #36b9cc !important;
        }
    </style>
</x-layouts.app>

  {{-- <!-- Modal para importar Excel -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="importModalLabel">Importar Notas desde Excel</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        <form action="{{ route('academic.grading-summary.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $selectedSubject->id ?? '' }}">
            <input type="hidden" name="grade_id" value="{{ $selectedGrade->id ?? '' }}">
            <input type="hidden" name="trimester_id" value="{{ $selectedTrimester->id ?? '' }}">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="file">Archivo Excel</label>
                    <input type="file" name="file" id="file" class="form-control-file" accept=".xlsx,.xls" required>
                    <small class="form-text text-muted">
                        El archivo debe tener el formato correcto con los nombres de estudiantes y actividades.
                    </small>
                </div>
                
                <div class="alert alert-warning">
                    <strong>Nota:</strong> Al importar, se sobrescribirán las notas existentes para este grupo.
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Importar Notas</button>
            </div>
        </form>
    </div>        
</div>

<!-- Modal para pegar desde Excel -->
<div class="modal fade" id="pasteExcelModal" tabindex="-1" role="dialog" aria-labelledby="pasteExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="pasteExcelModalLabel">Pegar Datos desde Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="pasteExcelForm">
                @csrf
                <input type="hidden" name="subject_id" value="{{ $selectedSubject->id ?? '' }}">
                <input type="hidden" name="grade_id" value="{{ $selectedGrade->id ?? '' }}">
                <input type="hidden" name="trimester_id" value="{{ $selectedTrimester->id ?? '' }}">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excel_data">Copie y pegue los datos desde Excel</label>
                        <textarea name="excel_data" id="excel_data" rows="10" class="form-control" placeholder="Pegue aquí los datos copiados de Excel (incluyendo los encabezados)"></textarea>
                        <small class="form-text text-muted">
                            Copie los datos desde Excel (incluyendo la fila de encabezados) y péguelos aquí.
                            Asegúrese de que la primera columna contenga los nombres completos de los estudiantes.
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Formato esperado:</strong> Primera columna con nombres de estudiantes, seguido de las columnas 
                        de actividades formativas y finalmente las columnas de evaluación sumativa (examen y proyecto).
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paste"></i> Pegar y Guardar Notas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}