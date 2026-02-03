@extends('layouts.app')

@section('title', 'Gap Analysis Report')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <!-- Header info -->
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Attendance vs. DAR Gap Analysis</h3>
        <p class="mt-1 text-sm text-gray-500">Showing days where an employee attended (Checked In) but did <strong>NOT</strong> submit a DAR.</p>
    </div>

    <!-- Filters -->
    <div class="p-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-end space-x-4">
        <form method="GET" action="{{ route('reports.gap-analysis') }}" class="flex space-x-4 w-full">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
            </div>
            <div class="flex-1">
                <label for="employee_id" class="block text-sm font-medium text-gray-700">Filter by Employee</label>
                <select name="employee_id" id="employee_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                    <option value="">All Employees</option>
                    @foreach($employees as $id => $name)
                        <option value="{{ $id }}" {{ $employeeId == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Run Analysis
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In Time</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out Time</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($gaps as $gap)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($gap->date)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $gap->employee->name }}</div>
                        <div class="text-xs text-gray-500">{{ $gap->employee->nik }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($gap->check_in)->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $gap->check_out ? \Carbon\Carbon::parse($gap->check_out)->format('H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <span class="text-red-600 font-bold">Missing DAR</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center">
                        <div class="text-green-500 font-bold text-lg mb-2">No Gaps Found!</div>
                        <p class="text-gray-500">All attendance records have matching DARs for this period.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
