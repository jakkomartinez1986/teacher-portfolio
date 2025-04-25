<?php

namespace App\Models\Security\Spatie;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends SpatieRole
{
    protected $guard_name = 'web';

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'ilike', '%'.strtoupper($query).'%');
    }
    public function defaultRolePhotoUrl()
    {
        $nombres = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));
        return 'https://ui-avatars.com/api/?name='.urlencode($nombres).'&background=6875F5&color=f5f5f5';//color=ff0000&background=a0a0a0
    }


    public static function roleHasPermission($role, $permissions)
    {
        $hasPermission = true;

        foreach ($permissions as $permission) {
            if(!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
            return $hasPermission;
        }
    }
}
