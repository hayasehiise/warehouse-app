<?php

namespace App\Models;

use App\Observers\ItemObserver;
use App\Policies\ItemPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Policy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Policy(ItemPolicy::class)]
#[Fillable([
    'public_id',
    'name',
    'sku',
    'description',
    'item_category_id',
])]
#[Hidden([
    'id',
    'deleted_at',
])]
#[ObservedBy([ItemObserver::class])]
class Item extends Model
{
    use HasUlids, SoftDeletes;

    public function uniqueIds(): array
    {
        return ['public_id'];
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function itemStock(): HasOne
    {
        return $this->hasOne(ItemStock::class);
    }

    public function itemCategory(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function distributionItems(): HasMany
    {
        return $this->hasMany(DistributionItems::class);
    }
}
