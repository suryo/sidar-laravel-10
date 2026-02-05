@extends('layouts.app')

@section('title', 'Create DAR - SIDAR HRIS')

@section('content')
<div class="px-4 sm:px-0">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create New DAR</h1>
        <p class="mt-1 text-sm text-gray-600">Fill in your daily activity report</p>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        
        <!-- Pending DAR Alert -->
        @if(isset($pending_dar_count) && $pending_dar_count > 0)
        <div class="bg-red-600 text-white px-4 py-2 text-sm font-semibold text-center mb-0 rounded-t-lg">
            Belum Kirim DAR {{ $pending_dar_count }}
        </div>
        @endif

        <div class="flex justify-end pt-4 px-6 pb-0">
             <button type="button" onclick="fillAbsence()" class="inline-flex items-center px-3 py-1.5 border border-red-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                ABSENCE
            </button>
        </div>

        <form action="{{ route('dars.store') }}" method="POST" class="p-6 space-y-6 pt-2">
            @csrf

            <!-- DAR Date -->
            <div>
                <label for="dar_date" class="block text-sm font-medium text-gray-700">DAR Date <span class="text-red-500">*</span></label>
                
                @if(isset($missingDates) && count($missingDates) > 0)
                <select id="dar_date" name="dar_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    @foreach($missingDates as $date)
                        <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('d F Y (l)') }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Only showing missing DAR dates.</p>
                @else
                <div class="mt-1 p-3 bg-green-50 text-green-700 rounded-md border border-green-200">
                    You have no pending DARs. Great job!
                </div>
                <input type="hidden" name="dar_date" value=""> <!-- Prevent form submit error manually or just let it fail validation if specific handling is better. Actually if no date, they can't submit. -->
                @endif
                
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
                >{{ old('activity') }}</textarea>
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
                >{{ old('result') }}</textarea>
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
                >{{ old('plan') }}</textarea>
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
                    value="{{ old('tag') }}"
                    placeholder="e.g., development, meeting, training"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('tag') border-red-500 @enderror"
                >
                @error('tag')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('dars.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Create DAR
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
        const editors = {};
        const form = document.querySelector('form');

        ['activity', 'result', 'plan'].forEach(id => {
            ClassicEditor
                .create(document.querySelector(`#${id}`), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo']
                })
                .then(editor => {
                    editors[id] = editor;
                    // Fix: Update textarea on change to satisfy "required" if browser check runs, 
                    // though usually we rely on backend. 
                    // Removing 'required' from textarea via JS is safer to avoid browser blocking hidden fields.
                    document.querySelector(`#${id}`).removeAttribute('required');
                    
                    editor.model.document.on('change:data', () => {
                        document.querySelector(`#${id}`).value = editor.getData();
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        });

        // Ensure data is synced anyway on submit
        form.addEventListener('submit', function() {
            for (const id in editors) {
                document.querySelector(`#${id}`).value = editors[id].getData();
            }
        });

        // Expose function for the Absence button
        window.fillAbsence = function() {
             const defaultText = '<p>Tidak masuk kantor / Cuti / Sakit</p>';
             
             // Update CKEditors
             for (const id in editors) {
                 editors[id].setData(defaultText);
             }
             
             // Update Tag
             const tagInput = document.getElementById('tag');
             if(tagInput) tagInput.value = 'Absence';
        };
    });
</script>
@endsection
