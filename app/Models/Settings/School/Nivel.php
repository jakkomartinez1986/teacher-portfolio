<?php

namespace App\Models\Settings\School;

use App\Models\Settings\School\Shift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nivel extends Model
{
    
    use SoftDeletes;

    protected $fillable = [
        'shift_id',
        'nivel_name',
        'status'
    ];

    protected $dates = ['deleted_at'];

    // Relación con Shift
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // Relación con Grade
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('nivel_name', 'like', '%'.strtoupper($query).'%');
    }
}
