<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_name',
        'student_capacity',
        'price_1year',
        'price_2year',
        'features',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to filter active packages
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
