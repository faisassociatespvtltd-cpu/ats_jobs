<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabourLaw extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'title', 'content', 'country', 'category',
        'author', 'source', 'published_date', 'isbn',
        'question', 'answer', 'created_by', 'views', 'is_featured'
    ];

    protected $casts = [
        'published_date' => 'date',
        'views' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
