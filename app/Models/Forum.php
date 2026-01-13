<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forum extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'content', 'parent_id', 'category',
        'views', 'replies_count', 'is_pinned', 'is_locked',
        'last_reply_at'
    ];

    protected $casts = [
        'last_reply_at' => 'datetime',
        'views' => 'integer',
        'replies_count' => 'integer',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Forum::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Forum::class, 'parent_id');
    }
}
