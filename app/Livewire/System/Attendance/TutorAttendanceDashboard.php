<?php

namespace App\Livewire\System\Attendance;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\System\Attendance\Attendance;
use App\Models\System\Teacher\ClassSchedule;
use App\Models\System\Attendance\ClassObservation;

class TutorAttendanceDashboard extends Component
{
    public $startDate;
    public $endDate;
    public $attendanceData = [];
    public $summary = [];
    public $grades = [];

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

        if (count($this->grades) === 0) {
            session()->flash('error', 'No se encontró un curso donde dictas "Acompañamiento integral en el aula".');
            return;
        }

        $this->loadData();
    }

    protected function loadGrades()
    {
        $this->grades = ClassSchedule::where('teacher_id', auth()->id())
            ->whereHas('subject', fn($q) => $q->where('subject_name', 'Acompañamiento integral en el aula'))
            ->with('grade')
            ->get()
            ->pluck('grade')
            ->unique()
            ->values()
            ->all();
    }

    
    public function loadData()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        if (empty($this->grades)) {
            $this->attendanceData = collect();
            $this->summary = [];
            return;
        }

        $gradeId = $this->grades[0]->id;
        
        $observations = ClassObservation::with(['attendances.student.user', 'classschedule.subject', 'classschedule.grade'])
            ->whereHas('classschedule', fn($q) => $q->where('grade_id', $gradeId))
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
                'teacher' => $observation->teacher->full_name,
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

    public function render()
    {
        return view('livewire.system.attendance.tutor-attendance-dashboard');
    }
}
