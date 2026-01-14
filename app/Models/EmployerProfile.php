<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_address',
        'contact_person_name',
        'phone_number',
        'whatsapp_number',
        'website_url',
        'company_description',
        'industry',
        'company_size',
        'linkedin_url',
    ];

    /**
     * Get the user that owns the employer profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
