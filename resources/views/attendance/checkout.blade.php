@extends('layouts.app')

@section('title', 'Absence Keluar')

@section('content')
<!-- Full Screen Camera Implementation -->
<div class="fixed inset-0 z-50 bg-black flex flex-col">
    <!-- Header Overlay -->
    <div class="absolute top-0 left-0 right-0 p-4 flex justify-between items-center bg-gradient-to-b from-black/70 to-transparent z-10 text-white">
        <div class="flex items-center">
            <h1 class="text-lg font-bold">INDRACO<span class="font-light">SIDAR</span></h1>
            <span class="mx-2">|</span>
            <span class="text-sm font-medium uppercase">Absence Keluar</span>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Message Icon -->
            <button id="message-btn" class="p-2 rounded-full hover:bg-white/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </button>
            <a href="{{ route('dashboard') }}" class="p-2 rounded-full hover:bg-white/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>
    </div>

    <!-- Camera Feed -->
    <div class="relative flex-1 bg-black flex items-center justify-center overflow-hidden">
        <video id="video" autoplay playsinline class="absolute inset-0 w-full h-full object-cover"></video>
        <canvas id="canvas" class="hidden"></canvas>
        <img id="photo-preview" class="absolute inset-0 w-full h-full object-cover hidden" alt="Captured Photo">
        
        <!-- Loading Indicator -->
        <div id="loading" class="absolute inset-0 flex items-center justify-center bg-black z-20">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
            <span class="ml-3 text-white">Initializing Camera...</span>
        </div>
    </div>

    <!-- Controls Overlay -->
    <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent z-10 flex flex-col items-center">
        <!-- Location Status -->
        <div id="location-status" class="mb-4 text-xs text-white/80 flex items-center">
            <svg class="w-3 h-3 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span id="location-text">Detecting Location...</span>
        </div>

        <!-- Capture Button -->
        <button id="capture-btn" class="mb-4 bg-white/20 border-4 border-white rounded-full h-20 w-20 flex items-center justify-center hover:bg-white/30 focus:outline-none transition-all duration-200">
            <div class="h-16 w-16 bg-white rounded-full"></div>
        </button>

        <!-- Submit Form (Hidden initially) -->
        <form id="attendance-form" action="{{ route('attendance.check-out') }}" method="POST" enctype="multipart/form-data" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-xs bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20 shadow-2xl z-50">
            @csrf
            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="lng">
            <input type="hidden" name="address" id="addr">
            <input type="hidden" name="photo" id="photo-data"> <!-- Base64 -->
            <input type="hidden" name="notes" id="hidden-notes"> <!-- Notes from Modal -->
            
            <div class="mb-3 text-center text-white">
                <p class="text-sm font-medium">Konfirmasi Absen Pulang?</p>
                <p class="text-xs text-white/70 mt-1">Pastikan Anda sudah selesai bekerja.</p>
            </div>

            <div class="flex space-x-2">
                <button type="button" id="retake-btn" class="flex-1 py-2 px-4 bg-gray-600 hover:bg-gray-500 text-white rounded text-sm font-medium">
                    Retake
                </button>
                <button type="submit" class="flex-1 py-2 px-4 bg-red-600 hover:bg-red-500 text-white rounded text-sm font-medium">
                    Absen Pulang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Message Modal -->
<div id="message-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm overflow-hidden">
        <div class="px-4 py-3 border-b flex justify-between items-center">
            <h3 class="font-medium text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Write Message
            </h3>
            <button id="close-modal-btn" class="text-gray-400 hover:text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4">
            <textarea id="modal-message-input" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2 bg-gray-50" placeholder="Write your message here..."></textarea>
        </div>
        <div class="px-4 py-3 bg-gray-50 flex justify-end">
            <button id="save-message-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                Submit Message
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ... (Same JS as create.blade.php) ...
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photoPreview = document.getElementById('photo-preview');
        const captureBtn = document.getElementById('capture-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const loading = document.getElementById('loading');
        const form = document.getElementById('attendance-form');
        const locationText = document.getElementById('location-text');
        
        // Modal Elements
        const messageBtn = document.getElementById('message-btn');
        const messageModal = document.getElementById('message-modal');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const saveMessageBtn = document.getElementById('save-message-btn');
        const modalInput = document.getElementById('modal-message-input');
        const hiddenNotes = document.getElementById('hidden-notes');

        // Modal Logic
        messageBtn.addEventListener('click', () => {
             messageModal.classList.remove('hidden');
             modalInput.focus();
        });

        function closeModal() {
            messageModal.classList.add('hidden');
        }

        closeModalBtn.addEventListener('click', closeModal);
        
        saveMessageBtn.addEventListener('click', () => {
            hiddenNotes.value = modalInput.value;
            if(hiddenNotes.value.trim() !== "") {
                messageBtn.classList.add('text-green-400');
            } else {
                messageBtn.classList.remove('text-green-400');
            }
            closeModal();
        });

        messageModal.addEventListener('click', (e) => {
            if (e.target === messageModal) closeModal();
        });
        
        // Camera & Location
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then(stream => { video.srcObject = stream; loading.classList.add('hidden'); })
            .catch(err => { console.error(err); loading.innerHTML = 'Camera Denied'; });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    document.getElementById('lat').value = pos.coords.latitude;
                    document.getElementById('lng').value = pos.coords.longitude;
                    locationText.innerText = `Lat: ${pos.coords.latitude.toFixed(4)}, Lng: ${pos.coords.longitude.toFixed(4)}`;
                },
                (err) => { locationText.innerText = "Location Denied"; locationText.classList.add('text-red-400'); }
            );
        }

        captureBtn.addEventListener('click', () => {
            canvas.width = video.videoWidth; canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const dataUrl = canvas.toDataURL('image/jpeg');
            photoPreview.src = dataUrl;
            photoPreview.classList.remove('hidden'); video.classList.add('hidden');
            document.getElementById('photo-data').value = dataUrl;
            document.getElementById('photo-data').name = "photo_base64"; 
            form.classList.remove('hidden'); captureBtn.classList.add('hidden');
        });

        retakeBtn.addEventListener('click', () => {
            photoPreview.classList.add('hidden'); video.classList.remove('hidden');
            form.classList.add('hidden'); captureBtn.classList.remove('hidden');
            document.getElementById('photo-data').value = '';
        });
    });
</script>
@endsection
