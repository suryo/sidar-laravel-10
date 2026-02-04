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
    { 
        $permissions = $request->input('permissions', []);
        $orders = $request->input('orders', []);
        
        $roles = Role::all();
        foreach ($roles as $role) {
            $syncData = [];
            
            if (isset($permissions[$role->id])) {
                foreach ($permissions[$role->id] as $menuId => $value) {
                    $order = $orders[$role->id][$menuId] ?? 0;
                    $syncData[$menuId] = ['order' => $order];
                }
            }
            
            $role->menus()->sync($syncData);
        }

        return redirect()->back()->with('success', 'Menu permissions and order updated successfully.');
    }
}
