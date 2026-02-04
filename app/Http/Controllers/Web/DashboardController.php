<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dar;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index(Request $request)
    {
        $employee = $request->user();
        
        // Get statistics
        $stats = [
            'total_dars' => Dar::where('employee_id', $employee->id)->count(),
            'pending_dars' => Dar::where('employee_id', $employee->id)
                                ->where('status', 'pending')
                                ->count(),
            'approved_dars' => Dar::where('employee_id', $employee->id)
                                 ->where('status', 'approved')
                                 ->count(),
            'rejected_dars' => Dar::where('employee_id', $employee->id)
                                 ->where('status', 'rejected')
                                 ->count(),
        ];
        
        // Get pending approvals count (if approver)
        if ($employee->level !== 'staff') {
            $stats['pending_approvals'] = $this->getPendingApprovalsCount($employee);
        }
        
        // Get recent DARs
        $recentDars = Dar::where('employee_id', $employee->id)
                        ->with(['employee'])
                        ->orderBy('dar_date', 'desc')
                        ->limit(5)
                        ->get();

        // ----------------------------------------------------
        // Attendance Data for Legacy Dashboard Features
        // ----------------------------------------------------
        
        // 1. Today's Attendance (for Buttons)
        $todayAttendance = \App\Models\Attendance::where('employee_id', $employee->id)
                            ->where('attendance_date', now()->format('Y-m-d'))
                            ->first();

        // 2. Chart Data (Personal Stats)
        // Performa per hari (Last 30 days check-in times or status?) 
        // We will map this to counts of status for now or simple daily status
        $monthlyAttendance = \App\Models\Attendance::where('employee_id', $employee->id)
                            ->whereMonth('attendance_date', now()->month)
                            ->whereYear('attendance_date', now()->year)
                            ->get();

        $stats_monthly = [
            'ontime' => $monthlyAttendance->where('check_in_status', 'ontime')->count(),
            'late' => $monthlyAttendance->where('check_in_status', 'late')->count(),
            'absent' => $monthlyAttendance->whereIn('status', ['absent', 'leave', 'sick'])->count(), // Corrected statuses based on model scopes
        ];

        return view('dashboard', compact('stats', 'recentDars', 'todayAttendance', 'stats_monthly'));
    }
    
    /**
     * Get pending approvals count for approver
     */
    private function getPendingApprovalsCount($employee)
    {
        $query = Dar::where('status', 'pending');
        
        switch ($employee->level) {
            case 'supervisor':
                $query->where('supervisor_id', $employee->id)
                      ->where('supervisor_status', 'pending');
                break;
            case 'manager':
                $query->where('manager_id', $employee->id)
                      ->where('manager_status', 'pending')
                      ->where('supervisor_status', 'approved');
                break;
            case 'senior_manager':
                $query->where('senior_manager_id', $employee->id)
                      ->where('senior_manager_status', 'pending')
                      ->where('manager_status', 'approved');
                break;
            case 'director':
                $query->where('director_id', $employee->id)
                      ->where('director_status', 'pending');
                break;
            case 'owner':
                $query->where('owner_id', $employee->id)
                      ->where('owner_status', 'pending')
                      ->where('director_status', 'approved');
                break;
        }
        
        return $query->count();
    }
}
