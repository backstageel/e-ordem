<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Registration\Models\Registration;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Member extends Model implements Auditable
{
    use AuditableTrait, HasFactory, SoftDeletes;

    // Status constants
    public const STATUS_ACTIVE = 'active';

    public const STATUS_SUSPENDED = 'suspended';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_IRREGULAR = 'irregular';  // Quotas em atraso

    public const STATUS_CANCELED = 'canceled';

    protected $fillable = [
        'person_id',
        'member_number',
        'registration_number',
        'registration_date',
        'expiry_date',
        'professional_category',
        'specialty',
        'sub_specialty',
        'workplace',
        'workplace_address',
        'workplace_phone',
        'workplace_email',
        'years_of_experience',
        'previous_license_number',
        'detailed_experience',
        'specialization_institution',
        'specialization_year',
        'specialization_country',
        'other_qualifications',
        'academic_degree',
        'university',
        'graduation_date',
        'status',
        'dues_paid',
        'dues_paid_until',
        'notes',
        'profile_photo_path',
    ];

    protected $casts = [
        'graduation_date' => 'date',
        'dues_paid' => 'boolean',
        'dues_paid_until' => 'date',
    ];

    /**
     * Get the person that owns the member.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the registrations for the member.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'person_id', 'person_id');
    }

    /**
     * Get the documents for the member.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the active registrations for the member.
     */
    public function activeRegistrations()
    {
        return $this->registrations()->where('status', \App\Enums\RegistrationStatus::APPROVED->value);
    }

    /**
     * Get the pending registrations for the member.
     */
    public function pendingRegistrations()
    {
        return $this->registrations()->whereIn('status', [
            \App\Enums\RegistrationStatus::SUBMITTED->value,
            \App\Enums\RegistrationStatus::UNDER_REVIEW->value,
            \App\Enums\RegistrationStatus::DOCUMENTS_PENDING->value,
            \App\Enums\RegistrationStatus::PAYMENT_PENDING->value,
            \App\Enums\RegistrationStatus::VALIDATED->value,
        ]);
    }

    /**
     * Get the full name of the member through the person relationship.
     */
    public function getFullNameAttribute()
    {
        return $this->person->full_name;
    }

    /**
     * Get the card associated with the member.
     */
    public function card(): HasOne
    {
        return $this->hasOne(MemberCard::class)->latest();
    }

    /**
     * Get all cards for the member.
     */
    public function cards(): HasMany
    {
        return $this->hasMany(MemberCard::class);
    }

    /**
     * Get the nome attribute (Portuguese accessor for full_name).
     */
    public function getNomeAttribute()
    {
        return $this->full_name;
    }

    /**
     * Get the user associated with the member through the person relationship.
     */
    public function user()
    {
        return $this->person->user();
    }

    /**
     * Get the exam applications for the member through the person's user relationship.
     */
    public function examApplications()
    {
        return $this->person->user->hasMany(ExamApplication::class, 'user_id');
    }

    /**
     * Get the payments for the member through the person relationship.
     */
    public function payments()
    {
        return $this->person->payments();
    }

    /**
     * Get the medical specialities of the member.
     */
    public function medicalSpecialities(): BelongsToMany
    {
        return $this->belongsToMany(MedicalSpeciality::class, 'medical_speciality_member')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    /**
     * Get the primary medical speciality of the member.
     */
    public function primaryMedicalSpeciality(): BelongsToMany
    {
        return $this->belongsToMany(MedicalSpeciality::class, 'medical_speciality_member')
            ->wherePivot('is_primary', true)
            ->withTimestamps();
    }

    /**
     * Get the quota history for the member.
     */
    public function quotaHistory(): HasMany
    {
        return $this->hasMany(MemberQuota::class);
    }

    /**
     * Get the status history for the member.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(MemberStatusHistory::class);
    }

    /**
     * Get pending quotas for the member.
     */
    public function pendingQuotas(): HasMany
    {
        return $this->quotaHistory()->where('status', MemberQuota::STATUS_PENDING);
    }

    /**
     * Get overdue quotas for the member.
     */
    public function overdueQuotas(): HasMany
    {
        return $this->quotaHistory()
            ->where(function ($q) {
                $q->where('status', MemberQuota::STATUS_OVERDUE)
                    ->orWhere(function ($query) {
                        $query->where('status', MemberQuota::STATUS_PENDING)
                            ->where('due_date', '<', now()->startOfDay());
                    });
            });
    }

    /**
     * Check if member has regular quotas.
     */
    public function isQuotaRegular(): bool
    {
        $overdueCount = $this->overdueQuotas()->count();

        return $overdueCount === 0 && ($this->dues_paid === true || $this->dues_paid_until?->isFuture());
    }

    /**
     * Check if member has pending documents.
     */
    public function hasPendingDocuments(): bool
    {
        return $this->documents()
            ->where('status', \App\Enums\DocumentStatus::PENDING)
            ->exists();
    }

    /**
     * Check if member can generate a card.
     */
    public function canGenerateCard(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->isQuotaRegular()
            && ! $this->hasPendingDocuments();
    }

    /**
     * Get the quota status for the member.
     */
    public function getQuotaStatus(): string
    {
        $overdueCount = $this->overdueQuotas()->count();

        if ($overdueCount > 0) {
            return 'irregular';
        }

        $pendingCount = $this->pendingQuotas()
            ->where('due_date', '<=', now()->addDays(config('members.quota_grace_period_days', 30)))
            ->count();

        return $pendingCount > 0 ? 'warning' : 'regular';
    }

    /**
     * Calculate total quota due amount.
     */
    public function totalQuotaDue(): float
    {
        return (float) $this->overdueQuotas()->sum('amount')
            + (float) $this->overdueQuotas()->sum('penalty_amount');
    }
}
