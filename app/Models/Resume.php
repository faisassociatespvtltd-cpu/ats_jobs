<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'applicant_id', 'file_path', 'file_name', 'file_type',
        'file_size', 'parsed_content', 'skills', 'experience',
        'education', 'parsing_status'
    ];

    protected $casts = [
        'parsed_content' => 'array',
        'skills' => 'array',
        'experience' => 'array',
        'education' => 'array',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }
}
