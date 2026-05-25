<?php

namespace App\Policies;

use App\Models\Distribution;
use App\Models\User;

class DistributionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('distribution.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Distribution $distribution): bool
    {
        return $user->can('distribution.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('distribution.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Distribution $distribution): bool
    {
        return $user->can('distribution.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Distribution $distribution): bool
    {
        return $user->can('distribution.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Distribution $distribution): bool
    {
        return $user->can('distribution.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Distribution $distribution): bool
    {
        return $user->can('distribution.force-delete');
    }

    /**
     * Determine whether the user can approval the model.
     */
    public function approval(User $user, Distribution $distribution): bool
    {
        return $user->can('distribution.approval');
    }
}
