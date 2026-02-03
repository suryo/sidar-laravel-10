<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display today's attendance status and check-in/out form
     */
    public function index(Request $request)
    {
        $employee = $request->user();
        $today = Carbon::today();
        
        $attendance = Attendance::where('employee_id', $employee->id)
                                ->whereDate('attendance_date', $today)
                                ->first();
        
        return view('attendance.index', compact('attendance'));
    }

    /**
     * Show attendance history
     */
    public function history(Request $request)
    {
        $query = Attendance::where('employee_id', $request->user()->id)
                           ->orderBy('attendance_date', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        $attendances = $query->paginate(15);

        return view('attendance.history', compact('attendances'));
    }

    /**
     * Handle Check-in
     */
    public function checkIn(Request $request)
    {
        $employee = $request->user();
        $today = Carbon::today();

        // Already checked in today?
        $exists = Attendance::where('employee_id', $employee->id)
                            ->whereDate('attendance_date', $today)
                            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already checked in today.');
        }

        $request->validate([
            'work_type' => 'required|in:wfo,wfh,outside',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance = new Attendance();
        $attendance->attendance_number = 'ATT-' . $employee->nik . '-' . now()->format('Ymd');
        $attendance->employee_id = $employee->id;
        $attendance->attendance_date = $today;
        $attendance->work_type = $request->work_type;
        $attendance->notes = $request->notes;
        
        $attendance->checkIn([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Checked in successfully at ' . now()->format('H:i'));
    }

    /**
     * Handle Check-out
     */
    public function checkOut(Request $request)
    {
        $employee = $request->user();
        $today = Carbon::today();

        $attendance = Attendance::where('employee_id', $employee->id)
                                ->whereDate('attendance_date', $today)
                                ->first();

        if (!$attendance) {
            return back()->with('error', 'You have not checked in today.');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'You have already checked out today.');
        }

        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
        ]);

        $attendance->checkOut([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Checked out successfully at ' . now()->format('H:i'));
    }
}
