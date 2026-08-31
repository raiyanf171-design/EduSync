<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\School;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Create new subscription
     */
    public function createSubscription($school, $package, $duration)
    {
        $startDate = Carbon::now();
        $expireDate = $startDate->copy()->addYears($duration);
        $price = $duration === 1 ? $package->price_1year : $package->price_2year;

        return Subscription::create([
            'school_id' => $school->id,
            'package_id' => $package->id,
            'start_date' => $startDate,
            'expire_date' => $expireDate,
            'amount' => $price,
            'status' => 'pending',
        ]);
    }

    /**
     * Mark subscription as paid
     */
    public function markAsPaid($subscription, $transactionId)
    {
        $subscription->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'payment_date' => Carbon::now(),
        ]);

        return $subscription;
    }

    /**
     * Check if subscription is active
     */
    public function isActive($subscription)
    {
        return $subscription->status === 'completed' && 
               $subscription->expire_date->isFuture();
    }

    /**
     * Check if subscription is expiring soon
     */
    public function isExpiringSoon($subscription, $days = 30)
    {
        $expiryDate = $subscription->expire_date;
        return $expiryDate->diffInDays(Carbon::now()) <= $days && 
               $expiryDate->isFuture();
    }

    /**
     * Get all expiring subscriptions
     */
    public function getExpiringSubscriptions($days = 30)
    {
        return Subscription::where('status', 'completed')
            ->where('expire_date', '<=', Carbon::now()->addDays($days))
            ->where('expire_date', '>', Carbon::now())
            ->get();
    }

    /**
     * Renew subscription
     */
    public function renewSubscription($subscription, $duration)
    {
        $newExpireDate = $subscription->expire_date->copy()->addYears($duration);
        $package = $subscription->package;
        $price = $duration === 1 ? $package->price_1year : $package->price_2year;

        return Subscription::create([
            'school_id' => $subscription->school_id,
            'package_id' => $subscription->package_id,
            'start_date' => $subscription->expire_date,
            'expire_date' => $newExpireDate,
            'amount' => $price,
            'status' => 'pending',
        ]);
    }
}
