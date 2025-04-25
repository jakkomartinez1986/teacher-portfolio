<?php

namespace App\Models\Settings\School;

use App\Models\Settings\School\Year;
use App\Models\Settings\School\Nivel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'year_id',
        'shift_name',
        'status'
    ];

    protected $casts = [
        'shift_name' => 'string' // MATUTINA, VESPERTINA, INTENSIVO
    ];

    protected $dates = ['deleted_at'];

    // Relación con Year
    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    // Relación con Nivel
    public function nivels()
    {
        return $this->hasMany(Nivel::class);
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('shift_name', 'ilike', '%'.strtoupper($query).'%');
    }
}
 