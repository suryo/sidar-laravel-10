@extends('layouts.app')

@section('title', 'DAR Details - SIDAR HRIS')

@section('content')
<div class="px-4 sm:px-0">
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">DAR Details</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $dar->dar_number }}</p>
        </div>
        <a href="{{ route('dars.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- DAR Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">DAR Information</h2>
                
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dar->dar_date->format('d F Y') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Activity</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{!! $dar->activity !!}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Result</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{!! $dar->result !!}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Plan for Tomorrow</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{!! $dar->plan !!}</dd>
                    </div>
                    
                    @if($dar->tag)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tag</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $dar->tag }}
                            </span>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Actions (for approvers) -->
            @if($canApprove)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Approval Actions</h2>
                
                <div class="flex space-x-3">
                    <form action="{{ route('dars.approve', $dar->id) }}" method="POST" class="flex-1">
                        @csrf
                        <div class="mb-3">
                            <label for="approve_notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                            <textarea name="notes" id="approve_notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Add approval notes..."></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Approve
                        </button>
                    </form>
                    
                    <form action="{{ route('dars.reject', $dar->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to reject this DAR?')">
                        @csrf
                        <div class="mb-3">
                            <label for="reject_notes" class="block text-sm font-medium text-gray-700">Rejection Notes <span class="text-red-500">*</span></label>
                            <textarea name="notes" id="reject_notes" rows="2" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Reason for rejection..."></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reject
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Status</h3>
                @if($dar->status === 'approved')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Approved
                </span>
                @elseif($dar->status === 'rejected')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    Rejected
                </span>
                @elseif($dar->status === 'draft')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    Draft
                </span>
                @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    <svg class="-ml-1 mr-2 h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Pending Approval
                </span>
                @endif
            </div>

            <!-- Approval Timeline -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-4">Approval Timeline</h3>
                
                <div class="flow-root">
                    <ul class="-mb-8">
                        @if($dar->supervisor_id)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                            {{ $dar->supervisor_status === 'approved' ? 'bg-green-500' : ($dar->supervisor_status === 'rejected' ? 'bg-red-500' : 'bg-gray-300') }}">
                                            @if($dar->supervisor_status === 'approved')
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            @elseif($dar->supervisor_status === 'rejected')
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <p class="text-sm text-gray-500">Supervisor</p>
                                        <p class="text-xs text-gray-400">{{ $dar->supervisor?->name }}</p>
                                        @if($dar->supervisor_notes)
                                        <p class="mt-1 text-xs text-gray-600 italic">{{ $dar->supervisor_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif

                        @if($dar->manager_id)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                            {{ $dar->manager_status === 'approved' ? 'bg-green-500' : ($dar->manager_status === 'rejected' ? 'bg-red-500' : 'bg-gray-300') }}">
                                            @if($dar->manager_status === 'approved')
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <p class="text-sm text-gray-500">Manager</p>
                                        <p class="text-xs text-gray-400">{{ $dar->manager?->name }}</p>
                                        @if($dar->manager_notes)
                                        <p class="mt-1 text-xs text-gray-600 italic">{{ $dar->manager_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif

                        @if($dar->director_id)
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                            {{ $dar->director_status === 'approved' ? 'bg-green-500' : ($dar->director_status === 'rejected' ? 'bg-red-500' : 'bg-gray-300') }}">
                                            @if($dar->director_status === 'approved')
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <p class="text-sm text-gray-500">Director</p>
                                        <p class="text-xs text-gray-400">{{ $dar->director?->name }}</p>
                                        @if($dar->director_notes)
                                        <p class="mt-1 text-xs text-gray-600 italic">{{ $dar->director_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Metadata</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $dar->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $dar->updated_at->format('d M Y H:i') }}</dd>
                    </div>
                    @if($dar->submitted_at)
                    <div>
                        <dt class="text-xs text-gray-500">Submitted</dt>
                        <dd class="text-sm text-gray-900">{{ $dar->submitted_at->format('d M Y H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
