<?php

namespace App\Models;

use App\Observers\ItemCategoryObserver;
use App\Policies\ItemCategoryPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(ItemCategoryPolicy::class)]
#[Fillable([
    'public_id',
    'name',
    'slug',
    'description',
])]
#[Hidden([
    'id',
    'deleted_at',
])]
#[ObservedBy([ItemCategoryObserver::class])]
class ItemCategory extends Model
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

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
