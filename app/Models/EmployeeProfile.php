<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone_number',
        'whatsapp_number',
        'date_of_birth',
        'gender',
        'bio',
        'education_level',
        'skills',
        'experience',
        'linkedin_url',
        'portfolio_url',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user that owns the employee profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
