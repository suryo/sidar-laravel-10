@extends('layouts.app')

@section('title', 'Letter Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Controls -->
    <div class="mb-6 flex justify-between items-center print:hidden">
        <div>
            <a href="{{ route('letters.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
        </div>
        <div class="flex space-x-3">
             <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print PDF
            </button>

            @if($letter->status === 'pending' && (auth()->user()->role->can_approve || auth()->user()->role->is_admin))
            <form action="{{ route('letters.approve', $letter) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Approve & Generate Number
                </button>
            </form>
            <form action="{{ route('letters.reject', $letter) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    Reject
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Letter Preview (A4 Paper Style) -->
    <div class="bg-white shadow-lg p-12 min-h-[1000px] print:shadow-none print:p-0" style="width: 210mm; margin: 0 auto;">
        
        <!-- Header / Kop Surat Placeholder -->
        <div class="border-b-4 border-double border-black pb-4 mb-8 text-center print:border-black">
            <h1 class="text-2xl font-bold uppercase tracking-widest">PT. SIDAR INDRACO</h1>
            <p class="text-sm text-gray-600">Jl. Contoh Raya No. 123, Jakarta Selatan</p>
            <p class="text-sm text-gray-600">Telp: (021) 123-4567 | Email: hr@sidar.id</p>
        </div>

        <!-- Content -->
        <div class="prose max-w-none text-justify font-serif text-gray-900 leading-relaxed">
            {!! $letter->content !!}
        </div>

        <!-- Footer / Signature -->
        <div class="mt-16 print:mt-16">
            <!-- Content usually has signature area, but if not we can append here -->
        </div>

        @if($letter->status === 'draft' || $letter->status === 'pending')
        <div class="mt-8 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 print:hidden text-center text-sm font-bold uppercase tracking-wide">
            DRAFT PREVIEW - NOT OFFICIAL
        </div>
        @endif
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .max-w-4xl, .max-w-4xl * {
            visibility: visible;
        }
        .max-w-4xl {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection
