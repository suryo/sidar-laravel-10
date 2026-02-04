@extends('layouts.app')

@section('title', 'Edit DAR - SIDAR HRIS')

@section('content')
<div class="px-4 sm:px-0">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit DAR</h1>
        <p class="mt-1 text-sm text-gray-600">{{ $dar->dar_number }}</p>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('dars.update', $dar->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- DAR Date -->
            <div>
                <label for="dar_date" class="block text-sm font-medium text-gray-700">DAR Date <span class="text-red-500">*</span></label>
                <input 
                    type="date" 
                    name="dar_date" 
                    id="dar_date" 
                    value="{{ old('dar_date', $dar->dar_date->format('Y-m-d')) }}"
                    max="{{ date('Y-m-d') }}"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('dar_date') border-red-500 @enderror"
                >
                @error('dar_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Activity -->
            <div>
                <label for="activity" class="block text-sm font-medium text-gray-700">Activity <span class="text-red-500">*</span></label>
                <textarea 
                    name="activity" 
                    id="activity" 
                    rows="4" 
                    required
                    placeholder="Describe what you did today..."
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('activity') border-red-500 @enderror"
                >{{ old('activity', $dar->activity) }}</textarea>
                @error('activity')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Minimum 10 characters</p>
            </div>

            <!-- Result -->
            <div>
                <label for="result" class="block text-sm font-medium text-gray-700">Result <span class="text-red-500">*</span></label>
                <textarea 
                    name="result" 
                    id="result" 
                    rows="4" 
                    required
                    placeholder="What was the outcome or result of your activity?"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('result') border-red-500 @enderror"
                >{{ old('result', $dar->result) }}</textarea>
                @error('result')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Minimum 10 characters</p>
            </div>

            <!-- Plan -->
            <div>
                <label for="plan" class="block text-sm font-medium text-gray-700">Plan for Tomorrow <span class="text-red-500">*</span></label>
                <textarea 
                    name="plan" 
                    id="plan" 
                    rows="4" 
                    required
                    placeholder="What do you plan to do tomorrow?"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('plan') border-red-500 @enderror"
                >{{ old('plan', $dar->plan) }}</textarea>
                @error('plan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Minimum 10 characters</p>
            </div>

            <!-- Tag (Optional) -->
            <div>
                <label for="tag" class="block text-sm font-medium text-gray-700">Tag (Optional)</label>
                <input 
                    type="text" 
                    name="tag" 
                    id="tag" 
                    value="{{ old('tag', $dar->tag) }}"
                    placeholder="e.g., development, meeting, training"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('tag') border-red-500 @enderror"
                >
                @error('tag')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('dars.show', $dar->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update DAR
                </button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 150px;
    }
    .ck-powered-by {
        display: none !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editors = ['activity', 'result', 'plan'];
        
        editors.forEach(id => {
            ClassicEditor
                .create(document.querySelector(`#${id}`), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo']
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>
@endsection
