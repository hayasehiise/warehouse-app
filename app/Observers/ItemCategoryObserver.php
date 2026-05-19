<?php

namespace App\Observers;

use App\Models\ItemCategory;
use Illuminate\Support\Str;

class ItemCategoryObserver
{
    /**
     * Handle the ItemCategory "created" event.
     */
    public function created(ItemCategory $itemCategory): void
    {
        //
    }

    /**
     * Handle the ItemCategory "creating" event.
     */
    public function creating(ItemCategory $itemCategory): void
    {
        $itemCategory->slug = Str::slug($itemCategory->name);
    }

    /**
     * Handle the ItemCategory "updated" event.
     */
    public function updated(ItemCategory $itemCategory): void
    {
        //
    }

    /**
     * Handle the ItemCategory "updating" event.
     */
    public function updating(ItemCategory $itemCategory): void
    {
        if ($itemCategory->isDirty('name')) {
            $itemCategory->slug = Str::slug($itemCategory->name);
        }
    }

    /**
     * Handle the ItemCategory "deleted" event.
     */
    public function deleted(ItemCategory $itemCategory): void
    {
        //
    }

    /**
     * Handle the ItemCategory "restored" event.
     */
    public function restored(ItemCategory $itemCategory): void
    {
        //
    }

    /**
     * Handle the ItemCategory "force deleted" event.
     */
    public function forceDeleted(ItemCategory $itemCategory): void
    {
        //
    }
}
