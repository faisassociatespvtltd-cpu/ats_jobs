<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'membership_type', 'start_date', 'end_date',
        'status', 'referral_code', 'referred_by', 'referral_count'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'referral_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }
}
