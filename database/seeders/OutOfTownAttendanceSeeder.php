<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OutOfTownAttendanceSeeder extends Seeder
{
    public function run()
    {
        $employees = Employee::where('status', 'active')->inRandomOrder()->take(10)->get();

        foreach ($employees as $employee) {
            // Create 3 days of out of town attendance in the last week
            for ($i = 0; $i < 3; $i++) {
                $date = Carbon::today()->subDays($i + 1);
                
                // Skip if exists
                if (Attendance::where('employee_id', $employee->id)->whereDate('attendance_date', $date)->exists()) {
                    continue;
                }

                $checkIn = $date->copy()->setTime(8, rand(0, 59), 0);
                $checkOut = $date->copy()->setTime(17, rand(0, 59), 0);

                Attendance::create([
                    'attendance_number' => 'ATT-OUT-' . $employee->nik . '-' . $date->format('Ymd'),
                    'employee_id' => $employee->id,
                    'attendance_date' => $date,
                    'check_in_time' => $checkIn,
                    'check_in_latitude' => -6.2088 + (rand(-100, 100) / 10000),
                    'check_in_longitude' => 106.8456 + (rand(-100, 100) / 10000),
                    'check_in_address' => 'Jl. Luar Kota No. ' . rand(1, 100) . ', Semarang',
                    'check_in_city' => 'Semarang',
                    'check_in_at_distributor' => (bool)rand(0, 1),
                    'check_in_status' => 'ontime',
                    
                    'check_out_time' => $checkOut,
                    'check_out_latitude' => -6.2088 + (rand(-100, 100) / 10000),
                    'check_out_longitude' => 106.8456 + (rand(-100, 100) / 10000),
                    'check_out_address' => 'Jl. Luar Kota No. ' . rand(1, 100) . ', Semarang',
                    'check_out_city' => 'Semarang',
                    'check_out_at_distributor' => (bool)rand(0, 1),

                    'work_type' => 'outside',
                    'status' => 'present',
                ]);
            }
        }
    }
}
