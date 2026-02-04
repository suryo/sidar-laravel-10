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
     * Show check-in form (Legacy View: staffabsenluarkotainbeta)
     */
    public function create()
    {
        return view('attendance.create');
    }

    /**
     * Show check-out form
     */
    public function checkoutView()
    {
        return view('attendance.checkout');
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
            'photo_base64' => 'nullable|string', // Add validation for base64
        ]);

        $attendance = new Attendance();
        $attendance->attendance_number = 'ATT-' . $employee->nik . '-' . now()->format('Ymd');
        $attendance->employee_id = $employee->id;
        $attendance->attendance_date = $today;
        $attendance->work_type = $request->work_type;
        $attendance->notes = $request->notes;
        
        // Handle Base64 Photo
        $photoPath = null;
        if ($request->has('photo_base64') && $request->photo_base64) {
            $image = $request->photo_base64;  // your base64 encoded
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'attendance_' . $employee->id . '_' . time() . '.jpg';
            
            // Ensure directory exists
            if (!file_exists(storage_path('app/public/attendance_photos'))) {
                mkdir(storage_path('app/public/attendance_photos'), 0755, true);
            }
            
            \File::put(storage_path('app/public/attendance_photos') . '/' . $imageName, base64_decode($image));
            $photoPath = 'attendance_photos/' . $imageName;
        }

        $attendance->checkIn([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'photo' => $photoPath, // Pass photo path
        ]);

        return redirect()->route('dashboard')->with('success', 'Checked in successfully at ' . now()->format('H:i'));
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
            'photo_base64' => 'nullable|string',
            'notes' => 'nullable|string|max:500', // Allow notes for check-out too
        ]);

         // Handle Base64 Photo
        $photoPath = null;
        if ($request->has('photo_base64') && $request->photo_base64) {
            $image = $request->photo_base64; 
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'attendance_out_' . $employee->id . '_' . time() . '.jpg';
            
            if (!file_exists(storage_path('app/public/attendance_photos'))) {
                mkdir(storage_path('app/public/attendance_photos'), 0755, true);
            }
            
            \File::put(storage_path('app/public/attendance_photos') . '/' . $imageName, base64_decode($image));
            $photoPath = 'attendance_photos/' . $imageName;
        }

        // If checks out with notes, append or overwrite? Let's just update the note if new one exists or perhaps this field is in the table but not used in checkIn? 
        // The model has 'notes'. If we want to keep both, we might append. For now, let's just let checkOut update it if provided.
        if ($request->notes) {
            $attendance->notes = $attendance->notes ? $attendance->notes . ' | Out: ' . $request->notes : $request->notes;
        }

        $attendance->checkOut([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'photo' => $photoPath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Checked out successfully at ' . now()->format('H:i'));
    }
}
