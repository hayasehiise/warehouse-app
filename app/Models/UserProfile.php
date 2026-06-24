<?php

namespace App\Models;

use App\Policies\UserProfilePolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Policy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Policy(UserProfilePolicy::class)]
#[Fillable([
    'public_id',
    'user_id',
    'fullName',
    'employee_code',
    'employee_rank',
    'employee_position',
    'employee_group',
])]
#[Hidden([
    'id',
])]
class UserProfile extends Model
{
    use HasUlids;

    public function uniqueIds(): array
    {
        return ['public_id'];
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
