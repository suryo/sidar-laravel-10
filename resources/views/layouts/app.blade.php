<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIDAR HRIS')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
            <!-- Impersonation Banner -->
            @if(session('impersonator_id'))
            <div class="bg-red-600 px-4 py-3 text-white flex justify-between items-center shadow-md z-50">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <span class="font-medium">You are impersonating <strong>{{ auth()->user()->name }}</strong>.</span>
                </div>
                <form action="{{ route('impersonate.leave') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-white text-red-600 px-3 py-1 rounded text-sm font-bold shadow hover:bg-red-50 focus:outline-none">
                        Leave Impersonation
                    </button>
                </form>
            </div>
            @endif

            <!-- Top Navigation (Simplified) -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
                <div class="flex items-center">
                    <!-- Hamburger Menu Button -->
                    <button id="sidebar-toggle" class="mr-4 text-gray-500 focus:outline-none md:hidden">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('title', 'SIDAR HRIS')
                    </h2>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="hidden sm:flex space-x-2 text-sm font-medium mr-2">
                        <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'text-primary-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">ID</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'text-primary-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">EN</a>
                    </div>
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
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 flex flex-col justify-between">
                <div class="p-6 pb-20 md:pb-6 flex-grow">
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
                </div>
                
                <!-- Footer -->
                <footer class="mt-auto py-4 border-t border-gray-200 text-center text-sm text-gray-400 w-full bg-gray-100">
                    &copy; {{ date('Y') }} SIDAR HRIS. Indraco WebDev.
                </footer>
            </main>
        </div>
    </div>

    <!-- Bottom Navigation for Mobile -->
    <div class="fixed bottom-0 w-full bg-white border-t border-gray-200 block md:hidden z-50">
        <div class="grid grid-cols-3 h-16">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center text-gray-500 hover:text-primary-600 {{ request()->routeIs('dashboard') ? 'text-primary-600' : '' }}">
                <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-xs font-medium">Dashboard</span>
            </a>
            
            <a href="{{ route('dars.index') }}" class="flex flex-col items-center justify-center text-gray-500 hover:text-primary-600 {{ request()->routeIs('dars.index') ? 'text-primary-600' : '' }}">
                <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-xs font-medium">DAR Report</span>
            </a>

            <a href="{{ route('dars.create') }}" class="flex flex-col items-center justify-center text-gray-500 hover:text-primary-600 {{ request()->routeIs('dars.create') ? 'text-primary-600' : '' }}">
                <svg class="h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <span class="text-xs font-medium">DAR</span>
            </a>
        </div>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 hidden transition-opacity duration-300 md:hidden" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        // Connect button
        document.getElementById('sidebar-toggle').addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
