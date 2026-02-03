@extends('layouts.app')

@section('title', 'Pending Approvals - SIDAR HRIS')

@section('content')
<div class="px-4 sm:px-0">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Leave Approvals</h1>
        <p class="mt-1 text-sm text-gray-600">Review and process pending leave requests from your team</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="ml-3 font-medium text-green-700">
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($pendingApprovals->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Process</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pendingApprovals as $leave)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                    {{ substr($leave->employee->name, 0, 2) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $leave->employee->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $leave->employee->position }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">
                            {{ $leave->type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $leave->total_days }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                            {{ $leave->reason }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form action="{{ route('leaves.process', $leave) }}" method="POST" class="inline-block flex gap-1">
                                @csrf
                                <input type="text" name="notes" placeholder="Notes (optional)" class="text-xs border-gray-300 rounded px-2 py-1 w-24 focus:ring-primary-500">
                                <button type="submit" name="action" value="approve" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1 rounded text-xs transition duration-150">Approve</button>
                                <button type="submit" name="action" value="reject" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded text-xs transition duration-150">Reject</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $pendingApprovals->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">All caught up!</h3>
            <p class="mt-1 text-sm text-gray-500">No pending leave requests to approve.</p>
        </div>
        @endif
    </div>
</div>
@endsection
