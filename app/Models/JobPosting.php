<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    use SoftDeletes, HasFactory;


    protected $fillable = [
        'title', 'description', 'company_name', 'location', 'required_skills', 'job_type',
        'salary_min', 'salary_max', 'salary_range', 'experience_level', 'education_required',
        'experience_required', 'posted_date',
        'closing_date', 'status', 'posted_by', 'requirements', 'benefits', 'other_details',
        'responsibilities', 'qualifications', 'hard_skills', 'soft_skills', 'parsed_at',
    ];

    protected $casts = [
        'posted_date' => 'date',
        'closing_date' => 'date',
        'requirements' => 'array',
        'benefits' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'hard_skills' => 'array',
        'soft_skills' => 'array',
        'parsed_at' => 'datetime',
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
