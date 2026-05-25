<?php

namespace App\Models;

use App\Observers\DistributionObserver;
use App\Policies\DistributionPolicy;
use Exception;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Policy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Policy(DistributionPolicy::class)]
#[ObservedBy([DistributionObserver::class])]
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

    // Approve Distribution
    public function approve(string $note): void
    {
        if ($this->approve_status !== 'pending') {
            throw new Exception('Tidak bisa approval distribusi yang sudah diapprove atau direject');
        }
        $this->approve_status = 'approved';
        $this->approved_by = auth()->id();
        $this->approved_note = $note;
        $this->save();
    }

    // Reject Distribution
    public function reject(string $note): void
    {
        if ($this->approve_status !== 'pending') {
            throw new Exception('Tidak bisa menolak distribusi yang sudah diapprove atau direject');
        }
        $this->approve_status = 'rejected';
        $this->approved_by = auth()->id();
        $this->approved_note = $note;
        $this->save();
    }

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
        return $this->hasMany(DistributionItems::class);
    }
}
