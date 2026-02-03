<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIDAR HRIS</title>
    
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
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo/Header -->
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    SIDAR HRIS
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Sign in to your account
                </p>
            </div>

            <!-- Login Form -->
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Error Messages -->
                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="rounded-md shadow-sm -space-y-px">
                    <!-- Login (Email or NIK) -->
                    <div>
                        <label for="login" class="sr-only">Email or NIK</label>
                        <input 
                            id="login" 
                            name="login" 
                            type="text" 
                            required 
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm" 
                            placeholder="Email address or NIK (NIK is your username)"
                            value="{{ old('login') }}"
                        >
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm" 
                            placeholder="Password"
                        >
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        Sign in
                    </button>
                </div>
            </form>

            <!-- Demo Credentials -->
            <div class="mt-4 p-4 bg-blue-50 rounded-md shadow-inner">
                <p class="text-xs text-blue-800 font-semibold mb-2 flex items-center">
                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    Demo - Login with Email or NIK:
                </p>
                <div class="space-y-1">
                    <p class="text-xs text-blue-700 font-mono"><strong>Staff:</strong> staff@sidar.test <span class="text-gray-400">or</span> DEMO-007</p>
                    <p class="text-xs text-blue-700 font-mono"><strong>Admin:</strong> admin@sidar.test <span class="text-gray-400">or</span> DEMO-001</p>
                    <p class="text-xs text-blue-700 font-mono"><strong>Pass:</strong> password</p>
                </div>
                <p class="mt-2 text-[10px] text-blue-500 italic">*NIK can be used as Username</p>
            </div>
        </div>
    </div>
</body>
</html>
