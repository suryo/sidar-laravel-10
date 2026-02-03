<?php

namespace App\Policies;

use App\Models\Claim;
use App\Models\User;

class ClaimPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Claim $claim): bool
    {
        return $user->id === $claim->employee_id || 
               $user->id === $claim->supervisor_id || 
               $user->id === $claim->hcs_id || 
               $user->id === $claim->finance_id ||
               (isset($user->role) && ($user->role->is_admin || $user->role->name === 'admin' || $user->role->name === 'hcs' || $user->role->name === 'finance'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Claim $claim): bool
    {
        return $user->id === $claim->employee_id && $claim->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Claim $claim): bool
    {
        return $user->id === $claim->employee_id && $claim->status === 'pending';
    }
}
