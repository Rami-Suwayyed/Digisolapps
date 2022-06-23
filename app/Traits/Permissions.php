<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

trait Permissions
{
    public function isPermissionsAllowed(...$permissions): bool
    {
        $user = Auth::guard("admin")->user();

        if($permissions[0] == "admin-control"){
            return $user->isAdministrator();
        }

        if($user->isAdministrator())
            return true;

        foreach ($permissions as $permission){
            if(Gate::allows($permission)){
                return true;
            }
        }
        return false;
    }

    public function permissionsAllowed(...$permissions){
        $user = Auth::guard("admin")->user();

        if($permissions[0] == "admin-control"){
            if(!$user->isAdministrator())
                abort(401);
            return true;
        }

        if($user->isAdministrator())
            return true;

        foreach ($permissions as $permission){
            if(Gate::allows($permission)){
                return true;
            }
        }
        abort(401);
    }
}
