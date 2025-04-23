<?php

namespace App\Livewire\System\Teacher;

use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\System\Teacher\ClassSchedule;

class TeacherSchedule extends Component
{
    public $diaSeleccionado;
    public $diasDeLaSemana = [];
    public $inicioSemanaLaboral;
    public $finSemanaLaboral;
    public $horarios = [];
    public $confirming;
    protected $listeners = ['deleteConfirmed'];
    public function mount()
    {
        $this->inicioSemanaLaboral = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $this->finSemanaLaboral = $this->inicioSemanaLaboral->copy()->addDays(4);
        $this->diaSeleccionado = Carbon::now()->isoFormat('dddd');
        $this->inicializarSemana();
        $this->cargarHorarios();
    }

    public function getInicioSemanaLaboralFormattedProperty()
    {
        return $this->inicioSemanaLaboral->isoFormat('D [de] MMMM');
    }

    public function getFinSemanaLaboralFormattedProperty()
    {
        return $this->finSemanaLaboral->isoFormat('D [de] MMMM');
    }

    public function inicializarSemana()
    {
        $this->diasDeLaSemana = [];
        
        for ($i = 0; $i < 5; $i++) {
            $fecha = $this->inicioSemanaLaboral->copy()->addDays($i);
            $this->diasDeLaSemana[$fecha->isoFormat('dddd')] = $fecha->toDateString();
        }
    }

    public function actualizarDia($diaNombre)
    {
        $this->diaSeleccionado = $diaNombre;
        $this->cargarHorarios();
    }

    public function cargarHorarios()
    {
         //dd(strtoupper($this->diaSeleccionado));
        $this->horarios = ClassSchedule::with(['subject', 'grade.nivel'])
            ->where('teacher_id', Auth::id())
            ->where('day',  mb_strtoupper($this->diaSeleccionado, 'UTF-8'))
            ->orderBy('start_time')
            ->get();
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
            
            // $delete_model->delete();          
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

    public function render()
    {
        return view('livewire.system.teacher.teacher-schedule');
    }
}
