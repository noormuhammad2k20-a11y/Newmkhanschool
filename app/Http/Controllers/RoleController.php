<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    use AjaxResponseTrait;
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $permissionIds = $request->input('permissions', []);
        
        // Sync permissions
        $role->permissions()->sync($permissionIds);
        
        return $this->ajaxSuccess($request, "Permissions for {$role->name} updated successfully.");
    }
}
