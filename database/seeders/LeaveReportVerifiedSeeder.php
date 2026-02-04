<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LeaveReportVerifiedSeeder extends Seeder
{
    public function run()
    {
        $employees = Employee::where('status', 'active')->inRandomOrder()->take(5)->get();

        foreach ($employees as $employee) {
            // Find a random colleague for delegation
            $delegate = Employee::where('id', '!=', $employee->id)->inRandomOrder()->first();

            // Create 2 Annual Leaves
            for ($i = 0; $i < 2; $i++) {
                $start = Carbon::today()->subDays(rand(1, 60));
                $end = $start->copy()->addDays(rand(1, 3));
                
                // Skip if exists
                if (Leave::where('employee_id', $employee->id)->whereDate('start_date', $start)->exists()) {
                    continue;
                }

                $delegateStatus = ['pending', 'approved', 'rejected'][rand(0, 2)];
                $supervisorStatus = ['pending', 'approved', 'rejected'][rand(0, 2)];
                $hcsStatus = ['pending', 'approved', 'rejected'][rand(0, 2)];

                Leave::create([
                    'leave_number' => 'LV-' . $employee->nik . '-' . $start->format('Ymd'),
                    'employee_id' => $employee->id,
                    'type' => 'annual',
                    'start_date' => $start,
                    'end_date' => $end,
                    'total_days' => $start->diffInDays($end) + 1,
                    'reason' => 'Cuti tahunan ke ' . ['Bali', 'Bandung', 'Jogja', 'Malang'][rand(0, 3)],
                    'delegate_to' => $delegate ? $delegate->id : null,
                    'delegation_tasks' => 'Handover pekerjaan rutin',
                    'delegate_status' => $delegateStatus,
                    'supervisor_id' => $employee->supervisor_id, // Assuming supervisor exists, or null
                    'supervisor_status' => $supervisorStatus,
                    'hcs_id' => 1, // Dummy HCS ID
                    'hcs_status' => $hcsStatus,
                    'status' => ($delegateStatus == 'approved' && $supervisorStatus == 'approved' && $hcsStatus == 'approved') ? 'approved' : 'pending',
                ]);
            }
        }
    }
}
