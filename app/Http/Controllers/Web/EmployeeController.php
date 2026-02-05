<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\AccessArea;
use App\Models\BusinessUnit;
use App\Models\Department;
use App\Models\Division;
use App\Models\Location;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['role', 'department', 'division'])->orderBy('name');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('role', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('department', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('division', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $employees = $query->paginate(15);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();
        $divisions = Division::all();
        $locations = Location::all();
        $accessAreas = AccessArea::all();
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        // For supervisor selection
        $supervisors = Employee::where('status', 'active')->orderBy('name')->get();

        return view('employees.create', compact('roles', 'departments', 'divisions', 'locations', 'accessAreas', 'businessUnits', 'supervisors'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'nik' => 'required|string|max:20|unique:employees',
            'password' => 'required|string|min:8',
        'role_id' => 'required|exists:roles,id',
        'department_id' => 'required|exists:departments,id',
        'division_id' => 'required|exists:divisions,id',
        'location_id' => 'required|exists:locations,id',
        'access_area_id' => 'nullable|exists:access_areas,id',
        'business_unit_id' => 'nullable|exists:business_units,id',
        'phone' => 'nullable|string|max:20',
        'position' => 'nullable|string|max:100',
        'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'approvers' => 'nullable|array|max:5',
            'approvers.*' => 'exists:employees,id|distinct',
            'leave_quota' => 'required|integer|min:0',
        ]);

        $employee = Employee::create($request->except(['approvers']));

        if ($request->has('approvers')) {
            $approversData = [];
            foreach ($request->approvers as $index => $approverId) {
                if ($approverId) {
                    $approversData[$approverId] = ['order' => $index + 1];
                }
            }
            $employee->approvers()->attach($approversData);
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $roles = Role::all();
        $departments = Department::all();
        $divisions = Division::all();
        $locations = Location::all();
        $accessAreas = AccessArea::all();
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        $supervisors = Employee::where('status', 'active')->where('id', '!=', $employee->id)->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'roles', 'departments', 'divisions', 'locations', 'accessAreas', 'businessUnits', 'supervisors'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('employees')->ignore($employee->id)],
            'nik' => ['required', 'string', Rule::unique('employees')->ignore($employee->id)],
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'division_id' => 'required|exists:divisions,id',
            'location_id' => 'required|exists:locations,id',
            'access_area_id' => 'nullable|exists:access_areas,id',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive,resigned',
            'approvers' => 'nullable|array|max:5',
            'approvers.*' => 'exists:employees,id|distinct',
            'leave_quota' => 'required|integer|min:0',
        ]);

        $data = $request->except(['password']);
        
        // Capture old approvers for logging (load if not loaded)
        $employee->load('approvers');
        $oldApprovers = $employee->approvers->pluck('name')->implode(', ');

        if ($request->filled('password')) {
            $data['password'] = $request->password; // Mutator will handle hashing
        }

        $employee->update($data);

        if ($request->has('approvers')) {
            $approversData = [];
            foreach ($request->approvers as $index => $approverId) {
                if ($approverId) {
                    $approversData[$approverId] = ['order' => $index + 1];
                }
            }
            $employee->approvers()->sync($approversData);

            // Fetch new approvers for logging
            $employee->load('approvers');
            $newApprovers = $employee->approvers->pluck('name')->implode(', ');
            
            // Log manually if differences exist
            if ($oldApprovers !== $newApprovers) {
                \App\Models\Employee::customLog('updated', $employee, ['approvers' => $oldApprovers], ['approvers' => $newApprovers]);
            }

        } else {
             if ($request->exists('approvers')) {
                 $employee->approvers()->detach();
                 // Log detachment
                 if ($oldApprovers !== '') {
                      \App\Models\Employee::customLog('updated', $employee, ['approvers' => $oldApprovers], ['approvers' => 'None']);
                 }
             }
        }

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        // Prevent deleting self
        if ($employee->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deactivated successfully.');
    }

    /**
     * Impersonate the specified employee.
     */
    public function impersonate(Employee $employee)
    {
        if ($employee->id === auth()->id()) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        // Store original user ID in session to allow switching back later
        session()->put('impersonator_id', auth()->id());

        auth()->loginUsingId($employee->id);

        return redirect()->route('dashboard')->with('success', "You are now logged in as {$employee->name}");
    }

    /**
     * Stop impersonating and return to original user.
     */
    public function stopImpersonate()
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->route('dashboard');
        }

        auth()->loginUsingId(session('impersonator_id'));
        session()->forget('impersonator_id');

        return redirect()->route('employees.index')->with('success', 'Welcome back!');
    }
}
