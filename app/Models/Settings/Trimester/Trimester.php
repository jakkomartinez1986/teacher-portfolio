<?php

namespace App\Models\Settings\Trimester;

use Illuminate\Support\Carbon;
use App\Models\Settings\School\Year;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trimester extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year_id',
        'trimester_name',
        'status',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'deleted_at' => 'datetime',
    ];
   
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the status name.
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return $this->status == 1 ? 'Activo' : 'Inactivo';
    }

    /**
     * Scope a query to only include active trimesters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include inactive trimesters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
 // Relación con Year
    public function year()
    {
        return $this->belongsTo(Year::class);
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('area_name', 'ilike', '%'.strtoupper($query).'%');
    }

    
    public function isCurrentlyActive()
    {
        $today = Carbon::today();

        return $this->status == 1 &&
            $this->start_date <= $today &&
            $this->end_date >= $today;
    }

     public function estadoTrimestre()
    {      
       
        return match((int) $this->status) {
           
            0 => 'Inactivo',
            1 => 'Activo',
            default => 'Desconocido', // Por si en el futuro hay otro valor como 2, 3, etc.
        };
    }

    public function getDurationDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1; // +1 para incluir ambos días
    }
}
