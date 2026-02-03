@extends('layouts.app')

@section('title', 'Request Leave - SIDAR HRIS')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-0">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Request Leave</h1>
        <p class="mt-1 text-sm text-gray-600">Please fill in the form carefully</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">Please fix the following errors:</p>
                <ul class="mt-1 list-disc list-inside text-sm text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <form action="{{ route('leaves.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type -->
                <div class="col-span-2">
                    <label for="type" class="block text-sm font-medium text-gray-700">Leave Type</label>
                    <select name="type" id="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="annual" {{ old('type') == 'annual' ? 'selected' : '' }}>Annual Leave (Cuti Tahunan)</option>
                        <option value="sick" {{ old('type') == 'sick' ? 'selected' : '' }}>Sick Leave (Sakit)</option>
                        <option value="permission" {{ old('type') == 'permission' ? 'selected' : '' }}>Permission (Izin)</option>
                        <option value="late" {{ old('type') == 'late' ? 'selected' : '' }}>Late Arrival (Izin Terlambat)</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other (Lainnya)</option>
                    </select>
                </div>

                <!-- Dates -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required value="{{ old('start_date', now()->format('Y-m-d')) }}" placeholder="Select start date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" required value="{{ old('end_date', now()->format('Y-m-d')) }}" placeholder="Select end date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>

                <!-- Late Time (Conditional) -->
                <div id="late-arrival-group" class="col-span-2 hidden">
                    <label for="late_arrival_time" class="block text-sm font-medium text-gray-700">Late Arrival Time</label>
                    <input type="time" name="late_arrival_time" id="late_arrival_time" value="{{ old('late_arrival_time') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>

                <!-- Reason -->
                <div class="col-span-2">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                    <textarea name="reason" id="reason" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('reason') }}</textarea>
                </div>

                <hr class="col-span-2 border-gray-200">

                <!-- Delegation -->
                <div class="col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Delegation (Optional)</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="delegate_to" class="block text-sm font-medium text-gray-700">Delegate Tasks To</label>
                            <select name="delegate_to" id="delegate_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="">No delegation</option>
                                @foreach($delegates as $delegate)
                                    <option value="{{ $delegate->id }}" {{ old('delegate_to') == $delegate->id ? 'selected' : '' }}>
                                        {{ $delegate->name }} ({{ $delegate->position }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="delegation_tasks" class="block text-sm font-medium text-gray-700">Delegation Task Details</label>
                            <textarea name="delegation_tasks" id="delegation_tasks" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('delegation_tasks') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('leaves.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-primary-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const lateGroup = document.getElementById('late-arrival-group');

        function toggleLateFields() {
            if (typeSelect.value === 'late') {
                lateGroup.classList.remove('hidden');
            } else {
                lateGroup.classList.add('hidden');
            }
        }

        typeSelect.addEventListener('change', toggleLateFields);
        toggleLateFields(); // Initial state
    });
</script>
@endsection
