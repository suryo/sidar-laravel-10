<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Traits\LogsActivity;

class Employee extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens, LogsActivity;

    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'phone',
        'department_id',
        'division_id',
        'location_id',
        'unit_usaha',
        'position',
        'level',
        'role_id',
        'supervisor_id',
        'manager_id',
        'senior_manager_id',
        'director_id',
        'owner_id',
        'leave_quota',
        'leave_group',
        'max_hours',
        'can_attend_outside',
        'status',
        'join_date',
        'resign_date',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'join_date' => 'date',
        'resign_date' => 'date',
        'can_attend_outside' => 'boolean',
        'max_hours' => 'decimal:2',
        'leave_quota' => 'integer',
    ];

    // ============================================
    // Relationships - Organization
    // ============================================

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    // ============================================
    // Relationships - Approval Chain
    // ============================================

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

    // Subordinates
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    public function approvers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_approvers', 'employee_id', 'approver_id')
                    ->withPivot('order')
                    ->orderBy('employee_approvers.order');
    }

    public function approverFor(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_approvers', 'approver_id', 'employee_id')
                    ->withPivot('order');
    }

    // ============================================
    // Relationships - Activities
    // ============================================

    public function dars(): HasMany
    {
        return $this->hasMany(Dar::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function latePermissions(): HasMany
    {
        return $this->hasMany(LatePermission::class);
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeResigned($query)
    {
        return $query->where('status', 'resigned');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // ============================================
    // Accessors & Mutators
    // ============================================

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // ============================================
    // Helper Methods
    // ============================================

    public function canApprove(Employee $employee): bool
    {
        return $this->id === $employee->supervisor_id
            || $this->id === $employee->manager_id
            || $this->id === $employee->senior_manager_id
            || $this->id === $employee->director_id
            || $this->id === $employee->owner_id;
    }

    public function getApprovalChain(): array
    {
        return array_filter([
            'supervisor' => $this->supervisor_id,
            'manager' => $this->manager_id,
            'senior_manager' => $this->senior_manager_id,
            'director' => $this->director_id,
            'owner' => $this->owner_id,
        ]);
    }
}
