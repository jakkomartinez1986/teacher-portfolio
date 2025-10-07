<?php

namespace App\Livewire\System\Teacher;

use Livewire\Component;
use App\Models\Settings\Area\Area;
use App\Models\Settings\School\Year;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use App\Models\Settings\School\Nivel;
use App\Models\Settings\School\Shift;
use App\Models\Settings\Trimester\Trimester;
use App\Models\System\Teacher\ClassSchedule;

class TeacherScheduleForm extends Component
{
    public $areas;
    public $jornadas;
    public $areasConAsignaturas = [];
    public $nivelesPorJornada = [];
    public $gradosPorNivel = [];
    
    public $selectedArea = null;
    public $selectedSubject = null;
    public $selectedJornada = null;
    public $selectedNivel = null;
    public $selectedGrade = null;
    public $scheduleType = null;
    public $scheduleTypeSelected = false;
    
    public $trimester_id = null;
    public $day;
    public $start_time;
    public $end_time;
    public $classroom;
    public $confirming;
    public $horarios = [];
    public $editMode = false;
    public $horarioId = null;
    
    protected $listeners = ['deleteConfirmed'];
    
    protected $rules = [
        'selectedArea' => 'required',
        'selectedSubject' => 'required',
        'selectedJornada' => 'required',
        'selectedNivel' => 'required',
        'selectedGrade' => 'required',
        'day' => 'required',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ];

    public function mount()
    {
        $this->areas = Area::with('subjects')->get();
        $this->jornadas = Shift::all()->map(function($shift) {
            return [
                'id' => $shift->id,
                'name' => $shift->shift_name
            ];
        });
        
        $this->loadAreasWithSubjects();
        $this->loadNivelesByJornada();
        $this->loadHorarios();
    }
    
    /**
     * Seleccionar el tipo de horario una sola vez
     */
    public function selectScheduleType($type)
    {
        $this->scheduleType = $type;
        $this->scheduleTypeSelected = true;
        
        // Solo para evaluación y recuperación, pre-seleccionar trimestre activo
        if (in_array($type, ['EVALUATION', 'MAKEUP'])) {
            $currentTrimester = Trimester::where('status', 1)->first();
            if ($currentTrimester) {
                $this->trimester_id = $currentTrimester->id;
            }
        } else {
            // Para oficial y prueba, siempre será global (null)
            $this->trimester_id = null;
        }
    }
    
    /**
     * Cambiar el tipo de horario (solo si no hay horarios guardados con este tipo)
     */
    public function changeScheduleType()
    {
        // Verificar si ya hay horarios guardados con el tipo actual
        $existingSchedules = ClassSchedule::where('teacher_id', Auth::id())
            ->where('schedule_type', $this->scheduleType)
            ->count();
            
        if ($existingSchedules > 0) {
            $this->dispatch('swal', [
                'title' => 'No se puede cambiar',
                'text' => 'No puedes cambiar el tipo de horario porque ya tienes horarios guardados con este tipo.',
                'icon' => 'warning',
            ]);
            return;
        }
        
        $this->scheduleTypeSelected = false;
        $this->scheduleType = null;
        $this->trimester_id = null;
        $this->resetForm();
    }
    
