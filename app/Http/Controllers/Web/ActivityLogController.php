<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('search') && $request->search) {
             $query->where(function($q) use ($request) {
                 $q->where('action', 'like', '%'.$request->search.'%')
                   ->orWhere('model_type', 'like', '%'.$request->search.'%')
                   ->orWhere('model_id', 'like', '%'.$request->search.'%');
             });
        }

        $logs = $query->latest()->paginate(20);
        $users = \App\Models\Employee::orderBy('name')->pluck('name', 'id');

        return view('admin.activity-logs.index', compact('logs', 'users'));
    }
}
