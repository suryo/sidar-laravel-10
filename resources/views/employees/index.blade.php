@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <!-- Header -->
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Employees</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage workforce accounts and data.</p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('employees.index') }}" class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name/NIK..." class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-l-md">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 shadow-sm text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100">
                    Search
                </button>
            </form>
            <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Employee
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name / NIK</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role & Dept</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($employees as $employee)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold">
                                {{ substr($employee->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                <div class="text-xs text-gray-500">{{ $employee->email }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $employee->nik }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $employee->role->name ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $employee->department->name ?? '-' }}</div>
                        <div class="text-xs text-gray-400">{{ $employee->division->name ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($employee->status === 'active') bg-green-100 text-green-800
                            @elseif($employee->status === 'inactive') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $employee->join_date ? $employee->join_date->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('employees.edit', $employee) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        
                        @if($employee->id !== auth()->id())
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Deactivate this employee?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none cursor-pointer">Deactivate</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">No employees found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $employees->links() }}
    </div>
</div>
@endsection
