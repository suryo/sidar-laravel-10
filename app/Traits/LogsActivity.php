<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        // Created
        static::created(function (Model $model) {
            static::logActivity('created', $model);
        });

        // Updated
        static::updated(function (Model $model) {
            if ($model->wasChanged()) {
                static::logActivity('updated', $model);
            }
        });

        // Deleted
        static::deleted(function (Model $model) {
            static::logActivity('deleted', $model);
        });
    }

    public static function logActivity($action, Model $model)
    {
        $userId = auth()->id();
        
        $log = [
            'user_id' => $userId,
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'old_values' => null,
            'new_values' => null,
        ];

        if ($action === 'updated') {
            $old = [];
            $new = [];
            foreach ($model->getDirty() as $key => $value) {
                // Ignore timestamps and irrelevant fields
                if (in_array($key, ['updated_at', 'created_at'])) continue;
                
                $old[$key] = $model->getOriginal($key);
                $new[$key] = $value;
            }
            $log['old_values'] = count($old) ? $old : null;
            $log['new_values'] = count($new) ? $new : null;
            
            if (empty($log['new_values'])) return; 
        }
        
        if ($action === 'created') {
             $log['new_values'] = $model->getAttributes();
        }

        if ($action === 'deleted') {
            $log['old_values'] = $model->getAttributes();
        }

        ActivityLog::create($log);
    }

    public static function customLog($action, Model $model, array $oldValues = null, array $newValues = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
