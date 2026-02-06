@extends('layouts.app')

@section('title', 'Buat Surat Perintah Lembur')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Form Surat Perintah Lembur
            </h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Isi detail surat perintah lembur berikut.</p>
            </div>
            
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: solid/x-circle -->
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                There were {{ $errors->count() }} errors with your submission
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('overtimes.store') }}" method="POST" class="mt-5 space-y-6">
                @csrf
                
                <!-- Mandate Header -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="date" class="block text-sm font-medium text-gray-700">Tanggal Lembur (Hari/Tgl)</label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="sm:col-span-3">
                         <label for="division_id" class="block text-sm font-medium text-gray-700">Bagian (Divisi)</label>
                         <select id="division_id" name="division_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">-- Pilih Bagian --</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('division_id', auth()->user()->division_id) == $div->id ? 'selected' : '' }}>
                                    {{ $div->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="task_description" class="block text-sm font-medium text-gray-700">Untuk Mengerjakan (Tugas)</label>
                        <textarea id="task_description" name="task_description" rows="3" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('task_description') }}</textarea>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-900">Daftar Karyawan Lembur</h4>
                        <button type="button" onclick="addEmployeeRow()" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            + Tambah Karyawan
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="employees-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Realisasi (Dari)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Realisasi (Sampai)</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Hapus</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="employees-body">
                                <!-- Dynamic Rows will be added here -->
                                {{-- If old input exists (validation fail), enable re-populating --}}
                                @if(old('employees'))
                                    @foreach(old('employees') as $index => $oldEmp)
                                        <tr id="row-{{ $index }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                 <select name="employees[{{ $index }}][employee_id]" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->id }}" {{ $oldEmp['employee_id'] == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="time" name="employees[{{ $index }}][start_time]" value="{{ $oldEmp['start_time'] }}" class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="time" name="employees[{{ $index }}][end_time]" value="{{ $oldEmp['end_time'] }}" class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" onclick="removeRow({{ $index }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('overtimes.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Simpan Perintah Lembur
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="employee-row-template">
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
             <select name="employees[INDEX][employee_id]" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="">-- Pilih --</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="time" name="employees[INDEX][start_time]" class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="time" name="employees[INDEX][end_time]" class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <button type="button" class="text-red-600 hover:text-red-900 remove-btn">Hapus</button>
        </td>
    </tr>
</template>

<script>
    let rowIdx = {{ old('employees') ? count(old('employees')) : 0 }};

    function addEmployeeRow() {
        const template = document.getElementById('employee-row-template');
        const clone = template.content.cloneNode(true);
        const tbody = document.getElementById('employees-body');
        
        // Replace INDEX placeholder with current index
        const rowHtml = clone.querySelector('tr');
        rowHtml.id = `row-${rowIdx}`;
        rowHtml.innerHTML = rowHtml.innerHTML.replace(/INDEX/g, rowIdx);
        
        // Setup remove button
        const removeBtn = rowHtml.querySelector('.remove-btn');
        removeBtn.setAttribute('onclick', `removeRow(${rowIdx})`);
        
        tbody.appendChild(rowHtml);
        rowIdx++;
    }

    function removeRow(id) {
        const row = document.getElementById(`row-${id}`);
        if(row) row.remove();
    }

    // Add first row by default if empty
    if(rowIdx === 0) {
        addEmployeeRow();
    }
</script>
@endsection
