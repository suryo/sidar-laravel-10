@extends('layouts.app')

@section('title', 'Create Access Area')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Create New Access Area</h3>
    </div>
    <form action="{{ route('master.access-areas.store') }}" method="POST" class="px-4 py-5 sm:p-6 space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" required class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
        </div>
        <div>
            <label for="color_code" class="block text-sm font-medium text-gray-700">Color Code (Hex/Name)</label>
            <div class="mt-1 flex rounded-md shadow-sm">
                 <input type="text" name="color_code" id="color_code" placeholder="#RRGGBB" class="focus:ring-primary-500 focus:border-primary-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300">
                 <input type="color" onchange="document.getElementById('color_code').value = this.value" class="h-9 w-12 border-gray-300 rounded-r-md cursor-pointer p-1">
            </div>
            <p class="mt-1 text-xs text-gray-500">Pick a color or enter hex code.</p>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="3" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('master.access-areas.index') }}" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">Cancel</a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
