<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Applicant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'job_posting_id', 'first_name', 'last_name', 'email', 'phone',
        'cover_letter', 'status', 'resume_id', 'application_date',
        'notes', 'rating'
    ];

    protected $casts = [
        'application_date' => 'date',
        'rating' => 'decimal:2',
    ];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function resume(): HasOne
    {
        return $this->hasOne(Resume::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }
}
