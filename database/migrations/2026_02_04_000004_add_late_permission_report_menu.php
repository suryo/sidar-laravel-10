<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Menu;
use App\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create the menu item
        $menu = Menu::firstOrCreate(
            ['route_name' => 'reports.late-permission'],
            [
                'title' => 'Report Izin Terlambat',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>', // Clock icon
                'order' => 15, // After Out of Town (order 14)
                'is_header' => false
            ]
        );

        // 2. Assign to Admin and HCS
        $roles = Role::where('is_admin', true)->orWhere('name', 'HCS')->get();

        foreach ($roles as $role) {
            if (!$role->menus->contains($menu->id)) {
                $role->menus()->attach($menu->id);
            }
        }
    }

    public function down(): void
    {
        // No down needed
    }
};
