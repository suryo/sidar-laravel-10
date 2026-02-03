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
        
        return view('dashboard', compact('stats', 'recentDars'));
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
