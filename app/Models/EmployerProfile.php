<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerProfile extends Model
{
    use HasFactory;

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
        'license_number',
        'registration_number',
        'tax_number',
        'company_type',
        'founded_year',
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
