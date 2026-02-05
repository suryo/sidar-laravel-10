<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\LogsActivity;

class Dar extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'dar_number',
        'employee_id',
        'dar_date',
        'activity',
        'result',
        'plan',
        'tag',
        'status',
        'submission_status',
        'is_read',
        'supervisor_id',
        'manager_id',
        'senior_manager_id',
        'director_id',
        'owner_id',
        'supervisor_status',
        'manager_status',
        'senior_manager_status',
        'director_status',
        'owner_status',
        'supervisor_approved_at',
        'manager_approved_at',
        'senior_manager_approved_at',
        'director_approved_at',
        'owner_approved_at',
        'submitted_at',
    ];

    protected $casts = [
        'dar_date' => 'date',
        'is_read' => 'boolean',
        'supervisor_approved_at' => 'datetime',
        'manager_approved_at' => 'datetime',
        'senior_manager_approved_at' => 'datetime',
        'director_approved_at' => 'datetime',
        'owner_approved_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    // ============================================
    // Relationships
    // ============================================

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(DarAttachment::class);
    }

    // Approval Chain
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function seniorManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'senior_manager_id');
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'director_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'owner_id');
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

    public function scopeOntime($query)
    {
        return $query->where('submission_status', 'ontime');
    }

    public function scopeLate($query)
    {
        return $query->where('submission_status', 'late');
    }

    public function scopeOver($query)
    {
        return $query->where('submission_status', 'over');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('dar_date', [$startDate, $endDate]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('dar_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('dar_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('dar_date', now()->month)
                     ->whereYear('dar_date', now()->year);
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

    public function getIsDraftAttribute(): bool
    {
        return $this->status === 'draft';
    }

    // ============================================
    // Helper Methods
    // ============================================

    public function approve(Employee $approver): bool
    {
        if ($this->supervisor_id === $approver->id) {
            $this->supervisor_status = 'approved';
            $this->supervisor_approved_at = now();
        } elseif ($this->manager_id === $approver->id) {
            $this->manager_status = 'approved';
            $this->manager_approved_at = now();
        } elseif ($this->senior_manager_id === $approver->id) {
            $this->senior_manager_status = 'approved';
            $this->senior_manager_approved_at = now();
        } elseif ($this->director_id === $approver->id) {
            $this->director_status = 'approved';
            $this->director_approved_at = now();
        } elseif ($this->owner_id === $approver->id) {
            $this->owner_status = 'approved';
            $this->owner_approved_at = now();
        } else {
            return false;
        }

        // Check if all required approvals are done
        if ($this->isFullyApproved()) {
            $this->status = 'approved';
        }

        return $this->save();
    }

    public function reject(Employee $approver, string $reason = null): bool
    {
        if ($this->supervisor_id === $approver->id) {
            $this->supervisor_status = 'rejected';
        } elseif ($this->manager_id === $approver->id) {
            $this->manager_status = 'rejected';
        } elseif ($this->senior_manager_id === $approver->id) {
            $this->senior_manager_status = 'rejected';
        } elseif ($this->director_id === $approver->id) {
            $this->director_status = 'rejected';
        } elseif ($this->owner_id === $approver->id) {
            $this->owner_status = 'rejected';
        } else {
            return false;
        }

        $this->status = 'rejected';
        return $this->save();
    }

    protected function isFullyApproved(): bool
    {
        $approvals = [];

        if ($this->supervisor_id) $approvals[] = $this->supervisor_status === 'approved';
        if ($this->manager_id) $approvals[] = $this->manager_status === 'approved';
        if ($this->senior_manager_id) $approvals[] = $this->senior_manager_status === 'approved';
        if ($this->director_id) $approvals[] = $this->director_status === 'approved';
        if ($this->owner_id) $approvals[] = $this->owner_status === 'approved';

        return count($approvals) > 0 && !in_array(false, $approvals);
    }

    public function getApprovalProgress(): array
    {
        return [
            'supervisor' => $this->supervisor_status,
            'manager' => $this->manager_status,
            'senior_manager' => $this->senior_manager_status,
            'director' => $this->director_status,
            'owner' => $this->owner_status,
        ];
    }
}
