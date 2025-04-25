<?php

namespace App\Models\Settings\Area;

use App\Models\Settings\Area\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'area_name'
    ]; 

    protected $dates = ['deleted_at'];

    // Relación con Subject
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('area_name', 'ilike', '%'.strtoupper($query).'%');
    }
}
