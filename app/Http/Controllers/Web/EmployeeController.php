<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
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
                  ->orWhere('email', 'like', "%{$search}%");
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
        // For supervisor selection
        $supervisors = Employee::where('status', 'active')->orderBy('name')->get();

        return view('employees.create', compact('roles', 'departments', 'divisions', 'locations', 'supervisors'));
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
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'supervisor_id' => 'nullable|exists:employees,id',
            'leave_quota' => 'required|integer|min:0',
        ]);

        Employee::create($request->all());

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
        $supervisors = Employee::where('status', 'active')->where('id', '!=', $employee->id)->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'roles', 'departments', 'divisions', 'locations', 'supervisors'));
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
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive,resigned',
            'supervisor_id' => 'nullable|exists:employees,id',
            'leave_quota' => 'required|integer|min:0',
        ]);

        $data = $request->except(['password']);

        if ($request->filled('password')) {
            $data['password'] = $request->password; // Mutator will handle hashing
        }

        $employee->update($data);

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
