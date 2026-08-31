<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'payment_method',
        'transaction_id',
        'amount',
        'status',
        'bkash_phone',
        'bkash_reference',
        'receipt_path',
        'paid_date',
        'gateway_response',
    ];

    protected $casts = [
        'paid_date' => 'datetime',
        'gateway_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the invoice associated with this payment
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get payment status label
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'initiated' => 'Payment Initiated',
            'pending' => 'Payment Pending',
            'completed' => 'Payment Completed',
            'failed' => 'Payment Failed',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    /**
     * Scope to filter completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to filter by payment method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('paid_date', [$startDate, $endDate]);
    }
}
