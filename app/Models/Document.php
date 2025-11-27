<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Registration\Models\Registration;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Document extends Model implements Auditable
{
    use AuditableTrait, HasFactory, SoftDeletes;

    protected $fillable = [
        'person_id',
        'member_id',
        'registration_id',
        'document_type_id',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'file_hash',
        'status',
        'submission_date',
        'validation_date',
        'expiry_date',
        'rejection_reason',
        'notes',
        'validated_by',
        'has_translation',
        'translation_file_path',
    ];

    protected $casts = [
        'status' => \App\Enums\DocumentStatus::class,
        'submission_date' => 'date',
        'validation_date' => 'date',
        'expiry_date' => 'date',
        'file_size' => 'integer',
        'has_translation' => 'boolean',
    ];

    /**
     * Get the person that owns the document.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the member that owns the document.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the registration that owns the document.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the document type that owns the document.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Get the user that validated the document.
     */
    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Get the reviews for the document.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(DocumentReview::class);
    }

    /**
     * Get the latest review for the document.
     */
    public function latestReview(): HasOne
    {
        return $this->hasOne(DocumentReview::class)->latestOfMany();
    }

    /**
     * Scope a query to only include pending documents.
     */
    public function scopePending($query)
    {
        return $query->where('status', \App\Enums\DocumentStatus::PENDING);
    }

    /**
     * Scope a query to only include validated documents.
     */
    public function scopeValidated($query)
    {
        return $query->where('status', \App\Enums\DocumentStatus::VALIDATED);
    }

    /**
     * Scope a query to only include rejected documents.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', \App\Enums\DocumentStatus::REJECTED);
    }

    /**
     * Scope a query to only include expired documents (by status or date).
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', \App\Enums\DocumentStatus::EXPIRED)
                ->orWhere(function ($subQ) {
                    $subQ->where('status', \App\Enums\DocumentStatus::VALIDATED)
                        ->whereNotNull('expiry_date')
                        ->where('expiry_date', '<', now());
                });
        });
    }

    /**
     * Scope a query to only include documents that require correction.
     */
    public function scopeRequiresCorrection($query)
    {
        return $query->where('status', \App\Enums\DocumentStatus::REQUIRES_CORRECTION);
    }

    /**
     * Scope a query to only include documents under review.
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', \App\Enums\DocumentStatus::UNDER_REVIEW);
    }

    /**
     * Scope a query to only include documents that require translation.
     */
    public function scopeRequiresTranslation($query)
    {
        return $query->whereHas('documentType', function ($query) {
            $query->where('requires_translation', true);
        });
    }

    /**
     * Scope a query to only include documents that have translation.
     */
    public function scopeHasTranslation($query)
    {
        return $query->where('has_translation', true);
    }

    /**
     * Scope a query to only include documents that need translation.
     */
    public function scopeNeedsTranslation($query)
    {
        return $query->whereHas('documentType', function ($query) {
            $query->where('requires_translation', true);
        })->where('has_translation', false);
    }

    /**
     * Check if the document is pending.
     */
    public function isPending(): bool
    {
        return $this->status === \App\Enums\DocumentStatus::PENDING;
    }

    /**
     * Check if the document is validated.
     */
    public function isValidated(): bool
    {
        return $this->status === \App\Enums\DocumentStatus::VALIDATED;
    }

    /**
     * Check if the document is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === \App\Enums\DocumentStatus::REJECTED;
    }

    /**
     * Check if the document is expired (by date or status).
     */
    public function isExpired(): bool
    {
        return $this->status === \App\Enums\DocumentStatus::EXPIRED
            || ($this->expiry_date && $this->expiry_date < now());
    }

    /**
     * Get the file URL.
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/'.$this->file_path);
    }

    /**
     * Get the translation file URL.
     */
    public function getTranslationFileUrlAttribute(): ?string
    {
        return $this->translation_file_path ? asset('storage/'.$this->translation_file_path) : null;
    }
}
