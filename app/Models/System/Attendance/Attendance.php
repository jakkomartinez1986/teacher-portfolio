<?php

namespace App\Models\System\Attendance;

use App\Models\User;
use App\Models\System\Student\Student;
use Illuminate\Database\Eloquent\Model;
use App\Models\System\Teacher\ClassSchedule;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'class_observation_id',
        'class_schedule_id',
        'tutor_id',
        'student_id',
        'date',
        'status',
        'arrival_time',
        'justification',
        'justification_file_path',
        'observation',
        'recorded_by',
        'notification_data', // Nuevo campo JSON
        'notification_sent_at' // Nueva marca de tiempo
    ];

    protected $casts = [
        'date' => 'date',
        'arrival_time' => 'datetime:H:i',
        'notification_data' => 'json', // Cast para el campo JSON
        'notification_sent_at' => 'datetime'
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function observation()
    {
        return $this->belongsTo(ClassObservation::class, 'class_observation_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForGrade($query, $gradeId)
    {
        return $query->whereHas('classSchedule', function($q) use ($gradeId) {
            $q->where('grade_id', $gradeId);
        });
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function getStatusNameAttribute()
    {
        $statuses = [
            'A' => 'Atraso',
            'I' => 'Falta Injustificada',
            'J' => 'Falta Justificada',
            'AA' => 'Abandono Aula',
            'AI' => 'Abandono Institucional',
            'P' => 'Permiso',
            'N' => 'Novedad'
        ];

        return $statuses[$this->status] ?? 'Presente';
    }
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = trim($value);
    }

    ///notificaciones 
     /**
     * Registra el envío de una notificación por WhatsApp
     */
    public function recordWhatsAppNotification($messageContent, $status = null)
    {
        $this->update([
            'notification_data' => [
                'message_content' => $messageContent,
                'sent_via' => 'whatsapp',
                'status_before' => $this->status,
                'status_after' => $status ?? $this->status
            ],
            'notification_sent_at' => now(),
            'status' => $status ?? $this->status
        ]);
        
        return $this;
    }

    /**
     * Obtiene el historial de notificaciones enviadas
     */
    public function getNotificationHistoryAttribute()
    {
        return $this->notification_data ?? [];
    }

    /**
     * Verifica si se ha enviado notificación
     */
    public function getWasNotifiedAttribute()
    {
        return !is_null($this->notification_sent_at);
    }
}