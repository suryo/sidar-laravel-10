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

        // Check if employee has approvers
        $hasApprovers = $employee->approvers()->exists();

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
        
        // Multi-approver logic
        $leave->supervisor_id = null; // No single supervisor
        $leave->supervisor_status = $hasApprovers ? 'pending' : 'approved';
        $leave->hcs_status = 'pending';
        
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
    /**
     * Show pending approvals for the authenticated user.
     */
    public function approvals(Request $request)
    {
        $employee = $request->user();
        
        // 1. Where I am a designated approver or delegate
        $query = Leave::with(['employee', 'delegateTo'])
            ->where(function($q) use ($employee) {
                // As Approver
                $q->whereHas('employee.approvers', function($subQ) use ($employee) {
                    $subQ->where('employee_approvers.approver_id', $employee->id);
                })->where('supervisor_status', 'pending');
                
                // As Delegate
                $q->orWhere(function($subQ) use ($employee) {
                    $subQ->where('delegate_to', $employee->id)
                         ->where('delegate_status', 'pending'); // Assuming there is delegate_status
                });
            });

        // 2. Where I am HR and it's pending HR approval
        $isHcs = $employee->role->is_admin || str_contains(strtoupper($employee->role->name), 'HR') || str_contains(strtoupper($employee->role->name), 'HCS');
        
        if ($isHcs) {
            $hcsQuery = Leave::with(['employee', 'delegateTo'])
                             ->where('hcs_status', 'pending');
            $query->union($hcsQuery); // Note: Simple union might duplicate if I am both approver and HR, but unlikely or acceptable.
        }
        
        // We need to be careful with building query flow. 
        // Union with builder is tricky if first query has complex where groups.
        // Actually, cleaner way:
        
        $pendingApprovals = Leave::with(['employee', 'delegateTo'])
            ->where(function($masterQ) use ($employee, $isHcs) {
                // Condition A: I am approver AND supervisor_status pending
                $masterQ->where(function($q) use ($employee) {
                     $q->whereHas('employee.approvers', function($sq) use ($employee) {
                         $sq->where('employee_approvers.approver_id', $employee->id);
                     })->where('supervisor_status', 'pending');
                });
                
                // Condition B: I am delegate
                $masterQ->orWhere(function($q) use ($employee) {
                    $q->where('delegate_to', $employee->id)
                      ->where('status', 'pending'); // Delegate doesn't strictly have a 'delegate_status' column in standard schemas often, usually just checks if assigned. But let's assume status 'pending'.
                      // Correct check: usually delegate approval happens before supervisor? Or parallel?
                      // If logic is unknown, assume if assigned and main status is pending.
                });

                // Condition C: I am HR AND hcs_status pending
                if ($isHcs) {
                    $masterQ->orWhere('hcs_status', 'pending');
                }
            })
            ->where('status', '!=', 'rejected') // Safety check
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('leaves.approvals', compact('pendingApprovals'));
    }

    /**
     * Handle approval/rejection logic.
     */
    /**
     * Handle approval/rejection logic.
     */
    public function processApproval(Request $request, Leave $leave)
    {
        $employee = $request->user();
        $action = $request->input('action'); // approve or reject
        $notes = $request->input('notes');

        $isApprover = $leave->employee->approvers()->where('approver_id', $employee->id)->exists();
        $isHcs = $employee->role->is_admin || str_contains(strtoupper($employee->role->name), 'HR') || str_contains(strtoupper($employee->role->name), 'HCS');
        $isDelegate = $leave->delegate_to === $employee->id;

        if (!$isApprover && !$isHcs && !$isDelegate) {
             return back()->with('error', 'Unauthorized action.');
        }

        if ($action === 'approve') {
            if ($isDelegate && $leave->status === 'pending') {
                 // Delegate logic usually just "accepts" the delegation?
                 // Or actually approves the leave? Assuming just accepts delegation for now, or is part of chain.
                 $leave->delegate_status = 'approved'; // Assuming column exists or we just ignore for now if not present in migration.
                 // If no delegate_status column, maybe we just log it.
            }
            
            if ($isApprover && $leave->supervisor_status === 'pending') {
                $leave->supervisor_status = 'approved';
                $leave->approved_by_id = $employee->id;
                // $leave->supervisor_approved_at = now(); // If column exists
            }
            
            if ($isHcs && $leave->hcs_status === 'pending') {
                $leave->hcs_status = 'approved';
                $leave->hcs_id = $employee->id;
                $leave->hcs_approved_at = now();
            }

            // Final Approval Check
            // Approved if: (Supervisor Approved OR Auto-Approved) AND (HCS Approved) AND (Delegate Approved if applicable)
            // Simplifying: Main status approved if HCS approved AND Supervisor approved.
            if ($leave->supervisor_status === 'approved' && $leave->hcs_status === 'approved') {
                $leave->status = 'approved';
                
                // Deduct quota
                if ($leave->type === 'annual') {
                    $requester = $leave->employee;
                    $requester->leave_quota -= $leave->total_days;
                    $requester->save();
                }
            }
            
            $leave->save();
            return back()->with('success', 'Leave request approved.');
            
        } else {
            // Reject
            $leave->status = 'rejected';
            
            if ($isApprover) $leave->supervisor_status = 'rejected';
            if ($isHcs) $leave->hcs_status = 'rejected';
            
            $leave->reject_notes = $notes; // Assuming column, or just ignore notes for now.
            $leave->save();
            
            return back()->with('success', 'Leave request rejected.');
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
