<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'company_name', 'location', 'required_skills', 'job_type',
        'salary_min', 'salary_max', 'experience_level', 'posted_date',
        'closing_date', 'status', 'posted_by', 'requirements', 'benefits', 'other_details'
    ];

    protected $casts = [
        'posted_date' => 'date',
        'closing_date' => 'date',
        'requirements' => 'array',
        'benefits' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }
}
