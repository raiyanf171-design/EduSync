<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'month_year',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'payment_status',
        'disbursed_date',
    ];

    protected $casts = [
        'disbursed_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the staff associated with this payroll
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Scope to filter pending payrolls
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope to filter disbursed payrolls
     */
    public function scopeDisbursed($query)
    {
        return $query->where('payment_status', 'disbursed');
    }
}
