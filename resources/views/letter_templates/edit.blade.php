@extends('layouts.app')

@section('title', 'Edit Template')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Template: {{ $letterTemplate->name }}</h3>
            <p class="mt-1 text-sm text-gray-500">Use placeholders like [NAME], [DATE], [REASON] for dynamic content.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('letter-templates.update', $letterTemplate) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Template Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $letterTemplate->name) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $letterTemplate->code) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="col-span-6">
                        <label for="content" class="block text-sm font-medium text-gray-700">Content (HTML)</label>
                        <div class="mt-1">
                            <textarea id="content" name="content" rows="20" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md font-mono">{{ old('content', $letterTemplate->content) }}</textarea>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Available standard placeholders: [RECIPIENT_NAME], [DATE], [CREATOR_NAME], [CREATOR_POSITION], [LETTER_NUMBER]
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('letter-templates.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
