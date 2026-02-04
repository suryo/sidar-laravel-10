@extends('layouts.app')

@section('title', 'Holiday Management')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <!-- List Holidays -->
        <div class="md:col-span-2">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Holiday Calendar</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Dates that are excluded from working days calculation.</p>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($holidays as $holiday)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 rounded-md p-2">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-bold text-gray-900">{{ $holiday->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $holiday->date->format('l, d F Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                             @if($holiday->is_national_holiday)
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mr-4">
                                National Holiday
                            </span>
                            @endif
                            <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Remove</button>
                            </form>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-8 text-center text-gray-500">No holidays defined yet.</li>
                    @endforelse
                </ul>
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $holidays->links() }}
                </div>
            </div>
        </div>

        <!-- Add Form -->
        <div class="mt-5 md:mt-0 md:col-span-1">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Holiday</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('holidays.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Holiday Name</label>
                                <input type="text" name="name" id="name" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="date" id="date" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_national_holiday" name="is_national_holiday" type="checkbox" value="1" checked class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_national_holiday" class="font-medium text-gray-700">National Holiday</label>
                                    <p class="text-gray-500">Uncheck for company-specific holidays.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Add Holiday
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
