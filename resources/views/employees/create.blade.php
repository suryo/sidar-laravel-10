@extends('layouts.app')

@section('title', 'Add New Employee')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Onboard New Employee</h3>
            <p class="mt-1 text-sm text-gray-500">Create a new user account.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-6 gap-6">
                    <!-- Personal Info -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>
                
                    <div class="col-span-6 sm:col-span-3">
                        <label for="nik" class="block text-sm font-medium text-gray-700">NIK (Employee ID)</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="password" class="block text-sm font-medium text-gray-700">Initial Password</label>
                        <input type="text" name="password" id="password" value="password123" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                        <p class="text-xs text-gray-500 mt-1">Default: password123</p>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Organization -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                        <select name="department_id" id="department_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="division_id" class="block text-sm font-medium text-gray-700">Division</label>
                        <select name="division_id" id="division_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Select Division</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('division_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="location_id" class="block text-sm font-medium text-gray-700">Work Location</label>
                        <select name="location_id" id="location_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Select Location</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="role_id" class="block text-sm font-medium text-gray-700">System Role</label>
                        <select name="role_id" id="role_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="supervisor_id" class="block text-sm font-medium text-gray-700">Direct Supervisor</label>
                        <select name="supervisor_id" id="supervisor_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">None (Top Level)</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>{{ $supervisor->name }} - {{ $supervisor->position }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Work Details -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="position" class="block text-sm font-medium text-gray-700">Job Position</label>
                        <input type="text" name="position" id="position" value="{{ old('position') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="join_date" class="block text-sm font-medium text-gray-700">Join Date</label>
                        <input type="date" name="join_date" id="join_date" value="{{ old('join_date', now()->format('Y-m-d')) }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="leave_quota" class="block text-sm font-medium text-gray-700">Annual Leave Quota</label>
                        <input type="number" name="leave_quota" id="leave_quota" value="{{ old('leave_quota', 12) }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('employees.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Create Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
