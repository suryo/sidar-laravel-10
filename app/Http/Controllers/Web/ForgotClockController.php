<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ForgotClock;
use Illuminate\Http\Request;

class ForgotClockController extends Controller
{
    public function index(Request $request)
    {
        $query = ForgotClock::where('employee_id', $request->user()->id)
                    ->orderBy('created_at', 'desc');

        $forgotClocks = $query->paginate(15);

        return view('forgot-clocks.index', compact('forgotClocks'));
    }

    public function create()
    {
        $employee = auth()->user();
        return view('forgot-clocks.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'clock_type' => 'required|in:in,out',
            'clock_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:500',
        ]);

        ForgotClock::create([
            'employee_id' => $request->user()->id,
            'date' => $validated['date'],
            'clock_type' => $validated['clock_type'],
            'clock_time' => $validated['clock_time'],
            'reason' => $validated['reason'],
            'approved_by_supervisor' => false,
            'acknowledged_by_hcs' => false,
        ]);

        return redirect()->route('forgot-clocks.index')
                       ->with('success', 'Pengajuan lupa check clock berhasil dikirim.');
    }

    public function show(ForgotClock $forgotClock)
    {
        // $this->authorize('view', $forgotClock); // Temporarily removing auth until Policy is set or just check simple ID
        if ($forgotClock->employee_id !== auth()->id()) {
            abort(403);
        }
        return view('forgot-clocks.show', compact('forgotClock'));
    }
}
