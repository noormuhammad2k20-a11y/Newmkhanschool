<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
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
        
        return back()->with('success', "Permissions for {$role->name} updated successfully.");
    }
}
