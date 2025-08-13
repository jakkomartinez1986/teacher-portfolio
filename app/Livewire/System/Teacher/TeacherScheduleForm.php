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
    
    public $trimester_id;
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
   
    
    // Cargar estructura inicial de datos
    $this->loadAreasWithSubjects();
    $this->loadNivelesByJornada();
    $this->loadHorarios();
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
        $this->horarios = ClassSchedule::with(['subject.area', 'grade.nivel'])
            ->where('teacher_id', Auth::id())
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
    }
    public function save()
    {
        $this->validate();
        $year = Year::where('status', 1)->first();
        $gradeactive = Grade::find($this->selectedGrade);
        $data = [
            'year_id' => $year->id,
            'teacher_id' => Auth::id(),
            'subject_id' => $this->selectedSubject,
            'grade_id' => $this->selectedGrade,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'classroom' => $gradeactive->grade_name.' - '.$gradeactive->section,//.' - '.$gradeactive->nivel->nivel_name,
            'is_active' => true
        ];
        //dd($data);
        if ($this->editMode) {
            $horario = ClassSchedule::findOrFail($this->horarioId);
            $horario->update($data);
            $subject=Subject::find($this->selectedSubject);
            $user=Auth::user();           
            if(strtoupper($subject->subject_name) == strtoupper('Acompañamiento integral en el aula')){
                if (!$user->hasRole('TUTOR')) {
                    $user->assignRole('TUTOR');
                }                
                         
            }               
            if (!$user->hasRole('DOCENTE')) {
                $user->assignRole('DOCENTE');
            }   

            $this->dispatch('swal',[
                'title'=>'Actualizado!',
                'text'=>'Modelo Horario con ID '.$horario->id.' Actualizado Correctamente!',
                'icon'=>'success',
              ]);
        
        } else {
            $subject=Subject::find($this->selectedSubject);
            $user=Auth::user();           
            if(strtoupper($subject->subject_name) == strtoupper('Acompañamiento integral en el aula')){
                if (!$user->hasRole('TUTOR')) {
                    $user->assignRole('TUTOR');
                }
                
                if (!$user->hasRole('DOCENTE')) {
                    $user->assignRole('DOCENTE');
                }             
            }          
            $horarioAc=ClassSchedule::create($data);
            $this->dispatch('swal',[
                'title'=>'Creado!',
                'text'=>'Modelo Horario con ID '.$horarioAc->id.' Creado Correctamente!',
                'icon'=>'success',
              ]);
           
        }

        $this->resetForm();
        $this->loadHorarios();
    }
    public function edit($horarioId)
    {
        $horario = ClassSchedule::findOrFail($horarioId);
        
        $this->horarioId = $horarioId;
        $this->selectedArea = $horario->subject->area_id;
        $this->selectedSubject = $horario->subject_id;
        $this->selectedJornada = $horario->grade->nivel->jornada;
        $this->selectedNivel = $horario->grade->nivel_id;
        $this->selectedGrade = $horario->grade_id;
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
            'title' => '¿Estás seguro de Eliminar el Id:'.$id .' del Modelo Horario Docente '.' ?',
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
                'text'=>'Modelo Horario con ID '.$id.' Eliminado Correctamente!',
                'icon'=>'success',
              ]);
            
        } else {
            $this->dispatch('swal',[
                'title'=>'Error!',
                'text'=>'Modelo Horario con ID '.$id.' no encontrado!',
                'icon'=>'error',
              ]);
           
        }
       
    }
    public function resetForm()
    {
        $this->reset([
            'selectedArea', 'selectedSubject', 'selectedJornada', 
            'selectedNivel', 'selectedGrade', 'trimester_id', 'day',
            'start_time', 'end_time', 'classroom', 'editMode', 'horarioId'
        ]);
    }

    public function render()
    {
        return view('livewire.system.teacher.teacher-schedule-form', [
            // 'trimestres' => $this->trimestres,
            'diasSemana' => [
                'LUNES', 'MARTES', 'MIÉRCOLES', 
                'JUEVES', 'VIERNES'
            ]
        ]);
    }

    
}
