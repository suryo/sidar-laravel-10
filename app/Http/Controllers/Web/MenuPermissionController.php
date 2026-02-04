<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class MenuPermissionController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $menus = Menu::orderBy('order')->get();

        return view('admin.menus.permissions', compact('roles', 'menus'));
    }

    public function update(Request $request)
    { // Expects input[role_id][menu_id] = on/off
      // Or simpler: input 'permissions' => [role_id => [menu_ids...]]
        
        $data = $request->input('permissions', []);
        
        $roles = Role::all();
        foreach ($roles as $role) {
            if (isset($data[$role->id])) {
                $role->menus()->sync(array_keys($data[$role->id]));
            } else {
                $role->menus()->detach();
            }
        }

        return redirect()->back()->with('success', 'Menu permissions updated successfully.');
    }
}
