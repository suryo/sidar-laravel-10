@extends('layouts.app')

@section('title', 'Leaves - SIDAR HRIS')

@section('content')
<div class="px-4 sm:px-0">
    <div class="mb-6 flex justify-between items-center text-center sm:text-left flex-col sm:row gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Leave Requests</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your leave and track your quota</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            @if(auth()->user()->role->can_approve)
            <a href="{{ route('leaves.approvals') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Pending Approvals
            </a>
            @endif
            <a href="{{ route('leaves.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                Request Leave
            </a>
        </div>
    </div>

    <!-- Stats/Quota Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6 flex items-center border-l-4 border-primary-500">
            <div class="bg-primary-100 p-3 rounded-full mr-4">
                <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Leave Quota</p>
                <p class="text-2xl font-bold text-gray-900">12 Days</p>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6 flex items-center border-l-4 border-teal-500">
            <div class="bg-teal-100 p-3 rounded-full mr-4">
                <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Remaining Quota</p>
                <p class="text-2xl font-bold text-teal-600">{{ $employee->leave_quota }} Days</p>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6 flex items-center border-l-4 border-orange-500">
            <div class="bg-orange-100 p-3 rounded-full mr-4">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Pending Requests</p>
                <p class="text-2xl font-bold text-orange-600">{{ $leaves->where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Request History</h3>
        </div>
        @if($leaves->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($leaves as $leave)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $leave->leave_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 rounded text-xs font-medium capitalize 
                                {{ $leave->type === 'annual' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $leave->type === 'sick' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $leave->type === 'permission' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $leave->type === 'late' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ $leave->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $leave->total_days }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full capitalize 
                                {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $leave->status === 'pending' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $leave->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $leave->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $leave->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('leaves.show', $leave) }}" class="text-primary-600 hover:text-primary-900 mr-3">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $leaves->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No leave requests</h3>
            <p class="mt-1 text-sm text-gray-500">You haven't submitted any leave requests yet.</p>
            <div class="mt-6">
                <a href="{{ route('leaves.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    New Request
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
