<?php

namespace App\Models\System\Student;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Settings\School\Grade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_code',
        'academic_status',
        'enrollment_date',
        'current_grade_id',
        'additional_info',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Grado actual del estudiante
     */
    public function currentGrade()
    {
        return $this->belongsTo(Grade::class, 'current_grade_id');
    }

    /**
     * Matrículas del estudiante (histórico de grados)
     */
    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    /**
     * Todos los grados por los que ha pasado el estudiante
     */
    public function grades()
    {
        return $this->hasManyThrough(
            Grade::class,
            StudentEnrollment::class,
            'student_id',
            'id',
            'id',
            'grade_id'
        );
    }

    /**
     * Nombre completo del estudiante (desde User)
     */
   public function getFullNameAttribute()
    {
        // Cambiar de $this->user->full_name a:
        return optional($this->user)->full_name ?? 'Sin nombre';
    }

    /**
     * Estado académico legible
     */
    public function getAcademicStatusNameAttribute()
    {
        return match((int) $this->academic_status) {
            0 => 'Inactivo',
            1 => 'Activo',
            2 => 'Graduado',
            3 => 'Suspendido',
            default => 'Desconocido',
        };
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->student_code)) {
                // Acceso seguro a school a través de user
                $schoolInitials = optional($model->user)->school ? $model->user->school->initials() : 'SCH';
                $model->student_code = Str::upper($schoolInitials) . '-' . 
                                    Str::substr(Str::uuid()->toString(), 0, 8);
            }
        });
    }
}
