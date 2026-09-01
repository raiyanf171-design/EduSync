<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_name',
        'subdomain',
        'address',
        'phone',
        'email',
        'logo_path',
        'subscription_package_id',
        'subscription_expire_date',
        'status',
        'max_students',
        'max_teachers',
    ];

    protected $casts = [
        'subscription_expire_date' => 'datetime',
    ];

    public function subscriptionPackage()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'subscription_package_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
