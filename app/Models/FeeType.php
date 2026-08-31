<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'fee_name',
        'amount',
        'is_recurring',
        'description',
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the school associated with this fee type
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
