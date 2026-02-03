@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Employee Summary Card -->
        <a href="{{ route('reports.employee-summary') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <h5 class="mb-1 text-2xl font-bold tracking-tight text-gray-900">Employee Summary</h5>
                    <p class="font-normal text-gray-700">Aggregate view of DARs, Attendance, and Leaves per employee.</p>
                </div>
            </div>
        </a>

        <!-- Gap Analysis Card -->
        <a href="{{ route('reports.gap-analysis') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
            <div class="flex items-center space-x-4">
                <div class="bg-amber-100 p-3 rounded-full text-amber-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h5 class="mb-1 text-2xl font-bold tracking-tight text-gray-900">Gap Analysis</h5>
                    <p class="font-normal text-gray-700">Identify missing DARs on days with verified Attendance.</p>
                </div>
            </div>
        </a>

        <!-- Placeholder Card -->
        <div class="block p-6 bg-gray-50 border border-gray-200 rounded-lg opacity-60">
            <div class="flex items-center space-x-4">
                <div class="bg-gray-200 p-3 rounded-full text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h5 class="mb-1 text-2xl font-bold tracking-tight text-gray-500">More Reports</h5>
                    <p class="font-normal text-gray-400">Coming soon (Payroll, Overtime, etc.)</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
