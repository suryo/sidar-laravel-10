<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OfficeExitPermit;
use Illuminate\Http\Request;

class OfficeExitPermitController extends Controller
{
    public function index(Request $request)
    {
        $query = OfficeExitPermit::where('employee_id', $request->user()->id)
                    ->orderBy('created_at', 'desc');

        $permits = $query->paginate(15);

        return view('office-exit-permits.index', compact('permits'));
    }

    public function create()
    {
        $employee = auth()->user();
        return view('office-exit-permits.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'exit_type' => 'required|in:dinas_luar,personal',
            'purpose' => 'required|in:dinas_luar,pribadi',
            'out_time' => 'required|date_format:H:i',
            'is_returning' => 'required|boolean',
            'return_time' => 'nullable|date_format:H:i|required_if:is_returning,1',
            'reason' => 'required|string|max:1000',
        ]);

        OfficeExitPermit::create([
            'employee_id' => $request->user()->id,
            'date' => $validated['date'],
            'exit_type' => $validated['exit_type'],
            'purpose' => $validated['purpose'],
            'out_time' => $validated['out_time'],
            'is_returning' => $validated['is_returning'],
            'return_time' => $validated['return_time'],
            'reason' => $validated['reason'],
            'approved_by_supervisor' => false,
            'acknowledged_by_hcs' => false,
        ]);

        return redirect()->route('office-exit-permits.index')
                       ->with('success', 'Pengajuan ijin keluar kantor berhasil dikirim.');
    }

    public function show(OfficeExitPermit $officeExitPermit)
    {
        if ($officeExitPermit->employee_id !== auth()->id()) {
            abort(403);
        }
        return view('office-exit-permits.show', compact('officeExitPermit'));
    }
}
