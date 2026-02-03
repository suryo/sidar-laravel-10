<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_number',
        'employee_id',
        'type',
        'amount',
        'description',
        'proof_file',
        'status',
        'supervisor_id',
        'supervisor_approved_at',
        'supervisor_notes',
        'hcs_id',
        'hcs_approved_at',
        'hcs_notes',
        'finance_id',
        'finance_processed_at',
        'finance_notes',
        'rejection_note',
        'rejected_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'supervisor_approved_at' => 'datetime',
        'hcs_approved_at' => 'datetime',
        'finance_processed_at' => 'datetime',
    ];

    /**
     * Get the employee who owns the claim.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the supervisor who approved.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    /**
     * Get the HCS who approved.
     */
    public function hcs(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hcs_id');
    }
    
    /**
     * Get the formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
    
    /**
     * Format Type Label
     */
    public function getTypeLabelAttribute(): string
    {
        return ucfirst($this->type);
    }

    /**
     * Get color class for status
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved_supervisor' => 'blue',
            'approved_hcs' => 'indigo',
            'paid' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }
}
