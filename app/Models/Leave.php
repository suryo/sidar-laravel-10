<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'leave_number',
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'late_arrival_time',
        'delegate_to',
        'delegation_tasks',
        'delegate_status',
        'delegate_approved_at',
        'supervisor_id',
        'hcs_id',
        'supervisor_status',
        'hcs_status',
        'supervisor_notes',
        'hcs_notes',
        'supervisor_approved_at',
        'hcs_approved_at',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'late_arrival_time' => 'datetime:H:i:s',
        'total_days' => 'integer',
        'delegate_approved_at' => 'datetime',
        'supervisor_approved_at' => 'datetime',
        'hcs_approved_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    // ============================================
    // Relationships
    // ============================================

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function delegateTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'delegate_to');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function hcs(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hcs_id');
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeAnnual($query)
    {
        return $query->where('type', 'annual');
    }

    public function scopeSick($query)
    {
        return $query->where('type', 'sick');
    }

    public function scopePermission($query)
    {
        return $query->where('type', 'permission');
    }

    public function scopeLate($query)
    {
        return $query->where('type', 'late');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate)
                     ->where('end_date', '>=', $endDate);
              });
        });
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('start_date', now()->month)
                     ->whereYear('start_date', now()->year);
    }

    // ============================================
    // Accessors
    // ============================================

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getIsAnnualLeaveAttribute(): bool
    {
        return $this->type === 'annual';
    }

    public function getIsLatePermissionAttribute(): bool
    {
        return $this->type === 'late';
    }

    public function getRequiresDelegationAttribute(): bool
    {
        return $this->is_annual_leave && $this->total_days > 0;
    }

    // ============================================
    // Helper Methods
    // ============================================

    public function approveDelegation(): bool
    {
        if (!$this->delegate_to) {
            return false;
        }

        $this->delegate_status = 'approved';
        $this->delegate_approved_at = now();
        return $this->save();
    }

    public function rejectDelegation(): bool
    {
        if (!$this->delegate_to) {
            return false;
        }

        $this->delegate_status = 'rejected';
        $this->status = 'rejected';
        return $this->save();
    }

    public function approveBySupervisor(string $notes = null): bool
    {
        $this->supervisor_status = 'approved';
        $this->supervisor_approved_at = now();
        $this->supervisor_notes = $notes;

        // Check if fully approved
        if ($this->isFullyApproved()) {
            $this->status = 'approved';
        }

        return $this->save();
    }

    public function approveByHcs(string $notes = null): bool
    {
        $this->hcs_status = 'approved';
        $this->hcs_approved_at = now();
        $this->hcs_notes = $notes;

        // Check if fully approved
        if ($this->isFullyApproved()) {
            $this->status = 'approved';
        }

        return $this->save();
    }

    public function reject(Employee $rejector, string $notes): bool
    {
        if ($this->supervisor_id === $rejector->id) {
            $this->supervisor_status = 'rejected';
            $this->supervisor_notes = $notes;
        } elseif ($this->hcs_id === $rejector->id) {
            $this->hcs_status = 'rejected';
            $this->hcs_notes = $notes;
        } else {
            return false;
        }

        $this->status = 'rejected';
        return $this->save();
    }

    protected function isFullyApproved(): bool
    {
        $approvals = [];

        // Check delegation if required
        if ($this->requires_delegation) {
            $approvals[] = $this->delegate_status === 'approved';
        }

        // Check supervisor approval
        if ($this->supervisor_id) {
            $approvals[] = $this->supervisor_status === 'approved';
        }

        // Check HCS approval
        if ($this->hcs_id) {
            $approvals[] = $this->hcs_status === 'approved';
        }

        return count($approvals) > 0 && !in_array(false, $approvals);
    }

    public function getApprovalProgress(): array
    {
        return [
            'delegation' => $this->delegate_status,
            'supervisor' => $this->supervisor_status,
            'hcs' => $this->hcs_status,
        ];
    }

    public function calculateTotalDays(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
