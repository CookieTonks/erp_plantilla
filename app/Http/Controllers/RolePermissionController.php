<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $users = User::all();
        return view('vistas.settings.home', compact('roles', 'permissions', 'users'));
    }

    public function storeRole(Request $request)
    {
        Role::create(['name' => $request->name]);
        return back()->with('success', 'Role created successfully!');
    }

    public function createPermission(Request $request)
    {
        $request->validate([
            'permission_name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->permission_name]);

        return back()->with('success', 'Permiso creado exitosamente.');
    }

    public function assignRole(Request $request)
    {
        $user = User::find($request->user_id);
        $user->assignRole($request->role_name);
        return back()->with('success', 'Role assigned to user successfully!');
    }

    public function assignPermission(Request $request)
    {
        $role = Role::findByName($request->role_name);
        $permission = Permission::findByName($request->permission_name);

        $role->givePermissionTo($permission);

        return back()->with('success', 'Permission assigned to role successfully!');
    }

    public function removeRole(Request $request)
    {
        $user = User::find($request->user_id);
        $role = $request->role_name;

        $user->removeRole($role);

        return back()->with('success', 'Rol quitado al usuario exitosamente.');
    }

    public function removePermission(Request $request)
    {
        $role = Role::findByName($request->role_name);
        $permission = Permission::findByName($request->permission_name);

        $role->revokePermissionTo($permission);

        return back()->with('success', 'Permiso revocado del rol exitosamente.');
    }


    public function givePermissionToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_name' => 'required|exists:permissions,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $permission = Permission::findByName($request->permission_name);

        $user->givePermissionTo($permission);

        return back()->with('success', 'Permiso asignado al usuario exitosamente.');
    }

    public function revokePermissionFromUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_name' => 'required|exists:permissions,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $permission = Permission::findByName($request->permission_name);

        $user->revokePermissionTo($permission);

        return back()->with('success', 'Permiso revocado del usuario exitosamente.');
    }
}
