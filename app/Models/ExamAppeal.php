<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamAppeal extends Model
{
    use SoftDeletes;

    protected $guarded = false;

    protected $casts = [
        'submitted_at' => 'datetime',
        'deadline_date' => 'date',
        'processed_at' => 'datetime',
        'is_final' => 'boolean',
        'is_appealable' => 'boolean',
    ];

    /**
     * Get the exam that this appeal belongs to.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the application that this appeal belongs to.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(ExamApplication::class, 'application_id');
    }

    /**
     * Get the result that this appeal belongs to.
     */
    public function result(): BelongsTo
    {
        return $this->belongsTo(ExamResult::class, 'result_id');
    }

    /**
     * Get the user who processed this appeal.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the user who proposed the jury.
     */
    public function juryProposedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jury_proposed_by');
    }

    /**
     * Get the user who approved the jury.
     */
    public function juryApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jury_approved_by');
    }

    /**
     * Get the user who created this appeal.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if the appeal is a correction appeal.
     */
    public function isCorrection(): bool
    {
        return $this->appeal_type === 'correction';
    }

    /**
     * Check if the appeal is approved.
     */
    public function isApproved(): bool
    {
        return $this->decision === 'approved';
    }

    /**
     * Check if the appeal is rejected.
     */
    public function isRejected(): bool
    {
        return $this->decision === 'rejected';
    }

    /**
     * Check if the appeal is pending.
     */
    public function isPending(): bool
    {
        return $this->decision === 'pending';
    }

    /**
     * Check if the appeal is final and unappealable.
     */
    public function isFinal(): bool
    {
        return $this->is_final;
    }

    /**
     * Check if the deadline has passed.
     */
    public function isDeadlinePassed(): bool
    {
        return $this->deadline_date < now()->toDateString();
    }
}
