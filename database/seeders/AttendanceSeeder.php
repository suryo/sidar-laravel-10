<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $startDate = Carbon::now()->subDays(10);
        
        foreach ($employees as $employee) {
            for ($i = 0; $i < 10; $i++) {
                $date = $startDate->copy()->addDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                $checkIn = $date->copy()->hour(rand(7, 10))->minute(rand(0, 59));
                $checkOut = $date->copy()->hour(rand(16, 18))->minute(rand(0, 59));
                
                Attendance::create([
                    'attendance_number' => 'ATT-' . $employee->nik . '-' . $date->format('Ymd'),
                    'employee_id' => $employee->id,
                    'attendance_date' => $date,
                    'check_in_time' => $checkIn->format('H:i:s'),
                    'check_out_time' => $checkOut->format('H:i:s'),
                    'work_type' => ['wfo', 'wfh', 'outside'][rand(0, 2)],
                    'status' => 'present',
                    'check_in_status' => $checkIn->hour < 9 ? 'ontime' : 'late',
                    'notes' => 'Sample seeded attendance',
                    'check_in_address' => 'Jl. HR Muhammad No. ' . rand(1, 100) . ', Surabaya',
                    'check_out_address' => 'Jl. HR Muhammad No. ' . rand(1, 100) . ', Surabaya',
                ]);
            }
        }
    }
}
