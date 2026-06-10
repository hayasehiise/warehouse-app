<?php

namespace App\Policies;

use App\Models\InventoryTransaction;
use App\Models\User;

class InventoryTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('inventory-transaction.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->can('inventory-transaction.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('inventory-transaction.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->can('inventory-transaction.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->can('inventory-transaction.delete');
    }

    /**
     * Determine whether the user can delete any the models
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('inventory-transaction.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->can('inventory-transaction.restore');
    }

    /**
     * Determine whether the user can restore any the models
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('inventory-transaction.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->can('inventory-transaction.force-delete');
    }

    /**
     * Determine whether the user can force delete any the models
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('inventory-transaction.force-delete');
    }

    /**
     * Determine whether the user can approve the model
     */
    public function approval(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->can('inventory-transaction.approval');
    }
}
