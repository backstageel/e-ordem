<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentReview extends Model
{
    protected $fillable = [
        'document_id',
        'reviewed_by',
        'review_status',
        'review_notes',
        'feedback',
        'validation_results',
        'reviewed_at',
        'review_order',
        'previous_review_id',
    ];

    protected $casts = [
        'validation_results' => 'array',
        'reviewed_at' => 'datetime',
        'review_order' => 'integer',
    ];

    /**
     * Get the document being reviewed.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the user who reviewed the document.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the previous review (if this is a re-review).
     */
    public function previousReview(): BelongsTo
    {
        return $this->belongsTo(DocumentReview::class, 'previous_review_id');
    }

    /**
     * Scope a query to only include approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('review_status', 'approved');
    }

    /**
     * Scope a query to only include rejected reviews.
     */
    public function scopeRejected($query)
    {
        return $query->where('review_status', 'rejected');
    }

    /**
     * Scope a query to only include pending reviews.
     */
    public function scopePending($query)
    {
        return $query->where('review_status', 'pending');
    }
}
