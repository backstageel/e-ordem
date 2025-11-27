<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentIntegrationLog extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
    ];

    /**
     * Get the payment integration that owns the log.
     */
    public function paymentIntegration(): BelongsTo
    {
        return $this->belongsTo(PaymentIntegration::class);
    }

    /**
     * Get the payment associated with the log.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Scope a query to only include logs with a specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include logs for a specific transaction.
     */
    public function scopeTransaction($query, $transactionId)
    {
        return $query->where('transaction_id', $transactionId);
    }
}
