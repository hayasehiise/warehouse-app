<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('item.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Item $item): bool
    {
        return $user->can('item.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('item.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Item $item): bool
    {
        return $user->can('item.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Item $item): bool
    {
        return $user->can('item.delete');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('item.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Item $item): bool
    {
        return $user->can('item.restore');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('item.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Item $item): bool
    {
        return $user->can('item.force-delete');
    }

    /**
     * Determine whether the user can force delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('item.force-delete');
    }
}
