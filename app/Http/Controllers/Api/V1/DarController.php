<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreDarRequest;
use App\Http\Requests\Api\V1\UpdateDarRequest;
use App\Http\Resources\Api\V1\DarResource;
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
        $employee = $request->user();
        
        $query = Dar::with(['employee', 'attachments']);
        
        // Filter by employee (default: current user's DARs)
        if ($request->has('employee_id') && $employee->level !== 'staff') {
            $query->where('employee_id', $request->employee_id);
        } else {
            $query->where('employee_id', $employee->id);
        }
        
        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('dar_date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('dar_date', '<=', $request->end_date);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by month
        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('dar_date', $request->month)
                  ->whereYear('dar_date', $request->year);
        }
        
        // Sort
        $query->orderBy('dar_date', 'desc');
        
        // Paginate
        $perPage = $request->get('per_page', 15);
        $dars = $query->paginate($perPage);
        
        return DarResource::collection($dars);
    }

    /**
     * Store a newly created DAR
     */
    public function store(StoreDarRequest $request)
    {
        $employee = $request->user();
        
        DB::beginTransaction();
        try {
            // Generate DAR number
            $darNumber = $this->generateDarNumber($employee);
            
            // Create DAR
            $dar = Dar::create([
                'dar_number' => $darNumber,
                'employee_id' => $employee->id,
                'dar_date' => $request->dar_date,
                'activity' => $request->activity,
                'result' => $request->result,
                'plan' => $request->plan,
                'tag' => $request->tag,
                'status' => 'pending',
                'submitted_at' => now(),
                
                // Set approval chain from employee
                'supervisor_id' => $employee->supervisor_id,
                'manager_id' => $employee->manager_id,
                'senior_manager_id' => $employee->senior_manager_id,
                'director_id' => $employee->director_id,
                'owner_id' => $employee->owner_id,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'DAR created successfully',
                'data' => new DarResource($dar->load(['employee', 'attachments'])),
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create DAR',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified DAR
     */
    public function show(Request $request, $id)
    {
        $employee = $request->user();
        
        $dar = Dar::with(['employee', 'attachments'])->findOrFail($id);
        
        // Check authorization
        if (!$this->canViewDar($employee, $dar)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this DAR',
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'DAR retrieved successfully',
            'data' => new DarResource($dar),
        ], 200);
    }

    /**
     * Update the specified DAR
     */
    public function update(UpdateDarRequest $request, $id)
    {
        $employee = $request->user();
        
        $dar = Dar::findOrFail($id);
        
        // Check authorization (only owner can update, and only if not approved)
        if ($dar->employee_id !== $employee->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this DAR',
            ], 403);
        }
        
        if ($dar->status !== 'draft' && $dar->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update DAR that is already approved or rejected',
            ], 422);
        }
        
        DB::beginTransaction();
        try {
            $dar->update($request->only(['dar_date', 'activity', 'result', 'plan', 'tag']));
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'DAR updated successfully',
                'data' => new DarResource($dar->load(['employee', 'attachments'])),
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update DAR',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified DAR
     */
    public function destroy(Request $request, $id)
    {
        $employee = $request->user();
        
        $dar = Dar::findOrFail($id);
        
        // Check authorization
        if ($dar->employee_id !== $employee->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this DAR',
            ], 403);
        }
        
        if ($dar->status !== 'draft' && $dar->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete DAR that is already approved or rejected',
            ], 422);
        }
        
        $dar->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'DAR deleted successfully',
        ], 200);
    }

    /**
     * Approve DAR
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);
        
        $employee = $request->user();
        $dar = Dar::with('employee')->findOrFail($id);
        
        // Determine approver level
        $approverLevel = $this->getApproverLevel($employee, $dar);
        
        if (!$approverLevel) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to approve this DAR',
            ], 403);
        }
        
        DB::beginTransaction();
        try {
            // Approve at the current level
            $dar->approveByLevel($approverLevel, $request->notes);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "DAR approved by {$approverLevel}",
                'data' => new DarResource($dar->fresh(['employee', 'attachments'])),
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve DAR',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject DAR
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);
        
        $employee = $request->user();
        $dar = Dar::with('employee')->findOrFail($id);
        
        // Determine approver level
        $approverLevel = $this->getApproverLevel($employee, $dar);
        
        if (!$approverLevel) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to reject this DAR',
            ], 403);
        }
        
        DB::beginTransaction();
        try {
            // Reject at the current level
            $dar->rejectByLevel($approverLevel, $request->notes);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "DAR rejected by {$approverLevel}",
                'data' => new DarResource($dar->fresh(['employee', 'attachments'])),
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject DAR',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get DARs pending approval for current user
     */
    public function pendingApprovals(Request $request)
    {
        $employee = $request->user();
        
        $query = Dar::with(['employee', 'attachments'])
            ->where('status', 'pending');
        
        // Filter based on user's level
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
                      ->where('director_status', 'pending')
                      ->where(function($q) use ($employee) {
                          $q->where('senior_manager_status', 'approved')
                            ->orWhereNull('senior_manager_id');
                      });
                break;
            case 'owner':
                $query->where('owner_id', $employee->id)
                      ->where('owner_status', 'pending')
                      ->where('director_status', 'approved');
                break;
            default:
                // Staff cannot approve
                return response()->json([
                    'success' => true,
                    'message' => 'No pending approvals',
                    'data' => [],
                ], 200);
        }
        
        $perPage = $request->get('per_page', 15);
        $dars = $query->orderBy('dar_date', 'desc')->paginate($perPage);
        
        return DarResource::collection($dars);
    }

    // ============================================
    // Helper Methods
    // ============================================

    /**
     * Generate DAR number
     */
    private function generateDarNumber($employee): string
    {
        $date = now()->format('Ymd');
        $count = Dar::where('employee_id', $employee->id)
                    ->whereDate('created_at', today())
                    ->count() + 1;
        
        return "DAR/{$employee->nik}/{$date}/{$count}";
    }

    /**
     * Check if employee can view DAR
     */
    private function canViewDar($employee, $dar): bool
    {
        // Owner can view their own DARs
        if ($dar->employee_id === $employee->id) {
            return true;
        }
        
        // Approvers can view DARs they need to approve
        return $dar->supervisor_id === $employee->id
            || $dar->manager_id === $employee->id
            || $dar->senior_manager_id === $employee->id
            || $dar->director_id === $employee->id
            || $dar->owner_id === $employee->id;
    }

    /**
     * Get approver level for employee
     */
    private function getApproverLevel($employee, $dar): ?string
    {
        if ($dar->supervisor_id === $employee->id) {
            return 'supervisor';
        }
        if ($dar->manager_id === $employee->id) {
            return 'manager';
        }
        if ($dar->senior_manager_id === $employee->id) {
            return 'senior_manager';
        }
        if ($dar->director_id === $employee->id) {
            return 'director';
        }
        if ($dar->owner_id === $employee->id) {
            return 'owner';
        }
        
        return null;
    }
}
