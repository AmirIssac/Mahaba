<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function rolesAndPermissionsIndex(){
        $roles = Role::get();
        $permissions = Permission::simplePaginate(20);
        return view('Admin.Roles.index',['roles'=>$roles,'permissions'=>$permissions]);
    }

}
