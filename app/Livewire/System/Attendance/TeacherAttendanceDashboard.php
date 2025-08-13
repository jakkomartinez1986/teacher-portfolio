<?php

namespace App\Livewire\System\Attendance;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings\School\Grade;
use App\Models\System\Student\Student;
use App\Models\System\Attendance\Attendance;
use App\Models\System\Teacher\ClassSchedule;
use App\Models\System\Attendance\ClassObservation;

class TeacherAttendanceDashboard extends Component
{
    public $startDate;
    public $endDate;
    public $attendanceData = [];
    public $summary = [];
    public $selectedGrade = null;
    public $grades = [];
    public $notificationStatus = [];
    public $unregisteredClasses = []; // Clases pendientes de registro
    public $statusOptions = [
        'A' => 'Atraso',
        'I' => 'Falta Injustificada',
        'J' => 'Falta Justificada',
        'AA' => 'Abandono Aula',
        'AI' => 'Abandono Institucional',
        'P' => 'Permiso',
        'N' => 'Novedad'
    ];

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->loadGrades();
        $this->loadData();
    }

    protected function loadGrades()
    {
        $this->grades = ClassSchedule::where('teacher_id', auth()->id())
            ->with('grade')
            ->get()
            ->pluck('grade')
            ->unique()
            ->values()
            ->all();

        // if (count($this->grades)) {
        //     $this->selectedGrade = $this->grades[0]->id;
        // }
    }

    public function loadData()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            // 'selectedGrade' => 'required|exists:grades,id'
        ]);

        // $currentGrade = Grade::find($this->selectedGrade);
        $gradeIds = collect($this->grades)->pluck('id');
        $observations = ClassObservation::with(['attendances.student.user', 'classschedule.subject'])
        ->where('teacher_id', auth()->id())
        ->whereHas('classschedule', fn($q) => $q->whereIn('grade_id', $gradeIds))
        ->whereBetween('observation_date', [$this->startDate, $this->endDate])
        ->orderBy('observation_date', 'desc')
        ->get();
        

        $groupedByDate = collect();

        foreach ($observations as $observation) {
            $date = $observation->observation_date->toDateString();

            if (!$groupedByDate->has($date)) {
                $groupedByDate->put($date, [
                    'date' => $date,
                    'classes' => collect()
                ]);
            }

            $groupedByDate[$date]['classes']->push([
                'class' => optional($observation->classschedule->subject)->subject_name ?? 'Clase desconocida',
                'grade' => optional($observation->classschedule->grade)->grade_name . ' ' . optional($observation->classschedule->grade)->section,
                'tutor'=>$observation->tutor->full_name,
                'classtopic' => $observation->classtopic,
                'observation' => $observation->observation,
                'attendances' => $observation->attendances->map(fn($att) => $this->formatAttendanceData($att))
            ]);
        }

        $this->attendanceData = $groupedByDate->sortByDesc('date');
        $this->calculateSummary();
    }

    protected function formatAttendanceData($attendance)
    {
        $studentName = optional($attendance->student)->full_name ?? 'Estudiante no encontrado';
        $status = preg_replace('/\s+/', '', $attendance->status ?? 'P');
        $phone = optional(optional($attendance->student)->user)->cellphone ?? null;        
        return [
            'id' => $attendance->id,
            'student_name' => $studentName,
            'status' => $attendance->status,
            'status_text' => $this->statusOptions[$status] ?? 'Presente',
            'status_color' => $this->getStatusColor($status),
            'arrival_time' => $attendance->arrival_time?->format('H:i'),
            'justification' => $attendance->justification,
            'observation' => $attendance->observation,
            'phone' => $phone,
            'notification_sent_at' => $attendance->notification_sent_at
        ];
    }

    protected function calculateSummary()
    {
        $this->summary = [
            'total_days' => $this->attendanceData->count(),
            'total_students' => 0,
            'status_counts' => array_fill_keys(array_keys($this->statusOptions), 0),
            'attendance_rate' => 0
        ];

        $totalRecords = 0;
        $presentRecords = 0;

        foreach ($this->attendanceData as $dayData) {
            foreach ($dayData['classes'] as $classData) {
                $this->summary['total_students'] = max($this->summary['total_students'], count($classData['attendances']));

                foreach ($classData['attendances'] as $attendance) {
                    $status = preg_replace('/\s+/', '', $attendance['status'] ?? '');

                    if ($status && isset($this->summary['status_counts'][$status])) {
                        $this->summary['status_counts'][$status]++;
                    } else {
                        $presentRecords++;
                    }

                    if (!$status || $status === 'P') {
                        $presentRecords++;
                    }

                    $totalRecords++;
                }
            }
        }

        if ($totalRecords > 0) {
            $this->summary['attendance_rate'] = round(($presentRecords / $totalRecords) * 100, 2);
        }
    }

    public function markAsSent($attendanceId)
    {
        $attendance = Attendance::find($attendanceId);
        if ($attendance) {
            $attendance->update([
                'notification_sent_at' => now(),
                'notification_data' => ['sent_via' => 'whatsapp']
            ]);
            $this->dispatch('notify-success', message: 'Notificación marcada como enviada');
            $this->loadData();
        }
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

    protected function getScheduledTecher()
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);
        
        $result = collect();
        $dayMap = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes', 
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado',
            'sunday' => 'Domingo'
        ];
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $dayName = $dayMap[strtolower($date->englishDayOfWeek)];
            $dateString = $date->format('Y-m-d');
            
            // Obtener clases programadas
            $scheduled = ClassSchedule::where('teacher_id', auth()->id())
                ->where('day', $dayName)
                ->with(['grade', 'subject'])
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.system.attendance.teacher-attendance-dashboard');
    }
}