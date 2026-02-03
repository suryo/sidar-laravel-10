<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIDAR HRIS')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: "#eff6ff",
                            100: "#dbeafe",
                            200: "#bfdbfe",
                            300: "#93c5fd",
                            400: "#60a5fa",
                            500: "#3b82f6",
                            600: "#2563eb",
                            700: "#1d4ed8",
                            800: "#1e40af",
                            900: "#1e3a8a",
                        },
                    },
                },
            },
        }
    </script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation (Simplified) -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('title', 'SIDAR HRIS')
                    </h2>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Date/Time Display -->
                    <span class="text-sm text-gray-500 font-mono hidden sm:block">
                        {{ now()->format('l, d M Y') }}
                    </span>
                    
                    <!-- Notification Bell (Placeholder) -->
                    <button class="text-gray-400 hover:text-gray-500 relative">
                        <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
                @endif

                @yield('content')
                
                <!-- Footer -->
                <footer class="mt-8 pt-4 border-t border-gray-200 text-center text-sm text-gray-400">
                    &copy; {{ date('Y') }} SIDAR HRIS. Indraco WebDev.
                </footer>
            </main>
        </div>
    </div>
</body>
</html>
