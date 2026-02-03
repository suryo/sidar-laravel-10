@extends('layouts.app')

@section('title', 'Attendance - SIDAR HRIS')

@section('content')
<div class="px-4 sm:px-0">
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Attendance</h1>
            <p class="mt-1 text-sm text-gray-600">Mark your daily attendance</p>
        </div>
        <a href="{{ route('attendance.history') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            View History
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Panel: Clock & Buttons -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <div class="mb-4">
                    <h2 id="current-day" class="text-xl font-medium text-gray-600">{{ now()->format('l, d F Y') }}</h2>
                    <div id="clock" class="text-6xl font-bold text-primary-600 my-4">--:--:--</div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                    <!-- Check In Section -->
                    <div class="p-6 border rounded-lg {{ $attendance && $attendance->check_in_time ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Check In</h3>
                        @if($attendance && $attendance->check_in_time)
                            <p class="text-3xl font-bold text-green-600">{{ $attendance->check_in_time->format('H:i') }}</p>
                            <p class="text-sm text-gray-500 mt-2">Successfully checked in</p>
                        @else
                            <form action="{{ route('attendance.check-in') }}" method="POST" id="check-in-form">
                                @csrf
                                <input type="hidden" name="latitude" id="lat-in">
                                <input type="hidden" name="longitude" id="lng-in">
                                
                                <div class="mb-4">
                                    <label for="work_type" class="block text-sm font-medium text-gray-700 text-left">Work Type</label>
                                    <select name="work_type" id="work_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                        <option value="wfo">WFO (Office)</option>
                                        <option value="wfh">WFH (Home)</option>
                                        <option value="outside">Outside Service</option>
                                    </select>
                                </div>

                                <button type="submit" class="w-full bg-primary-600 text-white font-bold py-3 px-4 rounded-md hover:bg-primary-700 transition duration-150">
                                    MARK CHECK IN
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Check Out Section -->
                    <div class="p-6 border rounded-lg {{ $attendance && $attendance->check_out_time ? 'bg-blue-50 border-blue-200' : ($attendance && $attendance->check_in_time ? 'bg-gray-50 border-gray-200' : 'bg-gray-100 border-gray-200 opacity-50') }}">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Check Out</h3>
                        @if($attendance && $attendance->check_out_time)
                            <p class="text-3xl font-bold text-blue-600">{{ $attendance->check_out_time->format('H:i') }}</p>
                            <p class="text-sm text-gray-500 mt-2">Successfully checked out</p>
                        @else
                            <form action="{{ route('attendance.check-out') }}" method="POST" id="check-out-form">
                                @csrf
                                <input type="hidden" name="latitude" id="lat-out">
                                <input type="hidden" name="longitude" id="lng-out">
                                
                                <button type="submit" 
                                    {{ !$attendance || !$attendance->check_in_time ? 'disabled' : '' }}
                                    class="w-full bg-gray-600 text-white font-bold py-3 px-4 rounded-md hover:bg-gray-700 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                                    MARK CHECK OUT
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Today's Stats -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Location & Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-md">
                        <div class="bg-primary-100 p-2 rounded-full">
                            <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Detection</p>
                            <p id="location-status" class="text-sm text-gray-900">Waiting for location...</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-md">
                        <div class="bg-yellow-100 p-2 rounded-full">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Shift Type</p>
                            <p class="text-sm text-gray-900 capitalize">{{ $attendance->work_type ?? 'Standard' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Recent Logs -->
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-4 uppercase tracking-wider">Instructions</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start">
                        <span class="text-primary-600 mr-2">•</span>
                        Allow location access when requested.
                    </li>
                    <li class="flex items-start">
                        <span class="text-primary-600 mr-2">•</span>
                        Check in before 09:00 AM to avoid being marked late.
                    </li>
                    <li class="flex items-start">
                        <span class="text-primary-600 mr-2">•</span>
                        Check out after 17:00 PM or after completing 8 working hours.
                    </li>
                </ul>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-4 uppercase tracking-wider">Attendance Status</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Status</p>
                        @if($attendance)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full capitalize 
                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $attendance->status }} 
                                @if($attendance->check_in_status === 'late') (Late) @endif
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Not Marked</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Live Clock
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').textContent = `${h}:${m}:${s}`;
    }
    
    setInterval(updateClock, 1000);
    updateClock();

    // Geolocation
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            document.getElementById('lat-in')?.setAttribute('value', lat);
            document.getElementById('lng-in')?.setAttribute('value', lng);
            document.getElementById('lat-out')?.setAttribute('value', lat);
            document.getElementById('lng-out')?.setAttribute('value', lng);
            
            document.getElementById('location-status').textContent = `Detected: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        }, function(error) {
            document.getElementById('location-status').textContent = "Location access denied. Please enable GPS.";
            document.getElementById('location-status').classList.add('text-red-500');
        });
    } else {
        document.getElementById('location-status').textContent = "Geolocation not supported by browser.";
    }
</script>
@endsection
