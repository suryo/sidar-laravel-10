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
            ['route_name' => 'reports.leave-report'],
            [
                'title' => 'Report Izin Cuti',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>', // Calendar icon
                'order' => 16, // After Late Permission (order 15)
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
