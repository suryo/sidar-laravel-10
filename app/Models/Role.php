<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'can_write',
        'can_read_own',
        'can_read_division',
        'can_read_department',
        'can_read_all',
        'can_approve',
        'can_manage_users',
        'is_admin',
        'is_hcs_print',
    ];

    protected $casts = [
        'can_write' => 'boolean',
        'can_read_own' => 'boolean',
        'can_read_division' => 'boolean',
        'can_read_department' => 'boolean',
        'can_read_all' => 'boolean',
        'can_approve' => 'boolean',
        'can_manage_users' => 'boolean',
        'is_admin' => 'boolean',
        'is_hcs_print' => 'boolean',
    ];

    // ============================================
    // Relationships
    // ============================================

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class)
                    ->withPivot('order')
                    ->orderBy('menu_role.order') // Order by the pivot table's order column
                    ->orderBy('menus.order');    // Fallback to global order
    }

    // ============================================
    // Helper Methods
    // ============================================

    public function canRead($scope = 'own'): bool
    {
        return match($scope) {
            'own' => $this->can_read_own,
            'division' => $this->can_read_division,
            'department' => $this->can_read_department,
            'all' => $this->can_read_all,
            default => false,
        };
    }
}
