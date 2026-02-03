@extends('layouts.app')

@section('title', 'Attendance History - SIDAR HRIS')

@section('content')
<div class="px-4 sm:px-0">
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Attendance History</h1>
            <p class="mt-1 text-sm text-gray-600">Your previous attendance records</p>
        </div>
        <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-4 py-2 border border-primary-600 text-sm font-medium rounded-md text-primary-600 bg-white hover:bg-primary-50">
            Back to Mark
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('attendance.history') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- History Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($attendances->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Hours</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($attendances as $att)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $att->attendance_date->format('d M Y') }}
                            <div class="text-xs text-gray-500">{{ $att->attendance_date->format('l') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-md uppercase 
                                {{ $att->work_type === 'wfo' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $att->work_type === 'wfh' ? 'bg-teal-100 text-teal-800' : '' }}
                                {{ $att->work_type === 'outside' ? 'bg-orange-100 text-orange-800' : '' }}">
                                {{ $att->work_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($att->check_in_time)
                                {{ $att->check_in_time->format('H:i') }}
                                @if($att->check_in_status === 'late')
                                    <span class="ml-1 text-xs text-red-500 font-medium">(Late)</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $att->check_out_time ? $att->check_out_time->format('H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full capitalize 
                                {{ $att->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $att->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($att->check_in_time && $att->check_out_time)
                                {{ $att->check_out_time->diff($att->check_in_time)->format('%Hh %Im') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $attendances->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-500">No attendance records found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
