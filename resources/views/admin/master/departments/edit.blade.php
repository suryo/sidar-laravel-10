@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Department</h3>
    </div>
    <form action="{{ route('master.departments.update', $department) }}" method="POST" class="px-4 py-5 sm:p-6 space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
            <input type="text" name="code" id="code" value="{{ old('code', $department->code) }}" required class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
        </div>
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}" required class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="3" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description', $department->description) }}</textarea>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('master.departments.index') }}" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">Cancel</a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
