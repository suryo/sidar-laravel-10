<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $types = ['annual', 'sick', 'permission', 'late'];
        $statuses = ['approved', 'pending', 'rejected'];

        foreach ($employees as $employee) {
            // Create 3 random leave requests for each employee
            for ($i = 0; $i < 3; $i++) {
                $type = $types[array_rand($types)];
                $status = $statuses[array_rand($statuses)];
                
                $startDate = now()->subMonths(rand(1, 6))->addDays(rand(1, 30));
                $endDate = (clone $startDate)->addDays(rand(0, 3));
                
                $totalDays = $startDate->diffInDays($endDate) + 1;

                $leave = Leave::create([
                    'leave_number' => 'LR-' . $employee->nik . '-' . now()->timestamp . $i,
                    'employee_id' => $employee->id,
                    'type' => $type,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_days' => $totalDays,
                    'reason' => 'Sample ' . $type . ' leave reason for testing.',
                    'status' => $status,
                    'submitted_at' => $startDate->subDays(2),
                ]);

                // Mock approvals if status is approved/rejected
                if ($status !== 'pending') {
                    if ($employee->supervisor_id) {
                        $leave->supervisor_id = $employee->supervisor_id;
                        $leave->supervisor_status = $status;
                        $leave->supervisor_approved_at = now();
                    }
                    $leave->save();
                }
            }
        }
    }
}
