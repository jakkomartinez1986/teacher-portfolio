<?php

namespace App\Models\Security\Spatie;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission  extends \Spatie\Permission\Models\Permission
{
    protected $guard_name = 'web';

    public static function defaultPermissions()
    {

        $permisos=[];
       
        return $permisos;
    }
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'like', '%'.strtoupper($query).'%');
    }
    public static function allmodules()
    {
        $modules = Permission::select('group_name')->distinct()->orderBy('group_name')->pluck('module');
        return $modules;
    }

    public static function getPermissionGroups()
    {
        $permission_groups = Permission::select('group_name')->groupBy('group_name')->get();

        return $permission_groups;
    }

    public static function getPermissionByGroupName(String $group_name)
    {
        $permissions = Permission::select('id', 'name')->where('group_name', $group_name)->get();

        return $permissions;
    }

    public function defaultRolePhotoUrl()
    {
        $nombres = trim(collect(explode(' ', $this->label))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));
        return 'https://ui-avatars.com/api/?name='.urlencode($nombres).'&background=6875F5&color=f5f5f5';//color=ff0000&background=a0a0a0
    }


}
