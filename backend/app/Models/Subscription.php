<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'school_id',
        'package_id',
        'duration_years',
        'start_date',
        'end_date',
        'amount_paid',
        'payment_status',
        'status',
        'bkash_transaction_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class);
    }
}
