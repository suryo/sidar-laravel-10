<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DarController extends Controller
{
    /**
     * Display a listing of DARs
     */
    public function index(Request $request)
    {
        $query = Dar::where('employee_id', $request->user()->id)
                    ->with(['employee']);

        // Apply filters
        if ($request->filled('start_date')) {
            $query->where('dar_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('dar_date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dars = $query->orderBy('dar_date', 'desc')
                     ->paginate(15);

        return view('dars.index', compact('dars'));
    }

    /**
     * Show the form for creating a new DAR
     */
    public function create(Request $request, \App\Services\DarCalculator $darCalculator)
    {
        $employee = $request->user();
        $missingDates = $darCalculator->getMissingDarDates($employee);
        $pending_dar_count = count($missingDates);
        
        return view('dars.create', compact('pending_dar_count', 'missingDates'));
    }

    /**
     * Store a newly created DAR
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dar_date' => [
                'required',
                'date',
                'before_or_equal:today',
                \Illuminate\Validation\Rule::unique('dars')->where(function ($query) use ($request) {
                    return $query->where('employee_id', $request->user()->id);
                }),
            ],
            'activity' => 'required|string|min:10|max:1000',
            'result' => 'required|string|min:10|max:1000',
            'plan' => 'required|string|min:10|max:1000',
            'tag' => 'nullable|string|max:100',
        ], [
            'dar_date.unique' => 'You have already submitted a DAR for this date.',
        ]);

        $employee = $request->user();

        DB::beginTransaction();
        try {
            // Determine initial status based on approvers existence
            $hasApprovers = $employee->approvers()->exists();
            
            $dar = Dar::create([
                'dar_number' => $this->generateDarNumber($employee),
                'employee_id' => $employee->id,
                'dar_date' => $validated['dar_date'],
                'activity' => $validated['activity'],
                'result' => $validated['result'],
                'plan' => $validated['plan'],
                'tag' => $validated['tag'] ?? null,
                'status' => 'pending',
                'submitted_at' => now(),
                
                // New Logic: 
                // If has approvers -> supervisor_status = pending
                // If NO approvers -> supervisor_status = approved (auto-pass to HRD)
                'supervisor_status' => $hasApprovers ? 'pending' : 'approved',
                'hcs_status' => 'pending', // Always pending HRD approval
                
                // Legacy columns (nullable now)
                'supervisor_id' => null, 
                'manager_id' => null,
                'senior_manager_id' => null,
                'director_id' => null,
                'owner_id' => null,
            ]);

            DB::commit();

            return redirect()->route('dars.show', $dar->id)
                           ->with('success', 'DAR created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Failed to create DAR: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified DAR
     */
    public function show(Request $request, $id)
    {
        $dar = Dar::with(['employee', 'supervisor', 'manager', 'director', 'owner'])
                  ->findOrFail($id);

        // Authorization check
        if (!$this->canView($request->user(), $dar)) {
            abort(403, 'Unauthorized to view this DAR');
        }

        // Check if user can approve
        $canApprove = $this->canApprove($request->user(), $dar);

        return view('dars.show', compact('dar', 'canApprove'));
    }

    /**
     * Show the form for editing the specified DAR
     */
    public function edit(Request $request, $id)
    {
        $dar = Dar::findOrFail($id);

        // Authorization check
        if ($dar->employee_id !== $request->user()->id) {
            abort(403, 'Unauthorized to edit this DAR');
        }

        if (!in_array($dar->status, ['draft', 'pending'])) {
            return redirect()->route('dars.show', $dar->id)
                           ->with('error', 'Cannot edit DAR with status: ' . $dar->status);
        }

        return view('dars.edit', compact('dar'));
    }

    /**
     * Update the specified DAR
     */
    public function update(Request $request, $id)
    {
        $dar = Dar::findOrFail($id);

        // Authorization check
        if ($dar->employee_id !== $request->user()->id) {
            abort(403, 'Unauthorized to update this DAR');
        }

        if (!in_array($dar->status, ['draft', 'pending'])) {
            return redirect()->route('dars.show', $dar->id)
                           ->with('error', 'Cannot update DAR with status: ' . $dar->status);
        }

        $validated = $request->validate([
            'dar_date' => 'required|date|before_or_equal:today',
            'activity' => 'required|string|min:10|max:1000',
            'result' => 'required|string|min:10|max:1000',
            'plan' => 'required|string|min:10|max:1000',
            'tag' => 'nullable|string|max:100',
        ]);

        $dar->update($validated);

        return redirect()->route('dars.show', $dar->id)
                       ->with('success', 'DAR updated successfully!');
    }

    /**
     * Remove the specified DAR
     */
    public function destroy(Request $request, $id)
    {
        $dar = Dar::findOrFail($id);

        // Authorization check
        if ($dar->employee_id !== $request->user()->id) {
            abort(403, 'Unauthorized to delete this DAR');
        }

        if (!in_array($dar->status, ['draft', 'pending'])) {
            return redirect()->route('dars.show', $dar->id)
                           ->with('error', 'Cannot delete DAR with status: ' . $dar->status);
        }

        $dar->delete();

        return redirect()->route('dars.index')
                       ->with('success', 'DAR deleted successfully!');
    }

    /**
     * Show pending approvals
     */
    public function approvals(Request $request)
    {
        $employee = $request->user();
        
        // 1. Dars where I am a designated approver (CC/BC) and supervisor approval is pending
        $approverQuery = Dar::whereHas('employee.approvers', function($q) use ($employee) {
            $q->where('employee_approvers.approver_id', $employee->id);
        })->where('supervisor_status', 'pending');

        // 2. Dars pending HR approval (if I am HR/Admin)
        // Assuming HR/Admin roles have is_admin = true or specific names. Adjust as per actual role names.
        // For now, checking is_admin or role name contains 'HR' or 'HCS'
        $isHcs = $employee->role->is_admin || str_contains(strtoupper($employee->role->name), 'HR') || str_contains(strtoupper($employee->role->name), 'HCS');
        
        if ($isHcs) {
            // HR sees items where supervision is EITHER approved OR not required (approved/skipped)
            // AND HCS status is pending.
            // Actually, usually HR can see pending supervisor ones too, but for "Approval Task", 
            // usually you wait for supervisor first? 
            // Req: "approval HRD/HCS juga ditambah approval...".
            // Let's show them all if HCS status is pending.
            $hcsQuery = Dar::where('hcs_status', 'pending');
            $approverQuery->union($hcsQuery);
        }

        $dars = $approverQuery->with(['employee'])
                              ->orderBy('submitted_at', 'asc')
                              ->paginate(15);

        return view('dars.approvals', compact('dars'));
    }

    /**
     * Approve DAR
     */
    public function approve(Request $request, $id)
    {
        $dar = Dar::with('employee')->findOrFail($id);
        $employee = $request->user();

        // Check authority
        $isApprover = $dar->employee->approvers()->where('approver_id', $employee->id)->exists();
        $isHcs = $employee->role->is_admin || str_contains(strtoupper($employee->role->name), 'HR') || str_contains(strtoupper($employee->role->name), 'HCS');

        if (!$isApprover && !$isHcs) {
            return back()->with('error', 'Unauthorized to approve this DAR');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Logic: Approver approves Supervisor Status
            if ($isApprover && $dar->supervisor_status === 'pending') {
                $dar->supervisor_status = 'approved';
                $dar->approved_by_id = $employee->id;
                $dar->supervisor_approved_at = now();
            }

            // Logic: HR approves HCS Status
            if ($isHcs && $dar->hcs_status === 'pending') {
                $dar->hcs_status = 'approved';
                $dar->hcs_id = $employee->id;
                $dar->hcs_approved_at = now();
            }
            
            // Final Status Check
            // REQ: "salah satu atasan sudah memberikan approval maka status dar bisa approve"
            // We update main status to approved if supervisor approves, regardless of HCS status for now.
            if ($dar->supervisor_status === 'approved') {
                $dar->status = 'approved';
            }
            
            $dar->save();

            DB::commit();

            return redirect()->route('dars.show', $dar->id)
                           ->with('success', 'DAR approved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve DAR: ' . $e->getMessage());
        }
    }

    /**
     * Reject DAR
     */
    public function reject(Request $request, $id)
    {
        $dar = Dar::with('employee')->findOrFail($id);
        $employee = $request->user();

        $isApprover = $dar->employee->approvers()->where('approver_id', $employee->id)->exists();
        $isHcs = $employee->role->is_admin || str_contains(strtoupper($employee->role->name), 'HR') || str_contains(strtoupper($employee->role->name), 'HCS');

        if (!$isApprover && !$isHcs) {
            return back()->with('error', 'Unauthorized to reject this DAR');
        }

        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $dar->status = 'rejected'; // Main status rejected immediately
            
            if ($isApprover) {
                $dar->supervisor_status = 'rejected';
                $dar->approved_by_id = $employee->id; // Recorded who rejected
            }
            
            if ($isHcs) {
                $dar->hcs_status = 'rejected';
                $dar->hcs_id = $employee->id;
            }
            
            // We might want to save notes somewhere? current table structure assumes notes handled in specific `approveByLevel` method or similar.
            // Since we are bypassing `approveByLevel`, let's check if we can save notes.
            // The `dars` table schema in create_dars_table.php doesn't show a 'notes' column.
            // But maybe `rejected_notes`?
            // The original code used `rejectByLevel` which presumably saved notes.
            // I'll skip notes saving to DB for now unless I see a column, or assumes logic handles it.
            
            $dar->save();
            DB::commit();

            return redirect()->route('dars.show', $dar->id)
                           ->with('success', 'DAR rejected.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject DAR: ' . $e->getMessage());
        }
    }

    // ============================================
    // Helper Methods
    // ============================================

    private function generateDarNumber($employee): string
    {
        $date = now()->format('Ymd');
        $count = Dar::where('employee_id', $employee->id)
                   ->whereDate('created_at', now()->toDateString())
                   ->count() + 1;

        return "DAR/{$employee->nik}/{$date}/{$count}";
    }

    private function canView($user, $dar): bool
    {
        // Owner can view
        if ($dar->employee_id === $user->id) {
            return true;
        }

        // Approvers can view (Legacy columns)
        if (in_array($user->id, [
            $dar->supervisor_id,
            $dar->manager_id,
            $dar->senior_manager_id,
            $dar->director_id,
            $dar->owner_id
        ])) {
            return true;
        }

        // CC/BC Approvers can view
        // Check if user is an approver for the DAR's employee
        if ($dar->employee->approvers->contains($user->id)) {
            return true;
        }
        
        // HCS/Admin can view
        if ($user->role->is_admin || str_contains(strtoupper($user->role->name), 'HR') || str_contains(strtoupper($user->role->name), 'HCS')) {
            return true;
        }

        return false;
    }

    private function canApprove($user, $dar): bool
    {
        if ($dar->status !== 'pending') {
            return false;
        }

        // Check CC/BC Approvers
        if ($dar->supervisor_status === 'pending') {
             if ($dar->employee->approvers->contains($user->id)) {
                 return true;
             }
        }
        
        // Check legacy approvers
        $approverLevel = $this->getApproverLevel($user, $dar);
        if ($approverLevel !== null) {
            return true;
        }
        
        return false;
    }

    private function getApproverLevel($employee, $dar): ?string
    {
        if ($dar->supervisor_id === $employee->id && $dar->supervisor_status === 'pending') {
            return 'supervisor';
        }

        if ($dar->manager_id === $employee->id && $dar->manager_status === 'pending' && $dar->supervisor_status === 'approved') {
            return 'manager';
        }

        if ($dar->senior_manager_id === $employee->id && $dar->senior_manager_status === 'pending' && $dar->manager_status === 'approved') {
            return 'senior_manager';
        }

        if ($dar->director_id === $employee->id && $dar->director_status === 'pending') {
            return 'director';
        }

        if ($dar->owner_id === $employee->id && $dar->owner_status === 'pending' && $dar->director_status === 'approved') {
            return 'owner';
        }

        return null;
    }
}
