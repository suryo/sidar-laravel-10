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
        @foreach(auth()->user()->role->menus as $menu)
            @if($menu->is_header)
                <div class="mt-6 px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider {{ $loop->first ? 'mt-0' : '' }}">
                    {{ $menu->title }}
                </div>
            @else
                <a href="{{ $menu->route_name ? route($menu->route_name) : ($menu->url ?? '#') }}" 
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md group 
                   {{ ($menu->route_name && request()->routeIs($menu->route_name) || request()->routeIs($menu->route_name . '.*')) ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="{{ ($menu->route_name && request()->routeIs($menu->route_name) || request()->routeIs($menu->route_name . '.*')) ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}">
                        {!! $menu->icon_svg !!}
                    </div>
                    {{ $menu->title }}
                </a>
            @endif
        @endforeach
        
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
