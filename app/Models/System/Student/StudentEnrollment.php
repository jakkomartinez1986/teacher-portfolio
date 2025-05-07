<?php

namespace App\Models\System\Student;

use App\Models\Settings\School\Grade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'grade_id',
        'enrollment_date',
        'completion_date',
        'status',
        'academic_year',
        'notes'
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'completion_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'active' => 'Activo',
            'completed' => 'Completado',
            'transferred' => 'Transferido',
            'withdrawn' => 'Retirado',
            default => $this->status,
        };
    }

}
