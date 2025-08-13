<?php

namespace App\Models\Settings\School;

use App\Models\Document\Document;
use App\Models\Settings\School\Nivel;
use App\Models\System\Student\Student;
use Illuminate\Database\Eloquent\Model;
use App\Models\System\Teacher\ClassSchedule;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nivel_id',
        'grade_name',
        'section',
        'status'
    ];

    protected $dates = ['deleted_at'];

    // Relación con Nivel
    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('grade_name', 'ilike', '%'.strtoupper($query).'%');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_subject_grade')
                ->withPivot('subject_id');
    }
   public function students()
    {
        return $this->hasMany(Student::class, 'current_grade_id');
    }
    // Grade.php
    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

}
