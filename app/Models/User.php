<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\Document\Document;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use App\Models\Settings\School\School;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Models\System\Teacher\ClassSchedule;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable //implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasRoles;
 

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'dni',
        'phone',
        'cellphone',
        'status',       
        'address',
        'profile_photo_path',
        'signature_photo_path',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }
    public function defaultUserPhotoUrl()
    {      
       
        if (is_null($this->profile_photo_path)) {
                           
            
            $fotografia= 'https://ui-avatars.com/api/?name='.$this->initials().'&background=6875F5&color=f5f5f5';//&background=6875F5&color=f5f5f5
            
        } else {
            $fotografia=$this->profile_photo_path;
        }
        
        return $fotografia;
    }
    public function defaultSignaturePhotoUrl()
    {      
       
        if (is_null($this->signature_photo_path)) {
                               
            
            $signatureohoto= 'https://ui-avatars.com/api/?name=SF&background=6875F5&color=f5f5f5';//&background=6875F5&color=f5f5f5
            
        } else {
            $signatureohoto=$this->signature_photo_path;
        }
        
        return $signatureohoto;
    }
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->lastname;
    }
    public function getContactsAttribute()
    {
        return $this->phone . '/' . $this->cellphone;
    }
    public function estadoUsuario()
    {      
       
        return match((int) $this->status) {
           
            0 => 'Inactivo',
            1 => 'Activo',
            default => 'Desconocido', // Por si en el futuro hay otro valor como 2, 3, etc.
        };
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'ilike', '%'.strtoupper($query).'%')
                ->orWhere('lastname', 'ilike', '%'.strtoupper($query).'%')
                ->orWhere('email', 'ilike', '%'.$query.'%');
    }
      // Relación con School
      public function school()
      {
          return $this->belongsTo(School::class);
      }

    public function createdDocuments()
    {
        return $this->hasMany(Document::class, 'creator_id');
    }

    public function coAuthoredDocuments()
    {
        return $this->belongsToMany(Document::class, 'document_authors')
                ->withPivot('is_main_author');
    }

    public function signedDocuments()
    {
        return $this->belongsToMany(Document::class, 'document_signatures')
                ->withPivot(['role_id', 'status', 'comments', 'signed_at']);
    }
   
    // En el modelo User
    public function taughtSubjects()
    {
        return $this->hasManyThrough(
            Subject::class,
            ClassSchedule::class,
            'teacher_id', // FK en class_schedules
            'id', // FK en subjects
            'id', // Local key en users
            'subject_id' // Local key en class_schedules
        )->distinct();
    }

    public function taughtGrades()
    {
        return $this->hasManyThrough(
            Grade::class,
            ClassSchedule::class,
            'teacher_id', // FK en class_schedules
            'id', // FK en grades
            'id', // Local key en users
            'grade_id' // Local key en class_schedules
        )->distinct();
    }
}
