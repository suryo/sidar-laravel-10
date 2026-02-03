@extends('layouts.app')

@section('title', 'Claim Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Claim {{ $claim->claim_number }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Submitted on {{ $claim->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <div>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                    @if($claim->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($claim->status === 'approved_supervisor') bg-blue-100 text-blue-800
                    @elseif($claim->status === 'approved_hcs') bg-indigo-100 text-indigo-800
                    @elseif($claim->status === 'paid') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                </span>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Employee</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $claim->employee->name }} ({{ $claim->employee->nik }})</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $claim->type_label }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-bold sm:mt-0 sm:col-span-2">{{ $claim->formatted_amount }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 nl2br">{{ $claim->description }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Proof of Payment</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($claim->proof_file)
                            <div class="border rounded-lg p-2 bg-white inline-block">
                                @if(Str::endsWith($claim->proof_file, '.pdf'))
                                    <a href="{{ Storage::url($claim->proof_file) }}" target="_blank" class="flex items-center text-primary-600 hover:text-primary-800">
                                        <svg class="h-8 w-8 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                        View PDF Receipt
                                    </a>
                                @else
                                    <a href="{{ Storage::url($claim->proof_file) }}" target="_blank">
                                        <img src="{{ Storage::url($claim->proof_file) }}" alt="Receipt" class="max-w-sm max-h-64 object-contain">
                                    </a>
                                @endif
                            </div>
                        @else
                            <span class="text-red-500 italic">No proof uploaded</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Timeline / Approval History -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Approval History</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <ul class="space-y-4">
                <!-- Supervisor -->
                <li class="relative pb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center 
                            {{ $claim->supervisor_approved_at ? 'bg-green-500' : ($claim->supervisor_id ? 'bg-gray-300' : 'bg-gray-200') }} text-white">
                            @if($claim->supervisor_approved_at) ✓ @else 1 @endif
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-900">Supervisor Approval</h4>
                            @if($claim->supervisor_approved_at)
                                <p class="text-sm text-green-600">
                                    Approved by {{ $claim->supervisor->name ?? 'Supervisor' }} on {{ $claim->supervisor_approved_at->format('d M Y H:i') }}
                                </p>
                                @if($claim->supervisor_notes)
                                    <p class="text-sm text-gray-500 italic">"{{ $claim->supervisor_notes }}"</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Pending Supervisor Review</p>
                            @endif
                        </div>
                    </div>
                </li>
                
                <!-- HCS -->
                <li class="relative pb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center 
                            {{ $claim->hcs_approved_at ? 'bg-green-500' : 'bg-gray-200' }} text-white">
                            @if($claim->hcs_approved_at) ✓ @else 2 @endif
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-900">HCS Verification</h4>
                            @if($claim->hcs_approved_at)
                                <p class="text-sm text-green-600">
                                    Verified by {{ $claim->hcs->name ?? 'HCS' }} on {{ $claim->hcs_approved_at->format('d M Y H:i') }}
                                </p>
                                @if($claim->hcs_notes)
                                    <p class="text-sm text-gray-500 italic">"{{ $claim->hcs_notes }}"</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Pending Verification</p>
                            @endif
                        </div>
                    </div>
                </li>

                <!-- Finance -->
                <li class="relative">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center 
                            {{ $claim->finance_processed_at ? 'bg-green-500' : 'bg-gray-200' }} text-white">
                            @if($claim->finance_processed_at) ✓ @else 3 @endif
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-900">Finance Payment</h4>
                            @if($claim->finance_processed_at)
                                <p class="text-sm text-green-600">
                                    Processed (Paid) on {{ $claim->finance_processed_at->format('d M Y H:i') }}
                                </p>
                                @if($claim->finance_notes)
                                    <p class="text-sm text-gray-500 italic">"{{ $claim->finance_notes }}"</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Pending Payment</p>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
            
            @if($claim->status === 'rejected')
            <div class="mt-6 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Rejected</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Reason: {{ $claim->rejection_note }}</p>
                            <p class="text-xs mt-1">Rejected by ID: {{ $claim->rejected_by }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