    public function loadAreasWithSubjects()
    {
        $this->areasConAsignaturas = $this->areas->map(function($area) {
            return [
                'id' => $area->id,
                'name' => $area->area_name,
                'subjects' => $area->subjects->map(function($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->subject_name
                    ];
                })->toArray()
            ];
        })->toArray();
    }
    
    public function updatedSelectedJornada($jornada)
    {
        $this->selectedNivel = null;
        $this->selectedGrade = null;
        $this->loadNivelesByJornada();
    }

    public function updatedSelectedNivel($nivelId)
    {
        $this->selectedGrade = null;
        $this->loadGradosByNivel();
    }
    
    public function loadNivelesByJornada()
    {
        if (!$this->selectedJornada) {
            $this->nivelesPorJornada = [];
            return;
        }
    
        $this->nivelesPorJornada = Nivel::where('shift_id', $this->selectedJornada)
            ->get()
            ->map(function($nivel) {
                return [
                    'id' => $nivel->id,
                    'name' => $nivel->nivel_name,
                    'shift_name' => $nivel->shift->shift_name ?? 'N/A'
                ];
            })->toArray();
    }
    
    public function loadGradosByNivel()
    {
        if (!$this->selectedNivel) {
            $this->gradosPorNivel = [];
            return;
        }
    
        $this->gradosPorNivel = Grade::where('nivel_id', $this->selectedNivel)
            ->get()
            ->map(function($grado) {
                return [
                    'id' => $grado->id,
                    'name' => $grado->grade_name . ' - ' . $grado->section
                ];
            })->toArray();
    }
    
    public function loadHorarios()
    {
        $this->horarios = ClassSchedule::with(['subject.area', 'grade.nivel', 'trimester'])
            ->where('teacher_id', Auth::id())
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
    }
    
    public function save()
    {
        // Validar que el tipo de horario esté seleccionado
        if (!$this->scheduleTypeSelected || !$this->scheduleType) {
            $this->dispatch('swal', [
                'title' => 'Tipo de horario requerido',
                'text' => 'Debes seleccionar un tipo de horario primero.',
                'icon' => 'warning',
            ]);
            return;
        }

        // Validación adicional para evaluación y recuperación
        if ($this->requiresTrimester() && !$this->trimester_id) {
            $this->dispatch('swal', [
                'title' => 'Trimestre requerido',
                'text' => 'Para horarios de evaluación y recuperación debes seleccionar un trimestre.',
                'icon' => 'warning',
            ]);
            return;
        }

        $this->validate();
        $year = Year::where('status', 1)->first();
        $gradeactive = Grade::find($this->selectedGrade);
        
        $data = [
            'year_id' => $year->id,
            'teacher_id' => Auth::id(),
            'subject_id' => $this->selectedSubject,
            'grade_id' => $this->selectedGrade,
            'trimester_id' => $this->requiresTrimester() ? $this->trimester_id : null,
            'schedule_type' => $this->scheduleType,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'classroom' => $gradeactive->grade_name.' - '.$gradeactive->section,
            'is_active' => true
        ];

        if ($this->editMode) {
            $horario = ClassSchedule::findOrFail($this->horarioId);
            $horario->update($data);
            $this->assignUserRoles($this->selectedSubject);
            
            $this->dispatch('swal',[
                'title'=>'Actualizado!',
                'text'=>'Horario con ID '.$horario->id.' Actualizado Correctamente!',
                'icon'=>'success',
            ]);
        } else {
            $this->assignUserRoles($this->selectedSubject);
            $horarioAc = ClassSchedule::create($data);
            
            $this->dispatch('swal',[
                'title'=>'Creado!',
                'text'=>'Horario con ID '.$horarioAc->id.' Creado Correctamente!',
                'icon'=>'success',
            ]);
        }

        $this->resetForm();
        $this->loadHorarios();
    }
    
    /**
     * Verifica si el tipo actual requiere trimestre
     */
    public function requiresTrimester()
    {
        return in_array($this->scheduleType, ['EVALUATION', 'MAKEUP']);
    }
    
    /**
     * Asigna roles al usuario según la asignatura
     */
    private function assignUserRoles($subjectId)
    {
        $subject = Subject::find($subjectId);
        $user = Auth::user();
        
        if ($subject && strtoupper($subject->subject_name) == strtoupper('Acompañamiento integral en el aula')) {
            if (!$user->hasRole('TUTOR')) {
                $user->assignRole('TUTOR');
            }
        }
        
        if (!$user->hasRole('DOCENTE')) {
            $user->assignRole('DOCENTE');
        }
    }
    
    public function edit($horarioId)
    {
        $horario = ClassSchedule::findOrFail($horarioId);
        
        $this->horarioId = $horarioId;
        $this->selectedArea = $horario->subject->area_id;
        $this->selectedSubject = $horario->subject_id;
        $this->selectedJornada = $horario->grade->nivel->shift_id;
        $this->selectedNivel = $horario->grade->nivel_id;
        $this->selectedGrade = $horario->grade_id;
        $this->scheduleType = $horario->schedule_type;
        $this->scheduleTypeSelected = true;
        $this->trimester_id = $horario->trimester_id;
        $this->day = $horario->day;
        $this->start_time = $horario->start_time->format('H:i');
        $this->end_time = $horario->end_time->format('H:i');
             
        $this->editMode = true;
        $this->dispatch('scroll-to-form');
    }

    public function delete($id)
    {
        $this->confirming = $id;       
        $this->dispatch('delete-prompt', [
            'icon' => 'warning',
            'title' => '¿Estás seguro de Eliminar el Id:'.$id .' del Horario Docente?',
            'text' => '¡No podrás revertir esto!',
            'id' => $id
        ]);
    }
    
    public function deleteConfirmed($id)
    {
        $delete_model = ClassSchedule::find($id);
        $this->confirming = $id;
       
        if ($delete_model) {            
            $delete_model->delete();          
            $this->dispatch('swal',[
                'title'=>'¡Eliminado!',
                'text'=>'Horario con ID '.$id.' Eliminado Correctamente!',
                'icon'=>'success',
            ]);
            $this->loadHorarios();
        } else {
            $this->dispatch('swal',[
                'title'=>'Error!',
                'text'=>'Horario con ID '.$id.' no encontrado!',
                'icon'=>'error',
            ]);
        }
    }
    
    public function resetForm()
    {
        $this->reset([
            'selectedArea', 'selectedSubject', 'selectedJornada', 
            'selectedNivel', 'selectedGrade', 'day', 
            'start_time', 'end_time', 'classroom', 'editMode', 'horarioId'
        ]);
    }
    
    /**
     * Tipos de horario disponibles
     */
    public function getScheduleTypes()
    {
        return [
            'TEST' => 'Horario de Prueba',
            'OFFICIAL' => 'Horario Oficial',
            'EVALUATION' => 'Horario de Evaluación',
            'MAKEUP' => 'Horario de Recuperación',
        ];
    }

    public function render()
    {
        return view('livewire.system.teacher.teacher-schedule-form', [
            'scheduleTypes' => $this->getScheduleTypes(),
            'trimestres' => Trimester::where('status', 1)->get(),
            'requiresTrimester' => $this->requiresTrimester(), // Pasar la variable a la vista
            'diasSemana' => [
                'LUNES', 'MARTES', 'MIÉRCOLES', 
                'JUEVES', 'VIERNES'
            ]
        ]);
    }
}