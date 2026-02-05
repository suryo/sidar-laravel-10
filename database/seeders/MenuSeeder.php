<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // 1. Clear existing
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Menu::truncate();
        \DB::table('menu_role')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Define Menus
        $menus = [
            // MAIN MENU
            [
                'title' => 'Main Menu',
                'is_header' => true,
                'order' => 1,
            ],
            [
                'title' => 'Dashboard',
                'route_name' => 'dashboard',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>',
                'order' => 2,
            ],
            [
                'title' => 'Daily Activity (DAR)',
                'route_name' => 'dars.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>',
                'order' => 3,
            ],
            
            // PRESENSI & CUTI
            [
                'title' => 'Presensi & Cuti',
                'is_header' => true,
                'order' => 4,
            ],
            [
                'title' => 'Presensi (Check-in)',
                'route_name' => 'attendance.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'order' => 5,
            ],
            [
                'title' => 'Cuti & Izin',
                'route_name' => 'leaves.index', // Assuming this route exists or is the one intended
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                'order' => 6,
            ],
             [
                'title' => 'Klaim & Reimbursement',
                'route_name' => 'claims.index', 
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z" /></svg>',
                'order' => 7,
            ],

            // MANAGEMENT (Managers+)
            [
                'title' => 'Management',
                'is_header' => true,
                'order' => 8,
                'only_approvers' => true, // Helper flag for seeder
            ],
            [
                'title' => 'Approval DAR',
                'route_name' => 'dars.approvals',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'order' => 9,
                'only_approvers' => true,
            ],
            [
                'title' => 'Approval Cuti',
                'route_name' => 'leaves.approvals', // Assuming route name
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>',
                'order' => 10,
                'only_approvers' => true,
            ],
            [
                'title' => 'Approval Klaim',
                'route_name' => 'claims.approvals',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'order' => 11,
                'only_approvers' => true,
            ],

             // REPORTS
            [
                'title' => 'Reports & Analytics',
                'is_header' => true,
                'order' => 12,
                'only_approvers' => true,
            ],
            [
                'title' => 'Reports',
                'route_name' => 'reports.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                'order' => 13,
                'only_approvers' => true,
            ],

            // CORRESPONDENCE
            [
                'title' => 'Correspondence',
                'is_header' => true,
                'order' => 14,
            ],
            [
                'title' => 'Official Letters',
                'route_name' => 'letters.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
                'order' => 15,
            ],
            [
                'title' => 'Manage Templates',
                'route_name' => 'letter-templates.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>', // Reused icon
                'order' => 16,
                 'only_admin_hcs' => true, // Flag for logic below
            ],
            [
                'title' => 'Calendar & Holidays',
                'route_name' => 'holidays.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                'order' => 17,
                'only_approvers' => true, // Actually logic says approvers OR admin
            ],

            // MASTER DATA (NEW)
            [
                'title' => 'Master Data & Claims',
                'is_header' => true,
                'order' => 18,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Master Plafon',
                'route_name' => 'medical-plafons.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                'order' => 19,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Monitoring Sisa Plafon',
                'route_name' => 'medical-plafons.monitoring',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
                'order' => 20,
                'only_admin_hcs' => true,
            ],
             [
                'title' => 'Master User Klaim',
                'route_name' => 'claim-users.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                'order' => 21,
                'only_admin_hcs' => true,
            ],
             [
                'title' => 'Master Group Klaim',
                'route_name' => 'claim-groups.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                'order' => 22,
                'only_admin_hcs' => true,
            ],
            
            // NEW MASTER DATA
            [
                'title' => 'Jabatan',
                'route_name' => 'master.roles.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
                'order' => 2201,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Departemen',
                'route_name' => 'master.departments.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                'order' => 2202,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Akses Area',
                'route_name' => 'master.access-areas.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>',
                'order' => 2203,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Unit Usaha',
                'route_name' => 'master.business-units.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                'order' => 2204,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Lokasi Kerja',
                'route_name' => 'master.locations.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
                'order' => 2205,
                'only_admin_hcs' => true,
            ],
            
            // REGULATIONS
            [
                'title' => 'Regulations & Settings',
                'is_header' => true,
                'order' => 23,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Pengaturan Resign',
                'route_name' => 'resignations.settings',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>',
                'order' => 24,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Pengaturan Distributor',
                'route_name' => 'distributors.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
                'order' => 25,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Reset Data',
                'route_name' => 'system.reset',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>',
                'order' => 26,
                'only_admin_hcs' => true,
            ],

            // ADMINISTRATION
            [
                'title' => 'Administration',
                'is_header' => true,
                'order' => 27,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Employees',
                'route_name' => 'employees.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                'order' => 28,
                'only_admin_hcs' => true,
            ],
            [
                'title' => 'Menu Settings',
                'route_name' => 'settings.menus.index',
                'icon_svg' => '<svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
                'order' => 29,
                'only_admin_hcs' => true,
            ],
        ];

        foreach ($menus as $menuData) {
            $onlyApprovers = $menuData['only_approvers'] ?? false;
            $onlyAdminHCS = $menuData['only_admin_hcs'] ?? false;
            
            unset($menuData['only_approvers'], $menuData['only_admin_hcs']);

            $menu = Menu::create($menuData);

            // Assign to roles
            $roles = Role::all();
            foreach ($roles as $role) {
                // Logic based on current sidebar
                $assign = true;

                if ($onlyApprovers) {
                    if (!$role->can_approve && !$role->is_admin) {
                        $assign = false;
                    }
                }

                if ($onlyAdminHCS) {
                    if (!$role->is_admin && $role->name !== 'HCS') {
                        $assign = false;
                    }
                }
                
                // Everyone gets everything else (Main menu, Basic presence)
                if ($assign) {
                    $role->menus()->attach($menu->id);
                }
            }
        }
    }
}
