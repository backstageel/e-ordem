<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case PENDING = 'pending';
    case UNDER_REVIEW = 'under_review';
    case REQUIRES_CORRECTION = 'requires_correction';
    case VALIDATED = 'validated';
    case EXPIRED = 'expired';
    case REJECTED = 'rejected';

    /**
     * Get the label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::UNDER_REVIEW => 'Em Análise',
            self::REQUIRES_CORRECTION => 'Requer Correção',
            self::VALIDATED => 'Validado',
            self::EXPIRED => 'Expirado',
            self::REJECTED => 'Rejeitado',
        };
    }

    /**
     * Get the badge color for the status.
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::UNDER_REVIEW => 'info',
            self::REQUIRES_CORRECTION => 'warning',
            self::VALIDATED => 'success',
            self::EXPIRED => 'danger',
            self::REJECTED => 'danger',
        };
    }

    /**
     * Check if the status is pending.
     */
    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    /**
     * Check if the status is under review.
     */
    public function isUnderReview(): bool
    {
        return $this === self::UNDER_REVIEW;
    }

    /**
     * Check if the status requires correction.
     */
    public function requiresCorrection(): bool
    {
        return $this === self::REQUIRES_CORRECTION;
    }

    /**
     * Check if the status is validated.
     */
    public function isValidated(): bool
    {
        return $this === self::VALIDATED;
    }

    /**
     * Check if the status is expired.
     */
    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }

    /**
     * Check if the status is rejected.
     */
    public function isRejected(): bool
    {
        return $this === self::REJECTED;
    }

    /**
     * Check if the status is in progress (not final).
     */
    public function isInProgress(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::UNDER_REVIEW,
            self::REQUIRES_CORRECTION,
        ]);
    }

    /**
     * Check if the status is final (validated, expired, or rejected).
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::VALIDATED,
            self::EXPIRED,
            self::REJECTED,
        ]);
    }

    /**
     * Check if the status needs attention (requires correction or expired).
     */
    public function needsAttention(): bool
    {
        return in_array($this, [
            self::REQUIRES_CORRECTION,
            self::EXPIRED,
        ]);
    }

    /**
     * Get all statuses as array for select options.
     */
    public static function options(): array
    {
        return array_map(
            fn (self $status) => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            self::cases()
        );
    }

    /**
     * Get statuses that allow document resubmission.
     */
    public static function allowsResubmission(): array
    {
        return [
            self::REQUIRES_CORRECTION,
            self::REJECTED,
        ];
    }
}
