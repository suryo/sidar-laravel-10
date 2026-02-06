<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LatePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LatePermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = LatePermission::where('employee_id', $request->user()->id)
                    ->orderBy('created_at', 'desc');

        $latePermissions = $query->paginate(15);

        return view('late-permissions.index', compact('latePermissions'));
    }

    public function create()
    {
        // Calculate the "Late Frequency" for this month? 
        // User asked for "terlmbat ke", implies a counter.
        // Let's count how many times they've been late this month.
        $employee = auth()->user();
        $currentMonth = now()->month;
        $lateCount = LatePermission::where('employee_id', $employee->id)
                                   ->whereMonth('late_date', $currentMonth)
                                   ->count() + 1;

        return view('late-permissions.create', compact('employee', 'lateCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'late_date' => 'required|date',
            'arrival_time' => 'required|date_format:H:i',
            'late_frequency' => 'required|integer',
            'reason' => 'required|string|max:500',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB Max
        ]);

        $path = null;
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('late-permissions', 'public');
        }

        $latePermission = LatePermission::create([
            'employee_id' => $request->user()->id,
            'late_date' => $validated['late_date'],
            'arrival_time' => $validated['arrival_time'],
            'late_frequency' => $validated['late_frequency'],
            'reason' => $validated['reason'],
            'proof_file' => $path,
            'approved_by_supervisor' => false,
            'acknowledged_by_hcs' => false,
        ]);

        return redirect()->route('late-permissions.index')
                       ->with('success', 'Late permission request submitted successfully.');
    }

    public function show(LatePermission $latePermission)
    {
        $this->authorize('view', $latePermission);
        return view('late-permissions.show', compact('latePermission'));
    }
}
