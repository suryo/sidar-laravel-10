<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Display a listing of personal leave requests.
     */
    public function index(Request $request)
    {
        $employee = $request->user();
        $leaves = Leave::where('employee_id', $employee->id)
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);
                       
        return view('leaves.index', compact('leaves', 'employee'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        // Get potential delegates (same department or team)
        $delegates = Employee::where('id', '!=', auth()->id())
                             ->where('status', 'active')
                             ->orderBy('name')
                             ->get();
                             
        return view('leaves.create', compact('delegates'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request)
    {
        $employee = $request->user();
        
        $request->validate([
            'type' => 'required|in:annual,sick,permission,late,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'delegate_to' => 'nullable|exists:employees,id',
            'delegation_tasks' => 'nullable|string|max:500',
            'late_arrival_time' => 'nullable|date_format:H:i',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        // Calculate Working Days (Excluding Weekends and Holidays)
        $totalDays = 0;
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        
        // Fetch holidays in range
        $holidays = \App\Models\Holiday::whereBetween('date', [$startDate, $endDate])->pluck('date')->toArray();

        foreach ($period as $date) {
            // Check if weekend (Sat/Sun) or Holiday
            if ($date->isWeekend() || in_array($date->format('Y-m-d'), $holidays)) {
                continue;
            }
            $totalDays++;
        }

        if ($totalDays <= 0) {
             return back()->with('error', 'The selected range has 0 working days.')->withInput();
        }

        // Check quota if type is annual
        if ($request->type === 'annual' && $employee->leave_quota < $totalDays) {
            return back()->with('error', "Your leave quota is insufficient ($employee->leave_quota days remaining).")->withInput();
        }

        $leave = new Leave();
        $leave->leave_number = 'LR-' . $employee->nik . '-' . now()->format('YmdHis');
        $leave->employee_id = $employee->id;
        $leave->type = $request->type;
        $leave->start_date = $startDate;
        $leave->end_date = $endDate;
        $leave->total_days = $totalDays;
        $leave->reason = $request->reason;
        $leave->delegate_to = $request->delegate_to;
        $leave->delegation_tasks = $request->delegation_tasks;
        $leave->late_arrival_time = $request->late_arrival_time;
        
        // Auto-assign supervisor from employee profile
        $leave->supervisor_id = $employee->supervisor_id;
        
        // HCS ID is usually a specific role/user, for now we set null or a specific ID
        // In a real app, logic would find the HCS person
        
        $leave->status = 'pending';
        $leave->submitted_at = now();
        $leave->save();

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified leave request.
     */
    public function show(Leave $leave)
    {
        $this->authorizeAccess($leave);
        return view('leaves.show', compact('leave'));
    }

    /**
     * Show pending approvals for the authenticated user.
     */
    public function approvals(Request $request)
    {
        $employee = $request->user();
        
        $query = Leave::with(['employee', 'delegateTo'])
                      ->where(function($q) use ($employee) {
                          $q->where('supervisor_id', $employee->id)
                            ->orWhere('delegate_to', $employee->id)
                            ->orWhere('hcs_id', $employee->id);
                      })
                      ->where('status', 'pending');

        $pendingApprovals = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('leaves.approvals', compact('pendingApprovals'));
    }

    /**
     * Handle approval/rejection logic.
     */
    public function processApproval(Request $request, Leave $leave)
    {
        $employee = $request->user();
        $action = $request->input('action'); // approve or reject
        $notes = $request->input('notes');

        if ($action === 'approve') {
            if ($leave->delegate_to === $employee->id && $leave->delegate_status === 'pending') {
                $leave->approveDelegation();
            } elseif ($leave->supervisor_id === $employee->id && $leave->supervisor_status === 'pending') {
                $leave->approveBySupervisor($notes);
            } elseif ($leave->hcs_id === $employee->id && $leave->hcs_status === 'pending') {
                $leave->approveByHcs($notes);
            } else {
                return back()->with('error', 'You are not authorized to approve this request or it is already processed.');
            }

            // If fully approved, deduct quota
            if ($leave->status === 'approved' && $leave->type === 'annual') {
                $requester = $leave->employee;
                $requester->leave_quota -= $leave->total_days;
                $requester->save();
            }

            return back()->with('success', 'Leave request approved.');
        } else {
            if ($leave->delegate_to === $employee->id || $leave->supervisor_id === $employee->id || $leave->hcs_id === $employee->id) {
                $leave->reject($employee, $notes);
                return back()->with('success', 'Leave request rejected.');
            }
            return back()->with('error', 'Unauthorized action.');
        }
    }

    private function authorizeAccess(Leave $leave)
    {
        $user = auth()->user();
        if ($leave->employee_id !== $user->id && 
            $leave->supervisor_id !== $user->id && 
            $leave->delegate_to !== $user->id &&
            !$user->role->is_admin) {
            abort(403);
        }
    }
}
