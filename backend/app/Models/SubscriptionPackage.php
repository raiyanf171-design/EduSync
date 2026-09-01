<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPackage extends Model
{
    protected $fillable = [
        'package_name',
        'description',
        'price_1_year',
        'price_2_year',
        'max_students',
        'max_teachers',
        'features',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function schools()
    {
        return $this->hasMany(School::class, 'subscription_package_id');
    }
}
