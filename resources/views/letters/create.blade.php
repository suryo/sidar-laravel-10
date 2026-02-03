@extends('layouts.app')

@section('title', 'Draft New Letter')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Create Letter Draft</h3>
            <p class="mt-1 text-sm text-gray-500">Select a template and fill in the details.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('letters.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-y-6">
                    <div>
                        <label for="template_id" class="block text-sm font-medium text-gray-700">Template</label>
                        <select id="template_id" name="template_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }} ({{ $template->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="recipient_name" class="block text-sm font-medium text-gray-700">Recipient Name</label>
                        <div class="mt-1">
                            <input type="text" name="recipient_name" id="recipient_name" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="e.g. John Doe">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject / Re</label>
                        <div class="mt-1">
                            <input type="text" name="subject" id="subject" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="e.g. Surat Peringatan">
                        </div>
                    </div>

                    <!-- Dynamic Fields Section -->
                    <div class="border-t border-gray-200 pt-4 mt-2">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Additional Data</h4>
                        <p class="text-xs text-gray-500 mb-4">Fill these if your template uses them.</p>
                        
                        <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4">
                            <div>
                                <label for="data_reason" class="block text-sm font-medium text-gray-700">Reason (Alasan)</label>
                                <input type="text" name="data[reason]" id="data_reason" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="For SP/Warning">
                            </div>
                            <div>
                                <label for="data_duration" class="block text-sm font-medium text-gray-700">Duration (Lama Kontrak)</label>
                                <input type="text" name="data[duration]" id="data_duration" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="For PKWT">
                            </div>
                             <div>
                                <label for="data_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="data[start_date]" id="data_start_date" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                             <div>
                                <label for="data_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="data[end_date]" id="data_end_date" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <a href="{{ route('letters.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Create Draft
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
