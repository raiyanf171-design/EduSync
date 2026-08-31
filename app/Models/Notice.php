<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'content',
        'notice_type',
        'class_id',
        'published_by',
        'published_date',
        'visibility',
        'status',
    ];

    protected $casts = [
        'published_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the school associated with this notice
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user who published this notice
     */
    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Scope to filter active notices
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
