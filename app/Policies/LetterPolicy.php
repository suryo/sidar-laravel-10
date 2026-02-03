<?php

namespace App\Policies;

use App\Models\Letter;
use App\Models\User;

class LetterPolicy
{
    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Letter $letter): bool
    {
        return $user->role->can_approve || $user->role->is_admin;
    }
}
