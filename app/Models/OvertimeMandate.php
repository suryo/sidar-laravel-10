<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeMandate extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'date',
        'task_description',
    ];
    
    protected $casts = [
        'date' => 'date',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function employees()
    {
        return $this->hasMany(OvertimeEmployee::class);
    }
}
