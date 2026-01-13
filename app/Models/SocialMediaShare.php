<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SocialMediaShare extends Model
{
    protected $fillable = [
        'shareable_type', 'shareable_id', 'platform', 'shared_by',
        'share_url', 'message', 'shared_at'
    ];

    protected $casts = [
        'shared_at' => 'datetime',
    ];

    public function shareable(): MorphTo
    {
        return $this->morphTo();
    }

    public function sharedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by');
    }
}
