<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'overtime_mandate_id',
        'employee_id',
        'start_time',
        'end_time',
        'duration_minutes',
    ];

    public function mandate()
    {
        return $this->belongsTo(OvertimeMandate::class, 'overtime_mandate_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
