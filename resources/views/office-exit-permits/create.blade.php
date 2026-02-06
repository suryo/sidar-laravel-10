@extends('layouts.app')

@section('title', 'Buat Pengajuan Ijin Meninggalkan Kantor')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Form Ijin Meninggalkan Kantor
            </h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Silakan lengkapi form di bawah ini untuk pengajuan ijin keluar kantor.</p>
            </div>
            <form action="{{ route('office-exit-permits.store') }}" method="POST" class="mt-5 space-y-6">
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
                        <label for="date" class="block text-sm font-medium text-gray-700">Tanggal Ijin</label>
                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="sm:col-span-3">
                        <label for="out_time" class="block text-sm font-medium text-gray-700">Keluar Pukul</label>
                        <input type="time" name="out_time" id="out_time" required class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="sm:col-span-3">
                         <label for="exit_type" class="block text-sm font-medium text-gray-700">Jenis Meninggalkan Kantor</label>
                         <select id="exit_type" name="exit_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="dinas_luar">Dinas Luar</option>
                            <option value="personal">Meninggalkan Pekerjaan</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                         <label for="purpose" class="block text-sm font-medium text-gray-700">Keperluan</label>
                         <select id="purpose" name="purpose" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="dinas_luar">Dinas Luar</option>
                            <option value="pribadi">Pribadi</option>
                        </select>
                    </div>

                     <div class="sm:col-span-3">
                         <label for="is_returning" class="block text-sm font-medium text-gray-700">Kembali ke Kantor?</label>
                         <select id="is_returning" name="is_returning" onchange="toggleReturnTime()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="1">Ya, Kembali</option>
                            <option value="0">Tidak Kembali</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3" id="return_time_container">
                        <label for="return_time" class="block text-sm font-medium text-gray-700">Jam Kembali</label>
                        <input type="time" name="return_time" id="return_time" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="sm:col-span-6">
                        <label for="reason" class="block text-sm font-medium text-gray-700">Keterangan / Alasan</label>
                        <textarea id="reason" name="reason" rows="3" required class="shadow-sm focus:ring-primary-500 focus:border-primary-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('office-exit-permits.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
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

<script>
    function toggleReturnTime() {
        var isReturning = document.getElementById('is_returning').value;
        var returnTimeContainer = document.getElementById('return_time_container');
        var returnTimeInput = document.getElementById('return_time');
        
        if (isReturning == '1') {
            returnTimeContainer.style.display = 'block';
            returnTimeInput.required = true;
        } else {
            returnTimeContainer.style.display = 'none';
            returnTimeInput.required = false;
            returnTimeInput.value = '';
        }
    }
    // Init
    toggleReturnTime();
</script>
@endsection
