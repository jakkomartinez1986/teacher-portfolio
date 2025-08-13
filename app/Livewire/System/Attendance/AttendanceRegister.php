<?php

namespace App\Livewire\System\Attendance;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\System\Student\Student;
use App\Models\System\Attendance\Attendance;
use App\Models\System\Teacher\ClassSchedule;
use App\Models\System\Attendance\ClassObservation;
use Illuminate\Container\Attributes\Auth;

class AttendanceRegister extends Component
{
    use WithFileUploads;

    public $selectedDate;
    public $selectedSchedule;
    public $schedules = [];
    public $students = [];
    public $classtopic = '';
    public $generalObservation = '';
    public $novedades = [];
    public $justificationFiles = [];   
    public $isDaily = false;
    public $tutorId;
    public $readyToLoad = false;

   public function rules()
    {
        $rules = [
            'classtopic' => 'required|string|max:255',
            'generalObservation' => 'nullable|string|max:1000',
        ];

        foreach ($this->novedades as $studentId => $novedad) {
            if (!empty($novedad['status'])) {
                $rules["novedades.$studentId.status"] = 'required|in:A,I,J,AA,AI,P,N';
                $rules["novedades.$studentId.arrival_time"] = 'required_if:novedades.'.$studentId.'.status,A|nullable|date_format:H:i';
                $rules["novedades.$studentId.justification"] = 'required_if:novedades.'.$studentId.'.status,J,P|nullable|string|max:500';
                $rules["novedades.$studentId.observation"] = 'nullable|string|max:500';
                $rules["justificationFiles.$studentId"] = 'nullable|file|mimes:pdf,jpg,png|max:2048';
            }
        }

        return $rules;
    }

    protected $messages = [
        'classtopic.required' => 'El tema de la clase es obligatorio',
        'novedades.*.status.required' => 'Seleccione un tipo de novedad',
        'novedades.*.arrival_time.required_if' => 'La hora de llegada es obligatoria para atrasos',
        'novedades.*.justification.required_if' => 'La justificación es obligatoria para este tipo de novedad',
    ];

