<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberQuota extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'year',
        'month',
        'amount',
        'due_date',
        'payment_date',
        'status',
        'payment_id',
        'penalty_amount',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_OVERDUE = 'overdue';

    public const STATUS_WAIVED = 'waived';

    /**
     * Get the member that owns the quota.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the payment associated with this quota.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Check if the quota is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE
            || ($this->status === self::STATUS_PENDING && $this->due_date < now()->startOfDay());
    }

    /**
     * Check if the quota is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID && $this->payment_date !== null;
    }

    /**
     * Get the period string (e.g., "Janeiro 2024").
     */
    public function getPeriodAttribute(): string
    {
        $monthNames = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];

        $monthName = $monthNames[$this->month] ?? $this->month;
        return $monthName.' '.$this->year;
    }

    /**
     * Scope a query to only include pending quotas.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include overdue quotas.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
            ->orWhere(function ($q) {
                $q->where('status', self::STATUS_PENDING)
                    ->where('due_date', '<', now()->startOfDay());
            });
    }

    /**
     * Scope a query to only include paid quotas.
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }
}
