<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'school_classes';

    protected $fillable = [
        'school_id',
        'class_name',
        'class_number',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the school associated with this class
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all sections for this class
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    /**
     * Get all students in this class
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Scope to filter active classes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
