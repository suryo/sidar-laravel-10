<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LetterTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'content'];

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class, 'template_id');
    }
}
