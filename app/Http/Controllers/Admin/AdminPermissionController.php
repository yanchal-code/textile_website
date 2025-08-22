<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;

class AdminPermissionController extends Controller
{
    public function edit(User $user)
    {
        $permissions = Permission::all()->groupBy('group');
        if($user->is_superadmin){
            return back()->with('error', 'Changes Not Allowed For Superadmin.');
        }
        return view('admin.users.permissions', [
            'user' => $user,
            'groupedPermissions' => $permissions
        ]);
    }

    public function update(Request $request, User $user)
    {
        $user->permissions()->sync($request->permissions ?? []);
        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }
}
