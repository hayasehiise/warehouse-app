<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'public_id',
    'distribution_code',
    'distribution_date',
    'recipient_name',
    'note',
    'approve_status',
    'approved_by',
    'approved_note',
    'approved_at',
    'created_by',
])]
#[Hidden([
    'id',
    'deleted_at',
    'updated_at',
])]
class Distribution extends Model
{
    use HasUlids, SoftDeletes;

    public function uniqueIds()
    {
        return ['public_id'];
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function distributionItems(): HasMany
    {
        return $this->hasMany(DistributionItem::class);
    }
}
