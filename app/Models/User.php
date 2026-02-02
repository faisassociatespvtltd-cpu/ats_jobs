<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'cv_path',
        'company_logo_path',
        'email_verification_token',
        'otp',
        'otp_expires_at',
        'is_blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the employee profile for this user.
     */
    public function employeeProfile()
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    /**
     * Get the employer profile for this user.
     */
    public function employerProfile()
    {
        return $this->hasOne(EmployerProfile::class);
    }

    /**
     * Check if user is an employee.
     */
    public function isEmployee()
    {
        return $this->user_type === 'employee';
    }

    /**
     * Check if user is an employer.
     */
    public function isEmployer()
    {
        return $this->user_type === 'employer';
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin()
    {
        return $this->user_type === 'superadmin';
    }

    /**
     * Get the membership record for this user.
     */
    public function membership()
    {
        return $this->hasOne(Membership::class);
    }

    /**
     * Get reviews given by this user.
     */
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * Get reviews received by this user.
     */
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewed_id');
    }

    /**
     * Get the average rating of the user.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviewsReceived()->avg('rating') ?: 0;
    }

    /**
     * Get the count of reviews received.
     */
    public function getReviewCountAttribute()
    {
        return $this->reviewsReceived()->count();
    }
}
