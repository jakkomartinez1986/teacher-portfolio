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
    public $horariodocente = [];
    public $confirming;
    public $tipoHorarioActivo = 'TEST';
    public $tiposHorario = [];
    
    protected $listeners = ['deleteConfirmed', 'horarioActivado', 'horarioDesactivado', 'todosHorariosActivados', 'todosHorariosDesactivados'];

    public function mount()
    {
        $this->inicioSemanaLaboral = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $this->finSemanaLaboral = $this->inicioSemanaLaboral->copy()->addDays(4);
        $this->diaSeleccionado = Carbon::now()->isoFormat('dddd');
        $this->inicializarSemana();
        $this->cargarTiposHorario();
        $this->cargarHorarios();
    }

    public function cargarTiposHorario()
    {
        $this->tiposHorario = [
            'TEST' => [
                'nombre' => 'Horario de Prueba',
                'color' => 'yellow',
                'icono' => '🧪'
            ],
            'OFFICIAL' => [
                'nombre' => 'Horario Oficial',
                'color' => 'green',
                'icono' => '📚'
            ],
            'EVALUATION' => [
                'nombre' => 'Horario de Evaluación',
                'color' => 'red',
                'icono' => '📝'
            ],
           
            'MAKEUP' => [
                'nombre' => 'Horario de Recuperación',
                'color' => 'purple',
                'icono' => '🔄'
            ]
        ];
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

    public function cambiarTipoHorario($tipo)
    {
        $this->tipoHorarioActivo = $tipo;
        $this->cargarHorarios();
    }

    public function cargarHorarios()
    {
        $this->horarios = ClassSchedule::with(['subject', 'grade.nivel', 'trimester'])
            ->where('teacher_id', Auth::id())
            ->where('day', mb_strtoupper($this->diaSeleccionado, 'UTF-8'))
            ->where('schedule_type', $this->tipoHorarioActivo)
            ->orderBy('start_time')
            ->get();

        $this->horariodocente = ClassSchedule::with(['subject', 'grade.nivel', 'trimester'])
            ->where('teacher_id', Auth::id())
            ->where('schedule_type', $this->tipoHorarioActivo)
            ->orderBy('grade_id')
            ->get();
    }

    public function toggleHorario($id)
    {
        $horario = ClassSchedule::find($id);
        
        if ($horario) {
            $nuevoEstado = !$horario->is_active;
            $horario->update(['is_active' => $nuevoEstado]);
            
            if ($nuevoEstado) {
                $this->dispatch('horarioActivado', $id);
            } else {
                $this->dispatch('horarioDesactivado', $id);
            }
            
            $this->cargarHorarios();
        }
    }

    /**
     * Activar todos los horarios del tipo actual
     */
    public function activarTodosHorarios()
    {
        $afectados = ClassSchedule::where('teacher_id', Auth::id())
            ->where('schedule_type', $this->tipoHorarioActivo)
            ->update(['is_active' => true]);

        $this->dispatch('todosHorariosActivados', $afectados);
        $this->cargarHorarios();
    }

    /**
     * Desactivar todos los horarios del tipo actual
     */
    public function desactivarTodosHorarios()
    {
        $afectados = ClassSchedule::where('teacher_id', Auth::id())
            ->where('schedule_type', $this->tipoHorarioActivo)
            ->update(['is_active' => false]);

        $this->dispatch('todosHorariosDesactivados', $afectados);
        $this->cargarHorarios();
    }

    /**
     * Verifica si hay horarios inactivos del tipo actual
     */
    public function getHayHorariosInactivosProperty()
    {
        return ClassSchedule::where('teacher_id', Auth::id())
            ->where('schedule_type', $this->tipoHorarioActivo)
            ->where('is_active', false)
            ->exists();
    }

    /**
     * Verifica si hay horarios activos del tipo actual
     */
    public function getHayHorariosActivosProperty()
    {
        return ClassSchedule::where('teacher_id', Auth::id())
            ->where('schedule_type', $this->tipoHorarioActivo)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Obtiene estadísticas de horarios activos/inactivos
     */
    public function getEstadisticasHorariosProperty()
    {
        $total = ClassSchedule::where('teacher_id', Auth::id())
            ->where('schedule_type', $this->tipoHorarioActivo)
            ->count();

        $activos = ClassSchedule::where('teacher_id', Auth::id())
            ->where('schedule_type', $this->tipoHorarioActivo)
            ->where('is_active', true)
            ->count();

        $inactivos = $total - $activos;

        return [
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos
        ];
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
            $this->cargarHorarios();
        } else {
            $this->dispatch('swal',[
                'title'=>'Error!',
                'text'=>'Horario con ID '.$id.' no encontrado!',
                'icon'=>'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.system.teacher.teacher-schedule', [
            'hayHorariosInactivos' => $this->hayHorariosInactivos,
            'hayHorariosActivos' => $this->hayHorariosActivos,
            'estadisticasHorarios' => $this->estadisticasHorarios,
        ]);
    }
}