<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Division;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get references for relationships
        $itDept = Department::where('code', 'IT')->first() ?? Department::first();
        $itDiv = Division::where('department_id', $itDept->id)->first() ?? Division::first();
        $jktHO = Location::where('code', 'JKT-HO')->first() ?? Location::first();

        // Template for essential employee data
        $baseData = [
            'password' => 'password', // Auto-hashed by model if using Attribute casting, but seeder usually needs Hash::make if not handled
            'unit_usaha' => 'Head Office',
            'department_id' => $itDept->id,
            'division_id' => $itDiv->id,
            'location_id' => $jktHO->id,
            'leave_quota' => 12,
            'status' => 'active',
            'join_date' => '2020-01-01',
            'max_hours' => 8.00,
        ];

        $demoUsers = [
            [
                'name' => 'Demo Administrator',
                'email' => 'admin@sidar.test',
                'nik' => 'DEMO-001',
                'role_slug' => 'administrator',
                'position' => 'System Administrator',
                'level' => 'owner',
            ],
            [
                'name' => 'Demo Director',
                'email' => 'director@sidar.test',
                'nik' => 'DEMO-002',
                'role_slug' => 'director',
                'position' => 'Technical Director',
                'level' => 'director',
            ],
            [
                'name' => 'Demo Manager Approver',
                'email' => 'manager.approver@sidar.test',
                'nik' => 'DEMO-003',
                'role_slug' => 'manager-approver',
                'position' => 'Regional Manager',
                'level' => 'manager',
            ],
            [
                'name' => 'Demo Manager',
                'email' => 'manager@sidar.test',
                'nik' => 'DEMO-004',
                'role_slug' => 'manager',
                'position' => 'Product Manager',
                'level' => 'manager',
            ],
            [
                'name' => 'Demo Supervisor Approver',
                'email' => 'supervisor.approver@sidar.test',
                'nik' => 'DEMO-005',
                'role_slug' => 'supervisor-approver',
                'position' => 'Team Lead',
                'level' => 'supervisor',
            ],
            [
                'name' => 'Demo Supervisor',
                'email' => 'supervisor@sidar.test',
                'nik' => 'DEMO-006',
                'role_slug' => 'supervisor',
                'position' => 'Field Supervisor',
                'level' => 'supervisor',
            ],
            [
                'name' => 'Demo Staff',
                'email' => 'staff@sidar.test',
                'nik' => 'DEMO-007',
                'role_slug' => 'staff',
                'position' => 'Developer',
                'level' => 'staff',
            ],
            [
                'name' => 'Demo HCS Print',
                'email' => 'hcs.print@sidar.test',
                'nik' => 'DEMO-008',
                'role_slug' => 'hcs-print',
                'position' => 'HCS Clerk',
                'level' => 'staff',
            ],
        ];

        foreach ($demoUsers as $userData) {
            $role = Role::where('slug', $userData['role_slug'])->first();
            
            if (!$role) continue;

            Employee::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($baseData, [
                    'name' => $userData['name'],
                    'nik' => $userData['nik'],
                    'position' => $userData['position'],
                    'level' => $userData['level'],
                    'role_id' => $role->id,
                    'phone' => '08' . rand(100000000, 999999999),
                    'password' => 'password', // Model Hash attribute handles this if configured
                ])
            );
        }

        $this->command->info('Demo users for all roles created successfully!');
    }
}
