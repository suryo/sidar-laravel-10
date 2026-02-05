@extends('layouts.app')

@section('title', 'System Activity Logs')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">System Activity Logs</h1>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <!-- Filters -->
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div class="sm:col-span-1">
                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                    <option value="">All Users</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="sm:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700">Search (Action/Model/ID)</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. created, App\Models\Employee, 123">
                </div>
            </div>

            <div class="sm:col-span-1 flex items-end">
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Filter Logs
                </button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details (Before -> After)</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 cursor-pointer transition-colors" onclick="openLogModal(this)" data-log="{{ json_encode($log) }}">
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                        {{ $log->created_at->format('d M Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $log->user->name ?? 'System/Unknown' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($log->action == 'created') bg-green-100 text-green-800 
                        @elseif($log->action == 'updated') bg-blue-100 text-blue-800 
                        @elseif($log->action == 'deleted') bg-red-100 text-red-800 
                        @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="text-xs">
                            <span class="font-bold">{{ class_basename($log->model_type) }}</span> #{{ $log->model_id }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">
                        @if($log->action == 'updated')
                            <div class="max-w-xs overflow-hidden">
                                @foreach((array)$log->new_values as $key => $val)
                                    @php 
                                        $oldVal = is_array($log->old_values) && isset($log->old_values[$key]) ? $log->old_values[$key] : '-';
                                        if (is_array($val)) $val = json_encode($val);
                                        if (is_array($oldVal)) $oldVal = json_encode($oldVal);
                                    @endphp
                                    <div class="mb-1">
                                        <span class="font-semibold">{{ $key }}:</span> 
                                        <span class="text-red-600 line-through">{{ Str::limit((string)$oldVal, 20) }}</span> 
                                        &rarr; 
                                        <span class="text-green-600">{{ Str::limit((string)$val, 20) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($log->action == 'created')
                            <span class="text-green-600">Created with {{ count((array)$log->new_values) }} attributes</span>
                        @elseif($log->action == 'deleted')
                            <span class="text-red-600">Deleted entry</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $logs->links() }}
    </div>
</div>

<!-- Log Detail Modal -->
<div id="log-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeLogModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Log Details
                        </h3>
                        <div class="mt-4 border-t border-gray-200 py-2">
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                 <div>
                                     <p class="text-sm font-semibold text-gray-500">Date/Time</p>
                                     <p id="modal-date" class="text-sm text-gray-900"></p>
                                 </div>
                                 <div>
                                     <p class="text-sm font-semibold text-gray-500">User</p>
                                     <p id="modal-user" class="text-sm text-gray-900"></p>
                                 </div>
                                 <div>
                                     <p class="text-sm font-semibold text-gray-500">Action</p>
                                     <p id="modal-action" class="text-sm font-semibold"></p>
                                 </div>
                                 <div class="col-span-2">
                                     <p class="text-sm font-semibold text-gray-500">Subject</p>
                                     <p id="modal-subject" class="text-sm text-gray-900 font-mono"></p>
                                 </div>
                                 <div class="col-span-2">
                                     <p class="text-sm font-semibold text-gray-500">Context (IP / Agent)</p>
                                     <p id="modal-context" class="text-xs text-gray-400"></p>
                                 </div>
                             </div>
                        </div>
                        
                        <div class="mt-4">
                            <h4 class="text-sm font-bold text-gray-700 mb-2">Changes</h4>
                            <div class="bg-gray-50 rounded p-4 font-mono text-xs overflow-x-auto relative">
                                <button onclick="copyLogContent()" class="absolute top-2 right-2 flex items-center px-2 py-1 bg-white border border-gray-300 rounded shadow-sm text-xs text-gray-600 hover:bg-gray-100 focus:outline-none">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    Copy
                                </button>
                                <pre id="modal-content" class="whitespace-pre-wrap text-gray-800"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeLogModal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openLogModal(element) {
        if (!element || !element.dataset.log) {
            console.error('No log data found');
            return;
        }
        
        // Parse the JSON data from the data attribute
        const log = JSON.parse(element.dataset.log);

        document.getElementById('modal-date').textContent = new Date(log.created_at).toLocaleString('id-ID'); // Format roughly
        document.getElementById('modal-user').textContent = log.user ? `${log.user.name} (${log.user.email})` : 'System/Unknown';
        
        const actionEl = document.getElementById('modal-action');
        actionEl.textContent = log.action.toUpperCase();
        actionEl.className = 'text-sm font-semibold ' + (
            log.action === 'created' ? 'text-green-600' : 
            log.action === 'updated' ? 'text-blue-600' : 
            log.action === 'deleted' ? 'text-red-600' : 'text-gray-600'
        );

        document.getElementById('modal-subject').textContent = `${log.model_type} #${log.model_id}`;
        document.getElementById('modal-context').textContent = `${log.ip_address} | ${log.user_agent}`;
        
        // Format Changes
        let content = '';
        if (log.action === 'updated') {
            const oldVals = log.old_values || {};
            const newVals = log.new_values || {};
            
            // Format as readable comparison
            let comparison = {};
            for (const key in newVals) {
                comparison[key] = {
                    before: oldVals[key] ?? null,
                    after: newVals[key]
                };
            }
            content = JSON.stringify(comparison, null, 2);
        } else if (log.action === 'created') {
            content = "CREATED ATTRIBUTES:\n" + JSON.stringify(log.new_values, null, 2);
        } else if (log.action === 'deleted') {
            content = "DELETED ATTRIBUTES:\n" + JSON.stringify(log.old_values, null, 2);
        } else {
            content = "No details available.";
        }

        document.getElementById('modal-content').textContent = content;
        
        document.getElementById('log-modal').classList.remove('hidden');
    }

    function closeLogModal() {
        document.getElementById('log-modal').classList.add('hidden');
    }

    function copyLogContent() {
        const text = document.getElementById('modal-content').textContent;
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            // Fallback
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Copied (fallback)!');
        });
    }
</script>
@endsection
