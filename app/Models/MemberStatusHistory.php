<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'previous_status',
        'new_status',
        'changed_by',
        'reason',
        'notes',
        'effective_date',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    /**
     * Get the member associated with this status history.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the user who made the status change.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Scope a query to order by most recent first.
     */
    public function scopeRecent($query)
    {
        return $query->latest('created_at');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('new_status', $status);
    }
}
