<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'late_date',
        'late_duration',
        'arrival_time',
        'reason',
        'approved_by_supervisor',
        'acknowledged_by_hcs',
    ];

    protected $casts = [
        'late_date' => 'date',
        'approved_by_supervisor' => 'boolean',
        'acknowledged_by_hcs' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
