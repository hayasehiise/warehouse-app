<?php

namespace App\Models;

use App\Observers\InventoryTransactionObserver;
use App\Policies\InventoryTransactionPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Policy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Policy(InventoryTransactionPolicy::class)]
#[ObservedBy(InventoryTransactionObserver::class)]
#[Fillable([
    'public_id',
    'item_id',
    'transaction_date',
    'type',
    'status',
    'approve_status',
    'quantity',
    'created_by',
    'approved_by',
    'approved_note',
])]
#[Hidden([
    'id',
    'deleted_at',
])]
class InventoryTransaction extends Model
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

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
