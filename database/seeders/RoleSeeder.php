<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            // 1. Administrator - Full access
            [
                'name' => 'Administrator',
                'slug' => 'administrator',
                'description' => 'Full system access with all permissions',
                'can_write' => true,
                'can_read_own' => true,
                'can_read_division' => true,
                'can_read_department' => true,
                'can_read_all' => true,
                'can_approve' => true,
                'can_manage_users' => true,
                'is_admin' => true,
                'is_hcs_print' => false,
            ],

            // 2. Staff - Write only (own data)
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'description' => 'Can write and read own data only',
                'can_write' => true,
                'can_read_own' => true,
                'can_read_division' => false,
                'can_read_department' => false,
                'can_read_all' => false,
                'can_approve' => false,
                'can_manage_users' => false,
                'is_admin' => false,
                'is_hcs_print' => false,
            ],

            // 3. Supervisor - Write, read division
            [
                'name' => 'Supervisor',
                'slug' => 'supervisor',
                'description' => 'Can write, read division data',
                'can_write' => true,
                'can_read_own' => true,
                'can_read_division' => true,
                'can_read_department' => false,
                'can_read_all' => false,
                'can_approve' => false,
                'can_manage_users' => false,
                'is_admin' => false,
                'is_hcs_print' => false,
            ],

            // 4. Supervisor with Approval - Write, read division, approve
            [
                'name' => 'Supervisor (Approver)',
                'slug' => 'supervisor-approver',
                'description' => 'Can write, read division data, and approve',
                'can_write' => true,
                'can_read_own' => true,
                'can_read_division' => true,
                'can_read_department' => false,
                'can_read_all' => false,
                'can_approve' => true,
                'can_manage_users' => false,
                'is_admin' => false,
                'is_hcs_print' => false,
            ],

            // 5. Manager - Write, read department
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Can write, read department data',
                'can_write' => true,
                'can_read_own' => true,
                'can_read_division' => true,
                'can_read_department' => true,
                'can_read_all' => false,
                'can_approve' => false,
                'can_manage_users' => false,
                'is_admin' => false,
                'is_hcs_print' => false,
            ],

            // 6. Manager with Approval - Write, read department, approve, manage users
            [
                'name' => 'Manager (Approver)',
                'slug' => 'manager-approver',
                'description' => 'Can write, read department data, approve, and manage users',
                'can_write' => true,
                'can_read_own' => true,
                'can_read_division' => true,
                'can_read_department' => true,
                'can_read_all' => false,
                'can_approve' => true,
                'can_manage_users' => true,
                'is_admin' => false,
                'is_hcs_print' => false,
            ],

            // 7. Director - Write, read all, approve, manage users
            [
                'name' => 'Director',
                'slug' => 'director',
                'description' => 'Can write, read all data, approve, and manage users',
                'can_write' => true,
                'can_read_own' => true,
                'can_read_division' => true,
                'can_read_department' => true,
                'can_read_all' => true,
                'can_approve' => true,
                'can_manage_users' => true,
                'is_admin' => false,
                'is_hcs_print' => false,
            ],

            // 8. HCS Print - Special role for printing
            [
                'name' => 'HCS Print',
                'slug' => 'hcs-print',
                'description' => 'Special role for HCS printing access',
                'can_write' => false,
                'can_read_own' => false,
                'can_read_division' => false,
                'can_read_department' => false,
                'can_read_all' => true,
                'can_approve' => false,
                'can_manage_users' => false,
                'is_admin' => false,
                'is_hcs_print' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('✅ Roles seeded successfully!');
        $this->command->info('Total roles created: ' . Role::count());
        
        // Display roles table
        $this->command->newLine();
        $this->command->table(
            ['ID', 'Name', 'Slug', 'Write', 'Read', 'Approve', 'Manage Users'],
            Role::all()->map(function ($role) {
                $readScope = $role->can_read_all ? 'All' : 
                            ($role->can_read_department ? 'Dept' : 
                            ($role->can_read_division ? 'Div' : 
                            ($role->can_read_own ? 'Own' : 'None')));
                
                return [
                    $role->id,
                    $role->name,
                    $role->slug,
                    $role->can_write ? '✓' : '✗',
                    $readScope,
                    $role->can_approve ? '✓' : '✗',
                    $role->can_manage_users ? '✓' : '✗',
                ];
            })->toArray()
        );
    }
}
