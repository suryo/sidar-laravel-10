<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DarAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'dar_id',
        'filename',
        'original_filename',
        'path',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    // ============================================
    // Relationships
    // ============================================

    public function dar(): BelongsTo
    {
        return $this->belongsTo(Dar::class);
    }

    // ============================================
    // Accessors
    // ============================================

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getSizeInKbAttribute(): float
    {
        return round($this->size / 1024, 2);
    }

    public function getSizeInMbAttribute(): float
    {
        return round($this->size / 1024 / 1024, 2);
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    // ============================================
    // Helper Methods
    // ============================================

    public function getExtension(): string
    {
        return pathinfo($this->original_filename, PATHINFO_EXTENSION);
    }
}
