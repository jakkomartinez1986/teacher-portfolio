<?php

namespace App\Models\Settings\Calendar;

use App\Models\Settings\School\Year;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Trimester\Trimester;

class CalendarDay extends Model
{
    protected $fillable = [
        'year_id',
        'trimester_id',
        'period',
        'date',
        'month_name',
        'day_name',
        'week',
        'day_number',
        'activity'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function trimester()
    {
        return $this->belongsTo(Trimester::class);
    }
}
