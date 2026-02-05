@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Employee: {{ $employee->name }}</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('employees.update', $employee) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-6 gap-6">
                    <!-- Personal Info -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>
                
                    <div class="col-span-6 sm:col-span-3">
                        <label for="nik" class="block text-sm font-medium text-gray-700">NIK (Employee ID)</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik', $employee->nik) }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password (Optional)</label>
                        <input type="text" name="password" id="password" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" placeholder="Leave blank to keep current">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Organization -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                        <select name="department_id" id="department_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="division_id" class="block text-sm font-medium text-gray-700">Division</label>
                        <select name="division_id" id="division_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('division_id', $employee->division_id) == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="access_area_id" class="block text-sm font-medium text-gray-700">Access Area</label>
                        <select name="access_area_id" id="access_area_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Select Access Area</option>
                            @foreach($accessAreas as $area)
                                <option value="{{ $area->id }}" {{ old('access_area_id', $employee->access_area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="business_unit_id" class="block text-sm font-medium text-gray-700">Business Unit</label>
                        <select name="business_unit_id" id="business_unit_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Select Business Unit</option>
                            @foreach($businessUnits as $bu)
                                <option value="{{ $bu->id }}" {{ old('business_unit_id', $employee->business_unit_id) == $bu->id ? 'selected' : '' }}>{{ $bu->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="location_id" class="block text-sm font-medium text-gray-700">Work Location</label>
                        <select name="location_id" id="location_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id', $employee->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="role_id" class="block text-sm font-medium text-gray-700">System Role</label>
                        <select name="role_id" id="role_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Multi-Approvers (Cc/Bc) - Dynamic -->
                     <div class="col-span-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cc/Bc (Approvers)</label>
                        <div id="approver-wrapper" class="space-y-3">
                            <!-- Dynamic inputs will appear here -->
                        </div>
                        <button type="button" onclick="addApprover()" class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Cc/Bc
                        </button>
                        <p class="mt-1 text-xs text-gray-500">Max 5 approvers. Order determines approval sequence/priority.</p>
                    </div>

                    <script>
                        const supervisors = @json($supervisors);
                        // Old input (if validation fails) takes precedence
                        const oldApprovers = @json(old('approvers'));
                        // DB data: {order: id}
                        const dbApprovers = @json($employee->approvers->pluck('id', 'pivot.order'));
                        
                        function updateApproverOptions() {
                            const wrapper = document.getElementById('approver-wrapper');
                            const selects = wrapper.querySelectorAll('select');
                            const selectedValues = Array.from(selects)
                                                        .map(s => s.value)
                                                        .filter(v => v);

                            selects.forEach(select => {
                                const currentVal = select.value;
                                Array.from(select.options).forEach(option => {
                                    if (!option.value) return; 
                                    
                                    if (selectedValues.includes(option.value) && option.value !== currentVal) {
                                        option.disabled = true;
                                        option.classList.add('text-gray-400');
                                    } else {
                                        option.disabled = false;
                                        option.classList.remove('text-gray-400');
                                    }
                                });
                            });
                        }

                        function addApprover(selectedId = null) {
                            const wrapper = document.getElementById('approver-wrapper');
                            const count = wrapper.children.length;
                            
                            if (count >= 5) {
                                alert('Maximum 5 approvers allowed.');
                                return;
                            }

                            const div = document.createElement('div');
                            div.className = 'flex items-center gap-2';
                            
                            let options = '<option value="">-- Select Approver --</option>';
                            supervisors.forEach(supervisor => {
                                const isSelected = selectedId == supervisor.id ? 'selected' : '';
                                options += `<option value="${supervisor.id}" ${isSelected}>${supervisor.name} - ${supervisor.position}</option>`;
                            });

                            div.innerHTML = `
                                <div class="flex-grow">
                                    <select name="approvers[]" onchange="updateApproverOptions()" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        ${options}
                                    </select>
                                </div>
                                <button type="button" onclick="removeApprover(this)" class="text-red-600 hover:text-red-800 p-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            `;
                            
                            wrapper.appendChild(div);
                            updateApproverOptions();
                        }
                        
                        function removeApprover(btn) {
                            btn.closest('.flex').remove();
                            updateApproverOptions();
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            let initialData = [];
                            
                            // Check old input first
                            if (oldApprovers && (Array.isArray(oldApprovers) || Object.keys(oldApprovers).length > 0)) {
                                initialData = Array.isArray(oldApprovers) ? oldApprovers : Object.values(oldApprovers);
                            } 
                            // Fallback to DB data
                            else if (dbApprovers && Object.keys(dbApprovers).length > 0) {
                                // dbApprovers is object {"1":id, "2":id}
                                // Ensure sorted by order key (keys are strings "1", "2")
                                initialData = Object.keys(dbApprovers)
                                                    .sort((a,b) => parseInt(a) - parseInt(b))
                                                    .map(key => dbApprovers[key]);
                            }
                            
                            initialData.forEach(function(id) {
                                if(id) addApprover(id);
                            });
                        });
                    </script>

                    <!-- Work Details -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="position" class="block text-sm font-medium text-gray-700">Job Position</label>
                        <input type="text" name="position" id="position" value="{{ old('position', $employee->position) }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="join_date" class="block text-sm font-medium text-gray-700">Join Date</label>
                        <input type="date" name="join_date" id="join_date" value="{{ old('join_date', $employee->join_date->format('Y-m-d')) }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="active" {{ $employee->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $employee->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="resigned" {{ $employee->status === 'resigned' ? 'selected' : '' }}>Resigned</option>
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="leave_quota" class="block text-sm font-medium text-gray-700">Annual Leave Quota</label>
                        <input type="number" name="leave_quota" id="leave_quota" value="{{ old('leave_quota', $employee->leave_quota) }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('employees.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Update Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
