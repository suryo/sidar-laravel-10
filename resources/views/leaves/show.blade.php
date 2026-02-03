@extends('layouts.app')

@section('title', 'Leave Details - SIDAR HRIS')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-0">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Leave Details</h1>
            <p class="mt-1 text-sm text-gray-600">Request ID: {{ $leave->leave_number }}</p>
        </div>
        <div>
            <span class="px-3 py-1 text-sm font-semibold rounded-full capitalize 
                {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                {{ $leave->status === 'pending' ? 'bg-orange-100 text-orange-800' : '' }}
                {{ $leave->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                {{ $leave->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                {{ $leave->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-4 mb-4">Request Information</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Employee</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $leave->employee->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $leave->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Period</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Duration</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $leave->total_days }} Days</dd>
                    </div>
                    @if($leave->type === 'late')
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Late Arrival Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $leave->late_arrival_time ? Carbon\Carbon::parse($leave->late_arrival_time)->format('H:i') : '-' }}</dd>
                    </div>
                    @endif
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Reason</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $leave->reason }}</dd>
                    </div>
                </dl>
            </div>

            @if($leave->delegate_to)
            <div class="bg-white shadow rounded-lg p-6 border-l-4 border-indigo-400">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Delegation Details</h3>
                <div class="flex items-start">
                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-4">
                        {{ substr($leave->delegateTo->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Delegated to {{ $leave->delegateTo->name }}</p>
                        <p class="text-xs text-gray-500 mb-2">{{ $leave->delegateTo->position }}</p>
                        <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded italic">"{{ $leave->delegation_tasks }}"</p>
                        <p class="mt-2 text-xs font-semibold capitalize {{ $leave->delegate_status === 'approved' ? 'text-green-600' : ($leave->delegate_status === 'rejected' ? 'text-red-600' : 'text-orange-600') }}">
                            Status: {{ $leave->delegate_status }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Approval Timeline -->
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Timeline</h3>
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        <!-- Submission -->
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Requested by <span class="font-medium text-gray-900">{{ $leave->employee->name }}</span></p>
                                        </div>
                                        <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                            {{ $leave->submitted_at ? $leave->submitted_at->format('d M H:i') : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Supervisor -->
                        @if($leave->supervisor_id)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full {{ $leave->supervisor_status === 'approved' ? 'bg-green-500' : ($leave->supervisor_status === 'rejected' ? 'bg-red-500' : 'bg-gray-400') }} flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Supervisor: <span class="font-medium text-gray-900">{{ $leave->supervisor->name }}</span></p>
                                            <p class="text-xs {{ $leave->supervisor_status === 'approved' ? 'text-green-600' : ($leave->supervisor_status === 'rejected' ? 'text-red-600' : 'text-gray-400') }} font-semibold capitalize">{{ $leave->supervisor_status }}</p>
                                            @if($leave->supervisor_notes)
                                            <p class="text-xs text-gray-400 italic mt-1 bg-gray-50 p-1">"{{ $leave->supervisor_notes }}"</p>
                                            @endif
                                        </div>
                                        <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                            {{ $leave->supervisor_approved_at ? $leave->supervisor_approved_at->format('d M H:i') : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif

                        <!-- HCS (Human Capital) -->
                        @if($leave->hcs_id)
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full {{ $leave->hcs_status === 'approved' ? 'bg-green-500' : ($leave->hcs_status === 'rejected' ? 'bg-red-500' : 'bg-gray-400') }} flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">HCS Verification</p>
                                            <p class="text-xs {{ $leave->hcs_status === 'approved' ? 'text-green-600' : ($leave->hcs_status === 'rejected' ? 'text-red-600' : 'text-gray-400') }} font-semibold capitalize">{{ $leave->hcs_status }}</p>
                                            @if($leave->hcs_notes)
                                            <p class="text-xs text-gray-400 italic mt-1 bg-gray-50 p-1">"{{ $leave->hcs_notes }}"</p>
                                            @endif
                                        </div>
                                        <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                            {{ $leave->hcs_approved_at ? $leave->hcs_approved_at->format('d M H:i') : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Contextual Actions -->
            @php $user = auth()->user(); @endphp
            @if($leave->status === 'pending' && ($leave->supervisor_id === $user->id || $leave->hcs_id === $user->id || $leave->delegate_to === $user->id))
            <div class="bg-blue-50 border border-blue-200 shadow rounded-lg p-6">
                <h3 class="text-sm font-bold text-blue-900 mb-4 flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    Process Your Action
                </h3>
                <form action="{{ route('leaves.process', $leave) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="notes" class="block text-xs font-medium text-gray-700">Approver Notes</label>
                        <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" name="action" value="approve" class="flex-1 bg-green-600 text-white rounded-md py-2 text-sm font-semibold hover:bg-green-700 shadow-sm transition">Approve</button>
                        <button type="submit" name="action" value="reject" class="flex-1 bg-red-600 text-white rounded-md py-2 text-sm font-semibold hover:bg-red-700 shadow-sm transition">Reject</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
