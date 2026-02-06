<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OvertimeMandate;
use App\Models\OvertimeEmployee;
use App\Models\Employee;
use App\Models\Division;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        // Show mandates created by user or where they are involved?
        // Usually HR/Admin/Manager creates mandates.
        // For now, list all or filter by user's division if restrictive.
        // Let's list all for simplicity, ordered by date.
        
        $query = OvertimeMandate::with(['division', 'employees'])
                    ->orderBy('date', 'desc');

        $mandates = $query->paginate(15);

        return view('overtimes.index', compact('mandates'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        // Assuming current user's division or all divisions?
        // Let's pass all divisions for flexibility
        $divisions = Division::orderBy('name')->get();
        
        return view('overtimes.create', compact('employees', 'divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'date' => 'required|date',
            'task_description' => 'required|string',
            'employees' => 'required|array|min:1',
            'employees.*.employee_id' => 'required|exists:employees,id',
            'employees.*.start_time' => 'required|date_format:H:i',
            'employees.*.end_time' => 'required|date_format:H:i|after:employees.*.start_time',
        ]);

        $mandate = OvertimeMandate::create([
            'division_id' => $validated['division_id'],
            'date' => $validated['date'],
            'task_description' => $validated['task_description'],
        ]);

        foreach ($validated['employees'] as $empData) {
            $start = Carbon::parse($empData['start_time']);
            $end = Carbon::parse($empData['end_time']);
            $duration = $end->diffInMinutes($start);

            OvertimeEmployee::create([
                'overtime_mandate_id' => $mandate->id,
                'employee_id' => $empData['employee_id'],
                'start_time' => $empData['start_time'],
                'end_time' => $empData['end_time'],
                'duration_minutes' => $duration,
            ]);
        }

        return redirect()->route('overtimes.index')
                       ->with('success', 'Surat Perintah Lembur berhasil dibuat.');
    }

    public function show($id)
    {
        $mandate = OvertimeMandate::with(['division', 'employees.employee'])->findOrFail($id);
        return view('overtimes.show', compact('mandate'));
    }
}
