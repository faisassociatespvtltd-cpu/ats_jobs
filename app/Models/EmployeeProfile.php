<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone_number',
        'whatsapp_number',
        'cnic',
        'expected_salary',
        'location',
        'city',
        'state',
        'country',
        'date_of_birth',
        'gender',
        'bio',
        'education_level',
        'skills',
        'experience',
        'linkedin_url',
        'portfolio_url',
        'cnic',
        'expected_salary',
        'location',
        'city',
        'state',
        'country',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'expected_salary' => 'decimal:2',
    ];

    /**
     * Get the user that owns the employee profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
