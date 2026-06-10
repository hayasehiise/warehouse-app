<?php

namespace App\Policies;

use App\Models\ItemCategory;
use App\Models\User;

class ItemCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('item-category.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ItemCategory $itemCategory): bool
    {
        return $user->can('item-category.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('item-category.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ItemCategory $itemCategory): bool
    {
        return $user->can('item-category.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ItemCategory $itemCategory): bool
    {
        return $user->can('item-category.delete');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('item-category.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ItemCategory $itemCategory): bool
    {
        return $user->can('item-category.restore');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('item-category.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ItemCategory $itemCategory): bool
    {
        return $user->can('item-category.force-delete');
    }

    /**
     * Determine whether the user can force delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('item-category.force-delete');
    }
}
