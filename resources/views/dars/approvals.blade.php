@extends('layouts.app')

@section('title', 'Pending DAR Approvals')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Pending Approvals</h1>
    </div>

    @if($dars->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($dars as $dar)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $dar->dar_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $dar->employee->name }}</div>
                            <div class="text-sm text-gray-500">{{ $dar->employee->position ?? $dar->employee->role->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="line-clamp-2">{!! strip_tags($dar->activity) !!}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('dars.show', $dar->id) }}" class="text-primary-600 hover:text-primary-900">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $dars->links() }}
        </div>
    </div>
    @else
    <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="mt-4 text-lg font-medium text-gray-900">All caught up!</p>
        <p>No pending approvals found.</p>
    </div>
    @endif
</div>
@endsection
