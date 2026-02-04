<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Dar;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Claim;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display reporting dashboard.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Employee Summary Report:
     * Aggregates counts of DARs, Attendance, Leaves, Claims for each employee.
     */
    public function employeeSummary(Request $request)
    {
        // Default to current month
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $departmentId = $request->input('department_id');

        $query = Employee::query()->where('status', 'active');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        // Eager load counts with filters
        $employees = $query->withCount([
            'dars' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('dar_date', [$startDate, $endDate]);
            },
            'attendances' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            },
            'leaves' => function ($q) use ($startDate, $endDate) {
                $q->where('status', 'approved')
                  ->whereBetween('start_date', [$startDate, $endDate]);
            },
            'claims' => function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['approved_supervisor', 'approved_hcs', 'paid'])
                  ->whereBetween('created_at', [$startDate, $endDate]);
            }
        ])->orderBy('name')->get();

        return view('reports.employee_summary', compact('employees', 'startDate', 'endDate'));
    }

    /**
     * Gap Analysis Report:
     * Identifies days where an employee attended (Checked In) but did NOT submit a DAR.
     */
    public function gapAnalysis(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $employeeId = $request->input('employee_id');

        // Find attendances within range
        $attendancesQuery = Attendance::with('employee')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('check_in');

        if ($employeeId) {
            $attendancesQuery->where('employee_id', $employeeId);
        }

        $attendances = $attendancesQuery->get();
        $gaps = [];

        // Fetch holidays in range for quick lookup
        $holidays = \App\Models\Holiday::whereBetween('date', [$startDate, $endDate])->pluck('date')->toArray();

        foreach ($attendances as $attendance) {
            // Check if date is a holiday
            if (in_array($attendance->date->format('Y-m-d'), $holidays)) {
                continue; // Skip holidays for gap analysis
            }

            // Check if DAR exists for this employee on this date
            $hasDar = Dar::where('employee_id', $attendance->employee_id)
                         ->where('dar_date', $attendance->date)
                         ->exists();

            if (!$hasDar) {
                $gaps[] = $attendance;
            }
        }

        // Get employees for filter dropdown
        $employees = Employee::orderBy('name')->pluck('name', 'id');

        return view('reports.gap_analysis', compact('gaps', 'startDate', 'endDate', 'employees', 'employeeId'));
    }

    /**
     * Out of Town Attendance Report (Luar Kota)
     */
    public function outOfTown(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $search = $request->input('search');
        $type = $request->input('type', 'in'); // 'in' or 'out'

        $query = Attendance::with(['employee.department', 'employee.division'])
                           ->where('work_type', 'outside') // or whatever work_type is used for out of town
                           ->whereBetween('attendance_date', [$startDate, $endDate]);

        if ($search) {
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('department', function($d) use ($search) {
                      $d->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('division', function($v) use ($search) {
                      $v->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Order by check in time descending
        $query->orderBy('check_in_time', 'desc');

        $attendances = $query->paginate(15);
        
        // Append query parameters to pagination links
        $attendances->appends($request->all());

        return view('reports.out_of_town', compact('attendances', 'startDate', 'endDate', 'type', 'search'));
    }
}
