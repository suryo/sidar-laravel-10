<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            // IT Department
            ['department' => 'IT', 'code' => 'IT-DEV', 'name' => 'Development', 'description' => 'Software development team'],
            ['department' => 'IT', 'code' => 'IT-INF', 'name' => 'Infrastructure', 'description' => 'IT infrastructure and network'],
            ['department' => 'IT', 'code' => 'IT-SUP', 'name' => 'Support', 'description' => 'IT support and helpdesk'],
            
            // HR Department
            ['department' => 'HR', 'code' => 'HR-REC', 'name' => 'Recruitment', 'description' => 'Talent acquisition'],
            ['department' => 'HR', 'code' => 'HR-TRN', 'name' => 'Training', 'description' => 'Employee training and development'],
            ['department' => 'HR', 'code' => 'HR-ADM', 'name' => 'Administration', 'description' => 'HR administration'],
            
            // Finance Department
            ['department' => 'FIN', 'code' => 'FIN-ACC', 'name' => 'Accounting', 'description' => 'Accounting operations'],
            ['department' => 'FIN', 'code' => 'FIN-TAX', 'name' => 'Tax', 'description' => 'Tax compliance'],
            ['department' => 'FIN', 'code' => 'FIN-AUD', 'name' => 'Audit', 'description' => 'Internal audit'],
            
            // Sales Department
            ['department' => 'SALES', 'code' => 'SAL-B2B', 'name' => 'B2B Sales', 'description' => 'Business to business sales'],
            ['department' => 'SALES', 'code' => 'SAL-B2C', 'name' => 'B2C Sales', 'description' => 'Business to consumer sales'],
            ['department' => 'SALES', 'code' => 'SAL-MKT', 'name' => 'Marketing', 'description' => 'Marketing and promotions'],
            
            // Operations Department
            ['department' => 'OPS', 'code' => 'OPS-GEN', 'name' => 'General Operations', 'description' => 'General operations'],
            ['department' => 'OPS', 'code' => 'OPS-FAC', 'name' => 'Facilities', 'description' => 'Facilities management'],
        ];

        foreach ($divisions as $divisionData) {
            $department = Department::where('code', $divisionData['department'])->first();
            
            if ($department) {
                Division::create([
                    'department_id' => $department->id,
                    'code' => $divisionData['code'],
                    'name' => $divisionData['name'],
                    'description' => $divisionData['description'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Divisions seeded successfully!');
    }
}
