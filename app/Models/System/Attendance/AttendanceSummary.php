<?php

namespace App\Models\System\Attendance;

use App\Models\User;
use App\Models\Settings\School\Grade;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Trimester\Trimester;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'grade_id',
        'trimester_id',
        'total_classes',
        'present_count',
        'late_count',
        'unjustified_count',
        'justified_count',
        'abandonment_count',
        'permission_count',
        'last_updated'
    ];

    // Relaciones
    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function trimester()
    {
        return $this->belongsTo(Trimester::class);
    }

}
