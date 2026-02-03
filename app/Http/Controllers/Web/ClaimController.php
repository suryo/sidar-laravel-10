<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employee = $request->user();
        
        $claims = Claim::where('employee_id', $employee->id)
            ->latest()
            ->paginate(10);
            
        return view('claims.index', compact('claims'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('claims.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:medical,optical,transport,travel,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
        ]);

        $employee = $request->user();
        
        DB::beginTransaction();
        try {
            // Handle File Upload
            $path = null;
            if ($request->hasFile('proof_file')) {
                $file = $request->file('proof_file');
                $filename = 'claim_' . $employee->nik . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('claims', $filename, 'public');
            }

            $claim = new Claim();
            $claim->claim_number = 'CLM-' . $employee->nik . '-' . now()->format('Ymd') . '-' . Str::upper(Str::random(3));
            $claim->employee_id = $employee->id;
            $claim->type = $request->type;
            $claim->amount = $request->amount;
            $claim->description = $request->description;
            $claim->proof_file = $path;
            
            // Auto-assign supervisor
            $claim->supervisor_id = $employee->supervisor_id;
            
            // Initial Status
            $claim->status = 'pending';
            
            $claim->save();
            
            DB::commit();
            return redirect()->route('claims.index')->with('success', 'Claim submitted successfully.');
            
        } catch (\Exception $e) {
            DB::rollback();
            // Delete uploaded file if exists
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            return back()->with('error', 'Failed to submit claim: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Claim $claim)
    {
        $this->authorize('view', $claim);
        return view('claims.show', compact('claim'));
    }

    /**
     * Show pending approvals
     */
    public function approvals(Request $request)
    {
        $user = $request->user();
        $role = $user->role->name ?? ''; // Assuming relation exists
        
        $query = Claim::query()->with('employee');
        
        // Logic for different approvers
        if ($user->position === 'Supervisor' || $role === 'supervisor') {
            $query->where('supervisor_id', $user->id)
                  ->where('status', 'pending');
        } elseif ($role === 'hcs' || $role === 'admin' || $user->department_id == 2) { 
            // HCS sees claims approved by supervisor
            $query->where('status', 'approved_supervisor');
        } elseif ($role === 'finance' || $user->department_id == 3) { // Assuming Finance dept ID
             // Finance sees claims approved by HCS
            $query->where('status', 'approved_hcs');
        } else {
             // Fallback for demo: show nothing or relevant items
             return view('claims.approvals', ['claims' => collect([])]);
        }
        
        $claims = $query->latest()->get();
        
        return view('claims.approvals', compact('claims'));
    }

    /**
     * Process approval
     */
    public function processApproval(Request $request, Claim $claim)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $action = $request->action;
        $notes = $request->notes;
        
        if ($action === 'reject') {
            $claim->status = 'rejected';
            $claim->rejection_note = $notes;
            $claim->rejected_by = $user->id;
            $claim->save();
            
            return back()->with('success', 'Claim rejected.');
        }
        
        // Approve Logic
        if ($claim->status === 'pending') {
            // Supervisor Approval
            // Check authorization strictly in real app
            $claim->status = 'approved_supervisor';
            $claim->supervisor_approved_at = now();
            $claim->supervisor_notes = $notes;
            $claim->supervisor_id = $user->id; // Confirm approver
        } elseif ($claim->status === 'approved_supervisor') {
            // HCS Approval
            $claim->status = 'approved_hcs';
            $claim->hcs_approved_at = now();
            $claim->hcs_notes = $notes;
            $claim->hcs_id = $user->id;
        } elseif ($claim->status === 'approved_hcs') {
            // Finance Approval (Payment)
            $claim->status = 'paid';
            $claim->finance_processed_at = now();
            $claim->finance_notes = $notes;
            $claim->finance_id = $user->id;
        }
        
        $claim->save();
        
        return back()->with('success', 'Claim approved successfully.');
    }
}
