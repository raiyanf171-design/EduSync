<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'package_id',
        'transaction_id',
        'start_date',
        'expire_date',
        'amount',
        'payment_method',
        'status',
        'bkash_reference',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expire_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the school associated with this subscription
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the package associated with this subscription
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'completed' && $this->expire_date > now();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->expire_date < now();
    }

    /**
     * Check if subscription is expiring soon (within 30 days)
     */
    public function isExpiringSoon(): bool
    {
        return $this->expire_date->diffInDays(now()) <= 30 && !$this->isExpired();
    }

    /**
     * Get days remaining in subscription
     */
    public function getDaysRemaining(): int
    {
        return max(0, $this->expire_date->diffInDays(now()));
    }

    /**
     * Get subscription status message
     */
    public function getStatus(): string
    {
        if ($this->status === 'pending') {
            return 'Pending Payment';
        } elseif ($this->isExpired()) {
            return 'Expired';
        } elseif ($this->isExpiringSoon()) {
            return 'Expiring Soon';
        } elseif ($this->isActive()) {
            return 'Active';
        }

        return 'Inactive';
    }

    /**
     * Scope to filter active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'completed')
                     ->where('expire_date', '>', now());
    }

    /**
     * Scope to filter expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('expire_date', '<', now());
    }

    /**
     * Scope to filter expiring soon
     */
    public function scopeExpiringSoon($query)
    {
        return $query->where('expire_date', '<=', now()->addDays(30))
                     ->where('expire_date', '>', now())
                     ->where('status', 'completed');
    }

    /**
     * Scope to filter by school
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}
