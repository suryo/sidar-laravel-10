@extends('layouts.app')

@section('title', 'Create Location')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Create New Location</h3>
    </div>
    <form action="{{ route('master.locations.store') }}" method="POST" class="px-4 py-5 sm:p-6 space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Location Name</label>
            <input type="text" name="name" id="name" required class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                <input type="number" step="any" name="latitude" id="latitude" required class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                <input type="number" step="any" name="longitude" id="longitude" required class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>
        <div>
            <label for="radius" class="block text-sm font-medium text-gray-700">Radius (meters)</label>
            <input type="number" name="radius" id="radius" value="100" required class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <p class="mt-1 text-xs text-gray-500">Allowed distance in meters for attendance.</p>
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <textarea name="address" id="address" rows="3" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('master.locations.index') }}" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">Cancel</a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
