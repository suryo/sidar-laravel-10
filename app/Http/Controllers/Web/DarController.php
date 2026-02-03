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
    public function create()
    {
        return view('dars.create');
    }

    /**
     * Store a newly created DAR
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dar_date' => 'required|date|before_or_equal:today',
            'activity' => 'required|string|min:10|max:1000',
            'result' => 'required|string|min:10|max:1000',
            'plan' => 'required|string|min:10|max:1000',
            'tag' => 'nullable|string|max:100',
        ]);

        $employee = $request->user();

        DB::beginTransaction();
        try {
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
                'supervisor_id' => $employee->supervisor_id,
                'manager_id' => $employee->manager_id,
                'senior_manager_id' => $employee->senior_manager_id,
                'director_id' => $employee->director_id,
                'owner_id' => $employee->owner_id,
                'supervisor_status' => $employee->supervisor_id ? 'pending' : null,
                'manager_status' => $employee->manager_id ? 'pending' : null,
                'senior_manager_status' => $employee->senior_manager_id ? 'pending' : null,
                'director_status' => $employee->director_id ? 'pending' : null,
                'owner_status' => $employee->owner_id ? 'pending' : null,
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
        $query = Dar::with(['employee'])->where('status', 'pending');

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
            case 'director':
                $query->where('director_id', $employee->id)
                      ->where('director_status', 'pending');
                break;
            case 'owner':
                $query->where('owner_id', $employee->id)
                      ->where('owner_status', 'pending')
                      ->where('director_status', 'approved');
                break;
            default:
                $dars = collect();
                return view('dars.approvals', compact('dars'));
        }

        $dars = $query->orderBy('submitted_at', 'asc')->paginate(15);

        return view('dars.approvals', compact('dars'));
    }

    /**
     * Approve DAR
     */
    public function approve(Request $request, $id)
    {
        $dar = Dar::with('employee')->findOrFail($id);
        $employee = $request->user();

        $approverLevel = $this->getApproverLevel($employee, $dar);

        if (!$approverLevel) {
            return back()->with('error', 'Unauthorized to approve this DAR');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $dar->approveByLevel($approverLevel, $request->notes);
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

        $approverLevel = $this->getApproverLevel($employee, $dar);

        if (!$approverLevel) {
            return back()->with('error', 'Unauthorized to reject this DAR');
        }

        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $dar->rejectByLevel($approverLevel, $request->notes);
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

        // Approvers can view
        if (in_array($user->id, [
            $dar->supervisor_id,
            $dar->manager_id,
            $dar->senior_manager_id,
            $dar->director_id,
            $dar->owner_id
        ])) {
            return true;
        }

        return false;
    }

    private function canApprove($user, $dar): bool
    {
        if ($dar->status !== 'pending') {
            return false;
        }

        $approverLevel = $this->getApproverLevel($user, $dar);
        return $approverLevel !== null;
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
