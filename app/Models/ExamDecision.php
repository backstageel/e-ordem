<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamDecision extends Model
{
    use SoftDeletes;

    protected $guarded = false;

    protected $casts = [
        'decision_date' => 'date',
        'published' => 'boolean',
        'published_at' => 'datetime',
        'sent_to_colleges' => 'boolean',
        'sent_to_directors' => 'boolean',
        'sent_to_dnfps' => 'boolean',
    ];

    /**
     * Get the exam that this decision belongs to.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the application that this decision belongs to.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(ExamApplication::class, 'application_id');
    }

    /**
     * Get the result that this decision belongs to.
     */
    public function result(): BelongsTo
    {
        return $this->belongsTo(ExamResult::class, 'result_id');
    }

    /**
     * Get the user who signed as president.
     */
    public function signedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by_president');
    }

    /**
     * Get the user who homologated as Bastonário.
     */
    public function homologatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'homologated_by_bastonario');
    }

    /**
     * Check if the decision is approved.
     */
    public function isApproved(): bool
    {
        return $this->decision_type === 'approved';
    }

    /**
     * Check if the decision is rejected.
     */
    public function isRejected(): bool
    {
        return $this->decision_type === 'rejected';
    }

    /**
     * Check if the decision is pending.
     */
    public function isPending(): bool
    {
        return $this->decision_type === 'pending';
    }

    /**
     * Check if the decision is published.
     */
    public function isPublished(): bool
    {
        return $this->published && $this->published_at !== null;
    }
}
