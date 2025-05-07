<?php

namespace App\Models\Settings\School;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class School extends Model
{
    protected $fillable = [
        'name_school',
        'distrit',
        'location',
        'address',
        'phone',
        'email',
        'website',
        'logo_path',
        'status',
    ];
    protected $appends = ['logo_url'];
    
    // Relación con User
    public function users()
    {
        return $this->hasMany(User::class);
    }
     
    public function initials(): string
    {
        return Str::of($this->name_school)
            ->explode(' ')
            ->map(fn (string $name_school) => Str::of($name_school)->substr(0, 1))
            ->implode('');
    }
    public function defaultSchoolPhotoUrl()
    {      
       
        // Si hay un logo_path definido, retornamos la URL completa del logo
        if ($this->logo_path) {
            return Storage::disk('public')->url($this->logo_path);
        }
        
        // Si no hay logo, generamos uno con las iniciales usando ui-avatars
        return 'https://ui-avatars.com/api/?' . http_build_query([
            'name' => $this->initials(),
            'background' => '6875F5',
            'color' => 'f5f5f5',
            'size' => '128'
        ]);
    }
    public function getAdressAttribute()
    {
        return $this->location . '/' . $this->address;
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name_school', 'ilike', '%'.strtoupper($query).'%')
                ->orWhere('distrit', 'ilike', '%'.strtoupper($query).'%');
    }
}
