<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'subdomain',
        'package_id',
        'subscription_expire_date',
        'bkash_phone',
        'status',
        'logo_path',
        'address',
        'phone',
        'email',
    ];

    protected $casts = [
        'subscription_expire_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all users (admins, teachers, students, parents) for this school
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all subscriptions for this school
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get all students for this school
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get all staff for this school
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    /**
     * Get all fees for this school
     */
    public function feeTypes(): HasMany
    {
        return $this->hasMany(FeeType::class);
    }

    /**
     * Get all sessions for this school
     */
    public function sessionYears(): HasMany
    {
        return $this->hasMany(SessionYear::class);
    }

    /**
     * Get all classes for this school
     */
    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * Get all invoices for this school
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Check if subscription is active
     */
    public function isSubscriptionActive(): bool
    {
        return $this->subscription_expire_date > now() && $this->status === 'active';
    }

    /**
     * Check if subscription is expiring soon (within 30 days)
     */
    public function isSubscriptionExpiringSoon(): bool
    {
        return $this->subscription_expire_date->diffInDays(now()) <= 30 
            && $this->subscription_expire_date > now();
    }

    /**
     * Get subscription status message
     */
    public function getSubscriptionStatus(): string
    {
        if ($this->status !== 'active') {
            return 'Inactive';
        }

        if ($this->subscription_expire_date < now()) {
            return 'Expired';
        }

        if ($this->isSubscriptionExpiringSoon()) {
            return 'Expiring Soon';
        }

        return 'Active';
    }
}
