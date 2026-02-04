<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'route_name', 'url', 'icon_svg', 'is_header', 'order'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
