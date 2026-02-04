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
            ['route_name' => 'reports.out-of-town'],
            [
                'title' => 'Report Absen Luar Kota',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>', // Reused report icon
                'order' => 14, // After "Reports" (order 13)
                'is_header' => false
            ]
        );

        // 2. Assign to Admin and HCS
        $roles = Role::whereIn('name', ['Administrator', 'HCS', 'Admin'])->get(); 
        // Note: Role names might vary, checking 'is_admin' and specific names
        
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
