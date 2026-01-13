<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScrapedJob extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'source', 'title', 'description', 'company_name', 'location',
        'salary', 'job_type', 'source_url', 'raw_data', 'status',
        'is_imported', 'imported_to_job_id', 'scraped_at'
    ];

    protected $casts = [
        'raw_data' => 'array',
        'scraped_at' => 'datetime',
        'is_imported' => 'boolean',
    ];

    public function importedToJob(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'imported_to_job_id');
    }
}
