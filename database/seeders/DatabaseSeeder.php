<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();

        // Seed in order (respecting foreign key constraints)
        $this->call([
            RoleSeeder::class, // Added RoleSeeder
            DepartmentSeeder::class,
            DivisionSeeder::class,
            LocationSeeder::class,
            EmployeeSeeder::class,
            AttendanceSeeder::class,
            DemoUserSeeder::class,
            LeaveSeeder::class,
            LetterTemplateSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        
        // Show summary
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Roles', \App\Models\Role::count()],
                ['Departments', \App\Models\Department::count()],
                ['Divisions', \App\Models\Division::count()],
                ['Locations', \App\Models\Location::count()],
                ['Employees', \App\Models\Employee::count()],
                ['Attendances', \App\Models\Attendance::count()],
                ['Leaves', \App\Models\Leave::count()],
            ]
        );
    }
}
