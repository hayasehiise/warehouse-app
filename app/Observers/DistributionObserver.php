<?php

namespace App\Observers;

use App\Models\Distribution;
use Exception;

class DistributionObserver
{
    /**
     * Method for decrease stock
     */
    public function decreaseStock(Distribution $distribution): void
    {
        foreach ($distribution->distributionItems as $item) {
            $stock = $item->item->itemStock;
            if ($stock->quantity < $item->qty) {
                throw new Exception('Item '.$item->item->name.' stock quantity is not enough!');
            }
            $stock->decrement('quantity', $item->qty);
        }
    }

    /**
     * Method for restock
     */
    public function restock(Distribution $distribution): void
    {
        foreach ($distribution->distributionItems as $item) {
            $stock = $item->item->itemStock;
            $stock->increment('quantity', $item->qty);
        }
    }

    /**
     * Handle the Distribution "creating" event.
     */
    public function creating(Distribution $distribution): void
    {
        $user = auth()->user();

        /**
         * Created By Automatic Fill
         */
        $distribution->created_by = $user->id;

        /**
         * Distribution Code Auto Generate
         */
        $lastDistribution = Distribution::withTrashed()->latest('id')->first();
        $newCode = ($lastDistribution?->id ?? 0) + 1;
        $distribution->distribution_code = 'DIST-'.now()->format('Ymd').'-'.str_pad($newCode, 5, '0', STR_PAD_LEFT);

        // pending approval
        $distribution->approve_status = 'pending';
    }

    /**
     * Handle the Distribution "created" event.
     */
    public function created(Distribution $distribution): void
    {
        // auto decrease stock if distribution already approved
        if ($distribution->approve_status === 'approved') {
            $this->decreaseStock($distribution);
        }
    }

    /**
     * Handle the Distribution "updating" event.
     */
    public function updating(Distribution $distribution): void
    {
        // approval changed
        if ($distribution->isDirty('approve_status')) {
            if (in_array($distribution->approve_status, ['approved', 'rejected'])) {
                $distribution->approved_by = auth()->id();
                $distribution->approved_at = now();
            }
        }
    }

    /**
     * Handle the Distribution "updated" event.
     */
    public function updated(Distribution $distribution): void
    {
        // pending -> approved : decrease stock
        if ($distribution->wasChanged('approve_status') && $distribution->approve_status === 'approved') {
            $this->decreaseStock($distribution);
        }
    }

    /**
     * Handle the Distribution "deleted" event.
     */
    public function deleted(Distribution $distribution): void
    {
        // prevent when force deleted
        if ($distribution->isForceDeleting()) {
            return;
        }

        // only approve can restock
        if ($distribution->approve_status === 'approved') {
            $this->restock($distribution);
        }
    }

    /**
     * Handle the Distribution "restored" event.
     */
    public function restored(Distribution $distribution): void
    {
        // only approve can decrease stock
        if ($distribution->approve_status === 'approved') {
            $this->decreaseStock($distribution);
        }
    }

    /**
     * Handle the Distribution "force deleted" event.
     */
    public function forceDeleted(Distribution $distribution): void
    {
        //
    }
}
