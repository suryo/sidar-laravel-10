@extends('layouts.app')

@section('title', 'Buat Pengajuan Lupa Check Clock')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Form Lupa Check Clock
            </h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Silakan lengkapi form di bawah ini jika Anda lupa melakukan check clock (masuk/pulang).</p>
            </div>
            <form action="{{ route('forgot-clocks.store') }}" method="POST" class="mt-5 space-y-6">
                @csrf
                
                <!-- Readonly User Info -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Nama Karyawan</label>
                        <input type="text" disabled value="{{ $employee->name }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50 text-gray-500">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" disabled value="{{ $employee->nik }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50 text-gray-500">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Departemen</label>
                        <input type="text" disabled value="{{ $employee->department->name ?? '-' }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50 text-gray-500">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Bagian (Divisi)</label>
                        <input type="text" disabled value="{{ $employee->division->name ?? '-' }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50 text-gray-500">
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6"></div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="sm:col-span-3">
                         <label for="clock_type" class="block text-sm font-medium text-gray-700">Jenis Check Clock</label>
                         <select id="clock_type" name="clock_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="in">Masuk (Clock In)</option>
                            <option value="out">Pulang (Clock Out)</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="clock_time" class="block text-sm font-medium text-gray-700">Jam Check Clock</label>
                        <input type="time" name="clock_time" id="clock_time" required class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="sm:col-span-6">
                        <label for="reason" class="block text-sm font-medium text-gray-700">Alasan Lupa</label>
                        <textarea id="reason" name="reason" rows="3" required class="shadow-sm focus:ring-primary-500 focus:border-primary-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('forgot-clocks.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Kirim Pengajuan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
