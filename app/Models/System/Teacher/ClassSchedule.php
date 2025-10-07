<?php

namespace App\Models\System\Teacher;

use App\Models\User;
use App\Models\Settings\School\Year;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Trimester\Trimester;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSchedule extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'year_id',
        'teacher_id',
        'subject_id',
        'grade_id',
        'trimester_id',
        'schedule_type',
        'day',
        'start_time',
        'end_time',
        'classroom',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean'
    ];
    
    // Constantes para tipos de horario
    const TYPE_OFFICIAL = 'OFFICIAL';
    const TYPE_EVALUATION = 'EVALUATION';
    const TYPE_TEST = 'TEST';
    const TYPE_MAKEUP = 'MAKEUP';

    public static function getScheduleTypes()
    {
        return [
            self::TYPE_OFFICIAL => 'Horario Oficial',
            self::TYPE_EVALUATION => 'Horario de Evaluación',
            self::TYPE_TEST => 'Horario de Prueba',
            self::TYPE_MAKEUP => 'Horario de Recuperación',
        ];
    }

    // Relaciones
    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function trimester()
    {
        return $this->belongsTo(Trimester::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('schedule_type', $type);
    }

    public function scopeOfficial($query)
    {
        return $query->where('schedule_type', self::TYPE_OFFICIAL);
    }

    public function scopeEvaluation($query)
    {
        return $query->where('schedule_type', self::TYPE_EVALUATION);
    }

    public function scopeTest($query)
    {
        return $query->where('schedule_type', self::TYPE_TEST);
    }

    public function scopeMakeup($query)
    {
        return $query->where('schedule_type', self::TYPE_MAKEUP);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('trimester_id');
    }

    public function scopeByTrimester($query, $trimesterId)
    {
        return $query->where('trimester_id', $trimesterId);
    }

    // Métodos específicos por tipo
    public function isOfficial()
    {
        return $this->schedule_type === self::TYPE_OFFICIAL;
    }

    public function isEvaluation()
    {
        return $this->schedule_type === self::TYPE_EVALUATION;
    }

    public function isTest()
    {
        return $this->schedule_type === self::TYPE_TEST;
    }

    public function isMakeup()
    {
        return $this->schedule_type === self::TYPE_MAKEUP;
    }

    // Verificar si es global (aplica a todos los trimestres)
    public function isGlobal()
    {
        return is_null($this->trimester_id);
    }

    // Atributos calculados
    public function getDurationAttribute()
    {
        return $this->start_time->diff($this->end_time)->format('%H:%I');
    }

    public function getScheduleAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    public function getTypeNameAttribute()
    {
        return self::getScheduleTypes()[$this->schedule_type] ?? $this->schedule_type;
    }

    public function getScopeDescriptionAttribute()
    {
        if ($this->isGlobal()) {
            return 'Aplica a todos los trimestres';
        } else {
            return "Trimestre específico: {$this->trimester->name}";
        }
    }
}