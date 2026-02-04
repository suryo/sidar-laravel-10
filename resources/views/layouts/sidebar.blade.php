<div class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 min-h-screen flex flex-col transition-transform duration-300 transform -translate-x-full md:relative md:translate-x-0" id="sidebar">
    <!-- Sidebar Header / User Profile -->
    <div class="p-6 text-center border-b border-gray-100 bg-gray-50">
        <div class="inline-block relative mb-3">
            <div class="h-16 w-16 rounded-full bg-primary-600 flex items-center justify-center text-xl font-bold text-white shadow-md mx-auto">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <div class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow-sm">
                <!-- Status Indicator or Icon -->
                <div class="h-3 w-3 bg-green-500 rounded-full"></div>
            </div>
        </div>
        <h5 class="font-bold text-gray-900 truncate">{{ auth()->user()->name }}</h5>
        <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">
            {{ auth()->user()->position ?? auth()->user()->role->name }}
        </p>
        <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ auth()->user()->nik }}</p>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <!-- Main -->
        <div class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Main Menu
        </div>
        
        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Dashboard
        </a>

        <!-- Activity (DAR) -->
        <a href="{{ route('dars.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('dars.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dars.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Daily Activity (DAR)
        </a>

        <!-- HRIS Section -->
        <div class="mt-6 px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Presensi & Cuti
        </div>

        <a href="{{ route('attendance.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('attendance.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('attendance.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Presensi (Check-in)
        </a>

        <a href="{{ route('leaves.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('leaves.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('leaves.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Cuti & Izin
        </a>

        <a href="{{ route('claims.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('claims.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('claims.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z" />
            </svg>
            Klaim & Reimbursement
        </a>

        <!-- Manager Section -->
        @if(auth()->user()->role->can_approve)
        <div class="mt-6 px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Management
        </div>
        
        <a href="{{ route('dars.approvals') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('dars.approvals') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dars.approvals') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Approval DAR
        </a>

        <a href="{{ route('leaves.approvals') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('leaves.approvals') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('leaves.approvals') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Approval Cuti
        </a>

        <a href="{{ route('claims.approvals') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('claims.approvals') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('claims.approvals') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Approval Klaim
        </a>
        
        <div class="mt-6 px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Reports & Analytics
        </div>

        <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('reports.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Reports
        </a>
        @endif

        <div class="mt-6 px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Correspondence
        </div>

        <a href="{{ route('letters.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('letters.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('letters.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Official Letters
        </a>
        
        @if(auth()->user()->role->can_approve || auth()->user()->role->is_admin)
        <a href="{{ route('letter-templates.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('letter-templates.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('letter-templates.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Manage Templates
        </a>

        <!-- Calendar Management -->
        <a href="{{ route('holidays.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('holidays.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('holidays.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Calendar & Holidays
        </a>
        @endif

        @if(auth()->user()->role->is_admin || auth()->user()->role->name === 'HCS')
        <div class="mt-6 px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Administration
        </div>

        <a href="{{ route('employees.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('employees.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('employees.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Employees
        </a>
        @endif
        
        <div class="p-4 mt-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-red-600 rounded-md hover:bg-red-50 group">
                <svg class="mr-3 h-5 w-5 text-red-500 group-hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</div>
