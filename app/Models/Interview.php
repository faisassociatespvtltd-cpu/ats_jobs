<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'applicant_id', 'job_posting_id', 'scheduled_at', 'interview_type',
        'location', 'meeting_link', 'interviewer_id', 'status',
        'notes', 'feedback', 'rating'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'rating' => 'decimal:2',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
