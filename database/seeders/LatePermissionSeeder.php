<?php

namespace Database\Seeders;

use App\Models\LatePermission;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LatePermissionSeeder extends Seeder
{
    public function run()
    {
        $employees = Employee::where('status', 'active')->inRandomOrder()->take(10)->get();

        foreach ($employees as $employee) {
            // Create 2 late permissions in the last month
            for ($i = 0; $i < 2; $i++) {
                $date = Carbon::today()->subDays(rand(1, 25));
                
                // Skip if exists
                if (LatePermission::where('employee_id', $employee->id)->whereDate('late_date', $date)->exists()) {
                    continue;
                }

                $approval = (bool)rand(0, 1);
                $hcs = (bool)rand(0, 1);

                LatePermission::create([
                    'employee_id' => $employee->id,
                    'late_date' => $date,
                    'late_duration' => '00:' . rand(10, 59) . ':00',
                    'arrival_time' => '08:' . rand(10, 59) . ':00',
                    'reason' => 'Ban bocor di jalan ' . rand(1, 10),
                    'approved_by_supervisor' => $approval,
                    'acknowledged_by_hcs' => $hcs,
                ]);
            }
        }
    }
}
