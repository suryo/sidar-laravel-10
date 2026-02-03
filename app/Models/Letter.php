<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_number',
        'template_id',
        'creator_id',
        'recipient_name',
        'subject',
        'content',
        'status',
        'approver_id',
        'approved_at',
        'rejection_note',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(LetterTemplate::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'creator_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approver_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }
}
