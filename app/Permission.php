<?php

namespace Vanguard;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = ['name', 'display_name', 'description'];

    public function roles()
    {
    	return $this->belongsToMany('Vanguard\Role');
    }

    public function rolesPermission($permission)
    {
        $permiso = Permission::where('name', $permission)->first();

        return $permiso->roles;

    }
}