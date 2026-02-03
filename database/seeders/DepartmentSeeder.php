<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'code' => 'IT',
                'name' => 'Information Technology',
                'description' => 'Handles all technology infrastructure and software development',
                'is_active' => true,
            ],
            [
                'code' => 'HR',
                'name' => 'Human Resources',
                'description' => 'Manages employee relations, recruitment, and HR policies',
                'is_active' => true,
            ],
            [
                'code' => 'FIN',
                'name' => 'Finance',
                'description' => 'Manages financial operations, accounting, and budgeting',
                'is_active' => true,
            ],
            [
                'code' => 'SALES',
                'name' => 'Sales & Marketing',
                'description' => 'Handles sales operations and marketing strategies',
                'is_active' => true,
            ],
            [
                'code' => 'OPS',
                'name' => 'Operations',
                'description' => 'Manages day-to-day business operations',
                'is_active' => true,
            ],
            [
                'code' => 'LOG',
                'name' => 'Logistics',
                'description' => 'Handles supply chain and logistics operations',
                'is_active' => true,
            ],
            [
                'code' => 'CS',
                'name' => 'Customer Service',
                'description' => 'Provides customer support and service',
                'is_active' => true,
            ],
            [
                'code' => 'PROD',
                'name' => 'Production',
                'description' => 'Manages production and manufacturing',
                'is_active' => true,
            ],
            [
                'code' => 'QC',
                'name' => 'Quality Control',
                'description' => 'Ensures product quality and standards',
                'is_active' => true,
            ],
            [
                'code' => 'RND',
                'name' => 'Research & Development',
                'description' => 'Conducts research and product development',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        $this->command->info('Departments seeded successfully!');
    }
}
