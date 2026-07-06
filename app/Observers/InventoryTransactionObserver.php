<?php

namespace App\Observers;

use App\Models\InventoryTransaction;

class InventoryTransactionObserver
{
    /**
     * Method for decreasing stock
     */
    private function decreaseStock(InventoryTransaction $inventoryTransaction): void
    {
        $stock = $inventoryTransaction->item->itemStock;
        $stock->decrement('quantity', $inventoryTransaction->quantity);
    }

    /**
     * Method for increasing stock
     */
    private function increaseStock(InventoryTransaction $inventoryTransaction): void
    {
        $stock = $inventoryTransaction->item->itemStock;
        $stock->increment('quantity', $inventoryTransaction->quantity);
    }

    /**
     * Handle the InventoryTransaction "creating" event.
     */
    public function creating(InventoryTransaction $inventoryTransaction): void
    {
        $user = auth()->user();

        $inventoryTransaction->created_by = $user->id;
        $inventoryTransaction->approve_status = 'PENDING';
    }

    /**
     * Handle the InventoryTransaction "created" event.
     */
    public function created(InventoryTransaction $inventoryTransaction): void
    {
        if ($inventoryTransaction->approve_status !== 'APPROVED') {
            return;
        }
        if ($inventoryTransaction->type === 'IN') {
            $this->increaseStock($inventoryTransaction);
        } elseif ($inventoryTransaction->type === 'OUT') {
            $this->decreaseStock($inventoryTransaction);
        }
    }

    /**
     * Handle the InventoryTransaction "updated" event.
     */
    public function updated(InventoryTransaction $inventoryTransaction): void
    {
        if ($inventoryTransaction->isDirty('approve_status') && $inventoryTransaction->approve_status === 'APPROVED') {
            if ($inventoryTransaction->type === 'IN') {
                $this->increaseStock($inventoryTransaction);
            } elseif ($inventoryTransaction->type === 'OUT') {
                $this->decreaseStock($inventoryTransaction);
            }
        }
    }

    /**
     * Handle the InventoryTransaction "deleted" event.
     */
    public function deleted(InventoryTransaction $inventoryTransaction): void
    {
        if ($inventoryTransaction->isForceDeleting()) {
            return;
        }
        if ($inventoryTransaction->approve_status !== 'APPROVED') {
            return;
        }
        if ($inventoryTransaction->type === 'IN') {
            $this->decreaseStock($inventoryTransaction);
        } elseif ($inventoryTransaction->type === 'OUT') {
            $this->increaseStock($inventoryTransaction);
        }
    }

    /**
     * Handle the InventoryTransaction "restored" event.
     */
    public function restored(InventoryTransaction $inventoryTransaction): void
    {
        if ($inventoryTransaction->approve_status !== 'APPROVED') {
            return;
        }
        if ($inventoryTransaction->type === 'IN') {
            $this->increaseStock($inventoryTransaction);
        } elseif ($inventoryTransaction->type === 'OUT') {
            $this->decreaseStock($inventoryTransaction);
        }
    }

    /**
     * Handle the InventoryTransaction "force deleted" event.
     */
    public function forceDeleted(InventoryTransaction $inventoryTransaction): void
    {
        //
    }
}
