@extends('layouts.app')

@section('title', 'Detail Ijin Terlambat')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Ijin Terlambat</h1>
            <p class="mt-1 text-sm text-gray-600">Diajukan pada {{ $latePermission->created_at->format('d F Y H:i') }}</p>
        </div>
        <a href="{{ route('late-permissions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <!-- Details Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Pengajuan</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nama Karyawan</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $latePermission->employee->name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Tanggal Terlambat</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $latePermission->late_date->format('d F Y') }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Jam Masuk (Aktual/Perkiraan)</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($latePermission->arrival_time)->format('H:i') }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Terlambat Ke</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $latePermission->late_frequency }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Alasan</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ $latePermission->reason }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Bukti Ijin Tertulis</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($latePermission->proof_file)
                            <a href="{{ Storage::url($latePermission->proof_file) }}" target="_blank" class="font-medium text-primary-600 hover:text-primary-500">
                                Lihat Lampiran
                            </a>
                        @else
                            <span class="text-gray-400 italic">Tidak ada lampiran</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Status Card -->
     <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Status Persetujuan</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                 <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Supervisor</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $latePermission->approved_by_supervisor ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $latePermission->approved_by_supervisor ? 'Disetujui' : 'Menunggu Persetujuan' }}
                        </span>
                    </dd>
                </div>
                 <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">HCS / HRD</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $latePermission->acknowledged_by_hcs ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $latePermission->acknowledged_by_hcs ? 'Diketahui' : 'Menunggu Konfirmasi' }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
