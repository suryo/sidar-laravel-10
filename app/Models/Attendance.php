<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'attendance_number',
        'employee_id',
        'attendance_date',
        'check_in_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_address',
        'check_in_photo',
        'check_in_city',
        'check_in_at_distributor',
        'check_out_time',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_address',
        'check_out_photo',
        'check_out_city',
        'check_out_at_distributor',
        'status',
        'check_in_status',
        'work_type',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime:H:i:s',
        'check_out_time' => 'datetime:H:i:s',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'check_in_at_distributor' => 'boolean',
        'check_out_at_distributor' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // ============================================
    // Relationships
    // ============================================

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function scopeOnLeave($query)
    {
        return $query->where('status', 'leave');
    }

    public function scopeOntime($query)
    {
        return $query->where('check_in_status', 'ontime');
    }

    public function scopeLate($query)
    {
        return $query->where('check_in_status', 'late');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('attendance_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('attendance_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('attendance_date', now()->month)
                     ->whereYear('attendance_date', now()->year);
    }

    // ============================================
    // Accessors
    // ============================================

    public function getHasCheckedInAttribute(): bool
    {
        return !is_null($this->check_in_time);
    }

    public function getHasCheckedOutAttribute(): bool
    {
        return !is_null($this->check_out_time);
    }

    public function getIsCompleteAttribute(): bool
    {
        return $this->has_checked_in && $this->has_checked_out;
    }

    public function getCheckInCoordinatesAttribute(): ?array
    {
        if ($this->check_in_latitude && $this->check_in_longitude) {
            return [
                'lat' => (float) $this->check_in_latitude,
                'lng' => (float) $this->check_in_longitude,
            ];
        }
        return null;
    }

    public function getCheckOutCoordinatesAttribute(): ?array
    {
        if ($this->check_out_latitude && $this->check_out_longitude) {
            return [
                'lat' => (float) $this->check_out_latitude,
                'lng' => (float) $this->check_out_longitude,
            ];
        }
        return null;
    }

    // ============================================
    // Helper Methods
    // ============================================

    public function checkIn(array $data): bool
    {
        $this->check_in_time = now();
        $this->check_in_latitude = $data['latitude'] ?? null;
        $this->check_in_longitude = $data['longitude'] ?? null;
        $this->check_in_address = $data['address'] ?? null;
        $this->check_in_photo = $data['photo'] ?? null;
        $this->check_in_city = $data['city'] ?? null;
        $this->check_in_at_distributor = $data['at_distributor'] ?? false;
        
        // Determine check-in status (ontime/late)
        $this->check_in_status = $this->determineCheckInStatus();
        
        return $this->save();
    }

    public function checkOut(array $data): bool
    {
        $this->check_out_time = now();
        $this->check_out_latitude = $data['latitude'] ?? null;
        $this->check_out_longitude = $data['longitude'] ?? null;
        $this->check_out_address = $data['address'] ?? null;
        $this->check_out_photo = $data['photo'] ?? null;
        $this->check_out_city = $data['city'] ?? null;
        $this->check_out_at_distributor = $data['at_distributor'] ?? false;
        
        return $this->save();
    }

    protected function determineCheckInStatus(): string
    {
        // Simple logic: before 9 AM = ontime, after = late
        // Can be customized based on employee level
        $checkInHour = now()->hour;
        return $checkInHour < 9 ? 'ontime' : 'late';
    }
}
