<?php

namespace Modules\Registration\Models;

use App\Enums\RegistrationCategory;
use App\Enums\RegistrationPriority;
use App\Enums\RegistrationStatus;
use App\Models\AcademicQualification;
use App\Models\BaseModel;
use App\Models\Document;
use App\Models\ExamApplication;
use App\Models\ExamResult;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Person;
use App\Models\User;
use App\Models\WorkExperience;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Registration\Database\Factories\RegistrationFactory;

class Registration extends BaseModel
{
    use HasFactory, SoftDeletes;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return RegistrationFactory::new();
    }

    protected $casts = [
        'status' => RegistrationStatus::class,
        'priority_level' => RegistrationPriority::class,
        'submission_date' => 'date',
        'approval_date' => 'date',
        'expiry_date' => 'date',
        'payment_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_paid' => 'boolean',
        'payment_amount' => 'decimal:2',
        'documents_validated' => 'boolean',
        'is_renewal' => 'boolean',
        'documents_checked' => 'boolean',
        'additional_documents_required' => 'array',
        'type' => 'string', // certification, provisional, effective
        'category' => 'integer', // 1, 2, 3 para certification
        'subtype' => 'integer', // 1-12 para provisional
        'grade' => 'string', // A, B, C para effective
        'exam_grade' => 'decimal:1',
        'duration_days' => 'integer',
        'years_of_experience' => 'integer',
    ];

    // Auditing configuration inherited from BaseModel

    /**
     * Get the member that owns the registration.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the person that owns the registration.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the registration type that owns the registration.
     */
    public function registrationType(): BelongsTo
    {
        return $this->belongsTo(RegistrationType::class);
    }

    /**
     * Get the user that approved the registration.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the previous registration that this registration is renewing.
     */
    public function previousRegistration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'previous_registration_id');
    }

    /**
     * Get the renewals for this registration.
     */
    public function renewals(): HasMany
    {
        return $this->hasMany(Registration::class, 'previous_registration_id');
    }

    /**
     * Get the documents for the registration.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the workflow for the registration.
     */
    public function workflow(): HasOne
    {
        return $this->hasOne(RegistrationWorkflow::class);
    }

    /**
     * Get the payments for the registration.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payable_id')->where('payable_type', self::class);
    }

    /**
     * Get the exam application for certification/effective registrations.
     */
    public function examApplication(): BelongsTo
    {
        return $this->belongsTo(ExamApplication::class);
    }

    /**
     * Get the exam result for certification/effective registrations.
     */
    public function examResult(): BelongsTo
    {
        return $this->belongsTo(ExamResult::class);
    }

    /**
     * Get the supervisor (médico moçambicano) for provisional registrations.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'supervisor_id');
    }

    /**
     * Get the certification workflow for certification registrations.
     */
    public function certificationWorkflow(): HasOne
    {
        return $this->hasOne(\Modules\Registration\Models\CertificationWorkflow::class);
    }

    /**
     * Scope a query to only include pending registrations.
     */
    public function scopePending($query)
    {
        return $query->where('status', RegistrationStatus::SUBMITTED);
    }

    /**
     * Scope a query to only include approved registrations.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', RegistrationStatus::APPROVED);
    }

    /**
     * Scope a query to only include rejected registrations.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', RegistrationStatus::REJECTED);
    }

    /**
     * Scope a query to only include expired registrations.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', RegistrationStatus::APPROVED)
            ->where('expiry_date', '<', now());
    }

    /**
     * Scope a query to only include active registrations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', RegistrationStatus::APPROVED)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            });
    }

    /**
     * Scope a query to only include under review registrations.
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', RegistrationStatus::UNDER_REVIEW);
    }

    /**
     * Scope a query to only include documents pending registrations.
     */
    public function scopeDocumentsPending($query)
    {
        return $query->where('status', RegistrationStatus::DOCUMENTS_PENDING);
    }

    /**
     * Scope a query to only include payment pending registrations.
     */
    public function scopePaymentPending($query)
    {
        return $query->where('status', RegistrationStatus::PAYMENT_PENDING);
    }

    /**
     * Scope a query to only include archived registrations.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', RegistrationStatus::ARCHIVED);
    }

    /**
     * Scope a query to only include provisional registrations.
     */
    public function scopeProvisional($query)
    {
        return $query->where('type', 'provisional')
            ->orWhere(function ($query) {
                $query->whereHas('registrationType', function ($query) {
                    $query->where('category', RegistrationCategory::PROVISIONAL);
                });
            });
    }

    /**
     * Scope a query to only include effective registrations.
     */
    public function scopeEffective($query)
    {
        return $query->where('type', 'effective')
            ->orWhere(function ($query) {
                $query->whereHas('registrationType', function ($query) {
                    $query->where('category', RegistrationCategory::EFFECTIVE);
                });
            });
    }

    /**
     * Scope a query to only include certification registrations.
     */
    public function scopeCertification($query)
    {
        return $query->where('type', 'certification');
    }

    /**
     * Check if the registration is a certification type.
     */
    public function isCertification(): bool
    {
        return $this->type === 'certification';
    }

    /**
     * Check if the registration is a provisional type.
     */
    public function isProvisional(): bool
    {
        return $this->type === 'provisional';
    }

    /**
     * Check if the registration is an effective type.
     */
    public function isEffective(): bool
    {
        return $this->type === 'effective';
    }

    /**
     * Generate process number based on registration type.
     */
    public function generateProcessNumber(): string
    {
        $year = now()->year;
        $sequence = self::whereYear('created_at', $year)
            ->where('type', $this->type)
            ->count() + 1;

        return match ($this->type) {
            'certification' => sprintf('CERT-%d-%d-%04d', $this->category ?? 0, $year, $sequence),
            'provisional' => sprintf('PROV-%02d-%d-%04d', $this->subtype ?? 0, $year, $sequence),
            'effective' => sprintf('EFET-%s-%d-%04d', $this->grade ?? 'X', $year, $sequence),
            default => sprintf('REG-%d-%04d', $year, $sequence),
        };
    }

    /**
     * Get required documents based on type and subtype/category/grade.
     */
    public function getRequiredDocuments(): array
    {
        if (! $this->registrationType) {
            return [];
        }

        $documents = $this->registrationType->getRequiredDocuments();

        // Se for provisória, adiciona documentos comuns
        if ($this->isProvisional() && $this->registrationType->isProvisional()) {
            $commonDocuments = $this->registrationType->getCommonDocuments() ?? [];
            $specificDocuments = $this->registrationType->getSpecificDocuments() ?? [];
            $documents = array_merge($commonDocuments, $specificDocuments);
        }

        return $documents;
    }

    /**
     * Check if the registration is renewable.
     */
    public function isRenewable(): bool
    {
        return $this->status === RegistrationStatus::APPROVED &&
               $this->expiry_date &&
               $this->expiry_date->diffInDays(now()) <= 30 &&
               $this->registrationType->name !== 'Renewal';
    }

    /**
     * Check if the registration is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    /**
     * Check if the registration is active.
     */
    public function isActive(): bool
    {
        return $this->status === RegistrationStatus::APPROVED &&
               (! $this->expiry_date || $this->expiry_date >= now());
    }

    /**
     * Check if the registration is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === RegistrationStatus::APPROVED;
    }

    /**
     * Check if the registration is pending.
     */
    public function isPending(): bool
    {
        return $this->status === RegistrationStatus::SUBMITTED;
    }

    /**
     * Check if the registration is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === RegistrationStatus::REJECTED;
    }

    public function isValidated(): bool
    {
        return $this->status === RegistrationStatus::VALIDATED;
    }

    /**
     * Check if the registration is under review.
     */
    public function isUnderReview(): bool
    {
        return $this->status === RegistrationStatus::UNDER_REVIEW;
    }

    /**
     * Check if the registration has documents pending.
     */
    public function hasDocumentsPending(): bool
    {
        return $this->status === RegistrationStatus::DOCUMENTS_PENDING;
    }

    /**
     * Check if the registration has payment pending.
     */
    public function hasPaymentPending(): bool
    {
        return $this->status === RegistrationStatus::PAYMENT_PENDING;
    }

    /**
     * Check if the registration is archived.
     */
    public function isArchived(): bool
    {
        return $this->status === RegistrationStatus::ARCHIVED;
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeColor(): string
    {
        return $this->status->color();
    }

    /**
     * Get the status label.
     */
    public function getStatusLabel(): string
    {
        return $this->status->label();
    }

    /**
     * Get the current work experience for the registration.
     */
    public function currentWorkExperience(): BelongsTo
    {
        return $this->belongsTo(WorkExperience::class, 'current_work_experience_id');
    }

    /**
     * Get the current academic qualification for the registration.
     */
    public function currentAcademicQualification(): BelongsTo
    {
        return $this->belongsTo(AcademicQualification::class, 'current_academic_qualification_id');
    }
}