    public function mount($tutorId = null, $isDaily = false)
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->isDaily = $isDaily;
        $this->tutorId = $tutorId;
        $this->loadTeacherSchedules();
        $this->readyToLoad = true;
    }

    public function loadData()
    {
        // if ($this->isDaily && $this->tutorId) {
        //     //$this->loadDailyStudents();
        // } else {
           
        // }
         $this->loadTeacherSchedules();
    }

    public function loadTeacherSchedules()
    {
        $daysMap = [
            'Monday'    => 'LUNES',
            'Tuesday'   => 'MARTES',
            'Wednesday' => 'MIÉRCOLES',
            'Thursday'  => 'JUEVES',
            'Friday'    => 'VIERNES',
            'Saturday'  => 'SÁBADO',
            'Sunday'    => 'DOMINGO',
        ];

        $currentDay = $daysMap[now()->englishDayOfWeek] ?? 'LUNES';

        $this->schedules = ClassSchedule::with(['grade', 'subject'])
            ->where('teacher_id', auth()->id())
            ->where('day',   $currentDay )
            ->get();
    }

    public function loadDailyStudents()
    {
      
        // $this->students = Student::where('current_grade_id', $this->gradeId)
        //         ->join('users', 'students.user_id', '=', 'users.id')
        //         ->select([
        //             'students.*',
        //             'users.name',
        //             'users.lastname',
        //             'users.email',
        //             'users.dni',
        //             'users.phone',
        //             'users.cellphone',
        //             'users.address',
        //             'users.profile_photo_path'
        //         ])
        //         ->orderBy('users.lastname')
        //         ->orderBy('users.name')
        //         ->get();
        //$this->initializeAttendanceData();
    }

    public function updatedSelectedSchedule($scheduleId)
    {
        if ($scheduleId) {
            $schedule = ClassSchedule::findOrFail($scheduleId);
            $this->tutorId = ClassSchedule::whereHas('subject', function ($query) {
                $query->where('subject_name', 'Acompañamiento integral en el aula');
                })
                ->where('grade_id', $schedule->grade_id)
                ->value('teacher_id');
               
            $this->students = Student::where('current_grade_id', $schedule->grade_id)
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->select([
                        'students.*',
                        'users.name',
                        'users.lastname',
                        'users.email',
                        'users.dni',
                        'users.phone',
                        'users.cellphone',
                        'users.address',
                        'users.profile_photo_path'
                    ])
                    ->orderBy('users.lastname')
                    ->orderBy('users.name')
                    ->get();
            $this->initializeAttendanceData();
        }
    }

    public function initializeAttendanceData()
    {
        foreach ($this->students as $student) {
            $existing = Attendance::where('student_id', $student->id)
                ->where('date', $this->selectedDate)
                ->where('recorded_by', auth()->id())
                ->when(!$this->isDaily, function($q) {
                    $q->where('class_schedule_id', $this->selectedSchedule);
                })
                ->first();

            $this->novedades[$student->id] = [
                'show' => false,
                'status' => $existing->status ?? null,
                'arrival_time' => optional($existing)->arrival_time?->format('H:i'),
                'justification' => $existing->justification ?? null,
                'observation' => $existing->observation ?? null
            ];
        }
    }

    public function save()
    {
        $this->validate();
        
            try {
            $observation = ClassObservation::updateOrCreate(
                [
                    'class_schedule_id' => $this->isDaily ? null : $this->selectedSchedule,
                    'observation_date' => $this->selectedDate
                ],
                [
                    'tutor_id' => $this->tutorId,
                    'teacher_id' => Auth()->id(),
                    'classtopic' => $this->classtopic,
                    'observation' => $this->generalObservation
                ]
            );
            
            $hasAttendances = false;
            
            foreach ($this->students as $student) {
                $studentId = $student->id;
                
                if (!empty($this->novedades[$studentId]['status'])) {
                    $hasAttendances = true;
                    $attendanceData = [
                        'student_id' => $studentId,
                        'date' => $this->selectedDate,
                        'status' => $this->novedades[$studentId]['status'],
                        'arrival_time' => $this->novedades[$studentId]['arrival_time'] ?? null,
                        'justification' => $this->novedades[$studentId]['justification'] ?? null,
                        'observation' => $this->novedades[$studentId]['observation'] ?? null,
                        'recorded_by' => Auth()->id(),
                        'class_observation_id' => $observation->id
                    ];

                    if (!$this->isDaily) {
                        $attendanceData['class_schedule_id'] = $this->selectedSchedule;
                    }

                    if (isset($this->justificationFiles[$studentId])) {
                        $attendanceData['justification_file_path'] = $this->justificationFiles[$studentId]->store(
                            'attendance/justifications',
                            'public'
                        );
                    }

                    Attendance::updateOrCreate(
                        [
                            'tutor_id' => $this->tutorId,
                            'student_id' => $studentId,
                            'date' => $this->selectedDate,
                            'class_schedule_id' => $this->isDaily ? null : $this->selectedSchedule
                        ],
                        $attendanceData
                    );
                }
            }

            // Mostrar mensaje de éxito en ambos casos
           
            $message = $hasAttendances 
                ? 'Clase y Asistencia registradas correctamente' 
                : 'Clase y Asistencia con todos presentes registrada correctamente';
            
            $this->dispatch('swal', [
                'title' => 'Éxito!',
                'text' => $message,
                'icon' => 'success'
            ]);

            $this->resetFields();

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error!',
                'text' => 'Error al guardar: '.$e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    protected function resetFields()
    {
        $this->generalObservation = '';
        $this->justificationFiles = [];
    }

    public function toggleNovedad($studentId)
    {
        if (!isset($this->novedades[$studentId])) {
            $this->novedades[$studentId] = [
                'show' => false,
                'status' => null,
                'arrival_time' => null,
                'justification' => null,
                'observation' => null
            ];
        }
        
        $this->novedades[$studentId]['show'] = !$this->novedades[$studentId]['show'];
    }

    public function getStatusText($status)
    {
        $statuses = [
            'A' => 'Atraso',
            'I' => 'Falta Injustificada',
            'J' => 'Falta Justificada',
            'AA' => 'Abandono Aula',
            'AI' => 'Abandono Institucional',
            'P' => 'Permiso',
            'N' => 'Novedad Estudiante'
        ];

        return $statuses[$status] ?? 'Presente';
    }

      protected function getStatusColor($status)
    {
        $status = preg_replace('/\s+/', '', $status);
        $colors = [
            'A' => 'bg-yellow-500',
            'I' => 'bg-red-500',
            'J' => 'bg-orange-500',
            'AA' => 'bg-purple-500',
            'AI' => 'bg-purple-700',
            'P' => 'bg-blue-500',
            'N' => 'bg-gray-500',
        ];

        return $colors[$status] ?? 'bg-green-500';
    }

    public function render()
    {
        return view('livewire.system.attendance.attendance-register', [
            'isValid' => $this->classtopic && collect($this->novedades)->contains(fn($n) => !empty($n['status']))
        ]);
    }
}