@extends('layouts.app')

@section('title', 'Manage Menu Permissions - SIDAR HRIS')

@section('content')
<div class="px-4 sm:px-0">
    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Menu Visibility Settings</h1>
            <p class="mt-1 text-sm text-gray-600">Configure which menus are visible for each role.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button type="submit" form="permissions-form" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Changes
            </button>
        </div>
    </div>

    <!-- Permissions Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <form id="permissions-form" action="{{ route('settings.menus.update') }}" method="POST">
            @csrf
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                                Menu Item
                            </th>
                            @foreach($roles as $role)
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex flex-col items-center space-y-2">
                                        <span>{{ $role->name }}</span>
                                        <div class="flex items-center space-x-2">
                                            <!-- Toggle Checkbox -->
                                            <button type="button" onclick="toggleRolePermissions({{ $role->id }})" class="text-gray-400 hover:text-primary-600 focus:outline-none" title="Check/Uncheck All">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                            <!-- Reset Order -->
                                            <button type="button" onclick="resetRoleOrders({{ $role->id }})" class="text-gray-400 hover:text-red-500 focus:outline-none" title="Set All to Free (0)">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($menus as $menu)
                            <tr class="{{ $menu->is_header ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 sticky left-0 {{ $menu->is_header ? 'bg-gray-100 font-bold' : 'bg-white' }}">
                                    @if($menu->is_header)
                                        {{ $menu->title }} (Header)
                                    @else
                                        <div class="flex items-center pl-4">
                                            @if($menu->icon_svg)
                                            <div class="mr-2 text-gray-400 h-5 w-5">
                                                {!! $menu->icon_svg !!}
                                            </div>
                                            @endif
                                            {{ $menu->title }}
                                        </div>
                                    @endif
                                </td>
                                @foreach($roles as $role)
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex flex-col items-center space-y-2">
                                            <input 
                                                type="checkbox" 
                                                name="permissions[{{ $role->id }}][{{ $menu->id }}]"
                                                data-role-id="{{ $role->id }}" 
                                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded"
                                                {{ $role->menus->contains($menu->id) ? 'checked' : '' }}
                                            >
                                            {{-- Get the pivot order if exists --}}
                                            @php
                                                // Check if role has this menu attached
                                                $attachedMenu = $role->menus->find($menu->id);
                                                $pivotOrder = $attachedMenu ? $attachedMenu->pivot->order : 0;
                                            @endphp
                                            <input 
                                                type="number" 
                                                name="orders[{{ $role->id }}][{{ $menu->id }}]" 
                                                data-role-id="{{ $role->id }}"
                                                value="{{ $pivotOrder > 0 ? $pivotOrder : '' }}"
                                                class="w-16 px-2 py-1 text-xs border border-gray-300 rounded text-center focus:ring-primary-500 focus:border-primary-500"
                                                placeholder="Free"
                                            >
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleRolePermissions(roleId) {
        // Select all checkboxes for this role
        const checkboxes = document.querySelectorAll(`input[type="checkbox"][data-role-id="${roleId}"]`);
        
        // Determine state based on the first checkbox (simple toggle logic)
        // If the first one is checked, we uncheck all. If unchecked, we check all.
        // Or better: Check if ANY is unchecked, then check all. If ALL are checked, uncheck all.
        let isAllChecked = true;
        for (let cb of checkboxes) {
            if (!cb.checked) {
                isAllChecked = false;
                break;
            }
        }

        const newState = !isAllChecked;
        checkboxes.forEach(cb => cb.checked = newState);
    }

    function resetRoleOrders(roleId) {
        if(!confirm('Reset custom order for this role to "Free"?')) return;
        const inputs = document.querySelectorAll(`input[type="number"][data-role-id="${roleId}"]`);
        inputs.forEach(input => input.value = '');
    }
</script>
@endsection
