<?php

namespace App\Models\Settings\School;

use App\Models\Settings\School\Shift;
use App\Models\Settings\School\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Year extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'year_name',
        'start_date',
        'end_date',
        'status'
    ];

   

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'deleted_at' => 'datetime',
    ];
    // Relación con School
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relación con Shift
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('year_name', 'ilike', '%'.strtoupper($query).'%');
    }

}
