<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Division;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Get role ID based on employee level
     */
    private function getRoleId(string $level, bool $isApprover = true): int
    {
        $roleSlug = match($level) {
            'owner' => 'administrator',
            'director' => 'director',
            'senior_manager' => $isApprover ? 'manager-approver' : 'manager',
            'manager' => $isApprover ? 'manager-approver' : 'manager',
            'supervisor' => $isApprover ? 'supervisor-approver' : 'supervisor',
            'staff' => 'staff',
            default => 'staff',
        };
        
        return Role::where('slug', $roleSlug)->first()->id;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get references
        $itDept = Department::where('code', 'IT')->first();
        $hrDept = Department::where('code', 'HR')->first();
        $finDept = Department::where('code', 'FIN')->first();
        $salesDept = Department::where('code', 'SALES')->first();
        
        $itDevDiv = Division::where('code', 'IT-DEV')->first();
        $hrRecDiv = Division::where('code', 'HR-REC')->first();
        $finAccDiv = Division::where('code', 'FIN-ACC')->first();
        $salesB2BDiv = Division::where('code', 'SAL-B2B')->first();
        
        $jktHO = Location::where('code', 'JKT-HO')->first();
        $bdgBr = Location::where('code', 'BDG-BR')->first();

        // ============================================
        // OWNER LEVEL
        // ============================================
        $owner = Employee::create([
            'nik' => 'EMP-0001',
            'name' => 'Budi Santoso',
            'email' => 'owner@sidar.test',
            'password' => 'password',
            'phone' => '081234567890',
            'department_id' => $itDept?->id,
            'division_id' => null,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'Owner',
            'level' => 'owner',
            'role_id' => $this->getRoleId('owner'),
            'supervisor_id' => null,
            'manager_id' => null,
            'senior_manager_id' => null,
            'director_id' => null,
            'owner_id' => null,
            'leave_quota' => 12,
            'leave_group' => 'EXECUTIVE',
            'max_hours' => 8.00,
            'can_attend_outside' => true,
            'status' => 'active',
            'join_date' => '2020-01-01',
        ]);

        // ============================================
        // DIRECTOR LEVEL
        // ============================================
        $director = Employee::create([
            'nik' => 'EMP-0002',
            'name' => 'Siti Nurhaliza',
            'email' => 'director@sidar.test',
            'password' => 'password',
            'phone' => '081234567891',
            'department_id' => $itDept?->id,
            'division_id' => null,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'Director',
            'level' => 'director',
            'role_id' => $this->getRoleId('director'),
            'supervisor_id' => null,
            'manager_id' => null,
            'senior_manager_id' => null,
            'director_id' => null,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'EXECUTIVE',
            'max_hours' => 8.00,
            'can_attend_outside' => true,
            'status' => 'active',
            'join_date' => '2020-02-01',
        ]);

        // ============================================
        // MANAGER LEVEL
        // ============================================
        $itManager = Employee::create([
            'nik' => 'EMP-1001',
            'name' => 'Ahmad Hidayat',
            'email' => 'it.manager@sidar.test',
            'password' => 'password',
            'phone' => '081234567892',
            'department_id' => $itDept?->id,
            'division_id' => $itDevDiv?->id,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'IT Manager',
            'level' => 'manager',
            'role_id' => $this->getRoleId('manager'),
            'supervisor_id' => null,
            'manager_id' => null,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'MANAGER',
            'max_hours' => 8.00,
            'can_attend_outside' => true,
            'status' => 'active',
            'join_date' => '2020-03-01',
        ]);

        $hrManager = Employee::create([
            'nik' => 'EMP-2001',
            'name' => 'Dewi Lestari',
            'email' => 'hr.manager@sidar.test',
            'password' => 'password',
            'phone' => '081234567893',
            'department_id' => $hrDept?->id,
            'division_id' => $hrRecDiv?->id,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'HR Manager',
            'level' => 'manager',
            'role_id' => $this->getRoleId('manager'),
            'supervisor_id' => null,
            'manager_id' => null,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'MANAGER',
            'max_hours' => 8.00,
            'can_attend_outside' => true,
            'status' => 'active',
            'join_date' => '2020-03-15',
        ]);

        $financeManager = Employee::create([
            'nik' => 'EMP-3001',
            'name' => 'Eko Prasetyo',
            'email' => 'finance.manager@sidar.test',
            'password' => 'password',
            'phone' => '081234567894',
            'department_id' => $finDept?->id,
            'division_id' => $finAccDiv?->id,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'Finance Manager',
            'level' => 'manager',
            'role_id' => $this->getRoleId('manager'),
            'supervisor_id' => null,
            'manager_id' => null,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'MANAGER',
            'max_hours' => 8.00,
            'can_attend_outside' => true,
            'status' => 'active',
            'join_date' => '2020-04-01',
        ]);

        // ============================================
        // SUPERVISOR LEVEL
        // ============================================
        $itSupervisor = Employee::create([
            'nik' => 'EMP-1101',
            'name' => 'Rudi Hartono',
            'email' => 'it.supervisor@sidar.test',
            'password' => 'password',
            'phone' => '081234567895',
            'department_id' => $itDept?->id,
            'division_id' => $itDevDiv?->id,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'IT Supervisor',
            'level' => 'supervisor',
            'role_id' => $this->getRoleId('supervisor'),
            'supervisor_id' => null,
            'manager_id' => $itManager->id,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'SUPERVISOR',
            'max_hours' => 8.00,
            'can_attend_outside' => true,
            'status' => 'active',
            'join_date' => '2021-01-15',
        ]);

        $hrSupervisor = Employee::create([
            'nik' => 'EMP-2101',
            'name' => 'Linda Wijaya',
            'email' => 'hr.supervisor@sidar.test',
            'password' => 'password',
            'phone' => '081234567896',
            'department_id' => $hrDept?->id,
            'division_id' => $hrRecDiv?->id,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'HR Supervisor',
            'level' => 'supervisor',
            'role_id' => $this->getRoleId('supervisor'),
            'supervisor_id' => null,
            'manager_id' => $hrManager->id,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'SUPERVISOR',
            'max_hours' => 8.00,
            'can_attend_outside' => true,
            'status' => 'active',
            'join_date' => '2021-02-01',
        ]);

        // ============================================
        // STAFF LEVEL
        // ============================================
        
        // IT Staff
        Employee::create([
            'nik' => 'EMP-1201',
            'name' => 'Andi Wijaya',
            'email' => 'andi.wijaya@sidar.test',
            'password' => 'password',
            'phone' => '081234567897',
            'department_id' => $itDept?->id,
            'division_id' => $itDevDiv?->id,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'Software Engineer',
            'level' => 'staff',
            'role_id' => $this->getRoleId('staff'),
            'supervisor_id' => $itSupervisor->id,
            'manager_id' => $itManager->id,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'STAFF',
            'max_hours' => 8.00,
            'can_attend_outside' => false,
            'status' => 'active',
            'join_date' => '2022-01-10',
        ]);

        Employee::create([
            'nik' => 'EMP-1202',
            'name' => 'Budi Setiawan',
            'email' => 'budi.setiawan@sidar.test',
            'password' => 'password',
            'phone' => '081234567898',
            'department_id' => $itDept?->id,
            'division_id' => $itDevDiv?->id,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'Frontend Developer',
            'level' => 'staff',
            'role_id' => $this->getRoleId('staff'),
            'supervisor_id' => $itSupervisor->id,
            'manager_id' => $itManager->id,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'STAFF',
            'max_hours' => 8.00,
            'can_attend_outside' => false,
            'status' => 'active',
            'join_date' => '2022-02-15',
        ]);

        // HR Staff
        Employee::create([
            'nik' => 'EMP-2201',
            'name' => 'Citra Dewi',
            'email' => 'citra.dewi@sidar.test',
            'password' => 'password',
            'phone' => '081234567899',
            'department_id' => $hrDept?->id,
            'division_id' => $hrRecDiv?->id,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'HR Recruiter',
            'level' => 'staff',
            'role_id' => $this->getRoleId('staff'),
            'supervisor_id' => $hrSupervisor->id,
            'manager_id' => $hrManager->id,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'STAFF',
            'max_hours' => 8.00,
            'can_attend_outside' => false,
            'status' => 'active',
            'join_date' => '2022-03-01',
        ]);

        // Finance Staff
        Employee::create([
            'nik' => 'EMP-3201',
            'name' => 'Dian Pratama',
            'email' => 'dian.pratama@sidar.test',
            'password' => 'password',
            'phone' => '081234567900',
            'department_id' => $finDept?->id,
            'division_id' => $finAccDiv?->id,
            'location_id' => $jktHO?->id,
            'unit_usaha' => 'Head Office',
            'position' => 'Accountant',
            'level' => 'staff',
            'role_id' => $this->getRoleId('staff'),
            'supervisor_id' => null,
            'manager_id' => $financeManager->id,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'STAFF',
            'max_hours' => 8.00,
            'can_attend_outside' => false,
            'status' => 'active',
            'join_date' => '2022-04-01',
        ]);

        // Sales Staff (Bandung Branch)
        Employee::create([
            'nik' => 'EMP-4201',
            'name' => 'Eko Saputra',
            'email' => 'eko.saputra@sidar.test',
            'password' => 'password',
            'phone' => '081234567901',
            'department_id' => $salesDept?->id,
            'division_id' => $salesB2BDiv?->id,
            'location_id' => $bdgBr?->id,
            'unit_usaha' => 'Bandung Branch',
            'position' => 'Sales Representative',
            'level' => 'staff',
            'role_id' => $this->getRoleId('staff'),
            'supervisor_id' => null,
            'manager_id' => null,
            'senior_manager_id' => null,
            'director_id' => $director->id,
            'owner_id' => $owner->id,
            'leave_quota' => 12,
            'leave_group' => 'STAFF',
            'max_hours' => 8.00,
            'can_attend_outside' => true, // Sales can attend outside
            'status' => 'active',
            'join_date' => '2022-05-01',
        ]);

        $this->command->info('Employees seeded successfully!');
        $this->command->info('Total employees created: ' . Employee::count());
    }
}
