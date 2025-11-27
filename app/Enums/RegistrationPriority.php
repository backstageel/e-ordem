<?php

namespace App\Enums;

enum RegistrationPriority: string
{
    case LOW = 'low';
    case NORMAL = 'normal';
    case HIGH = 'high';
    case URGENT = 'urgent';

    /**
     * Get the label for the priority.
     */
    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Baixa',
            self::NORMAL => 'Normal',
            self::HIGH => 'Alta',
            self::URGENT => 'Urgente',
        };
    }

    /**
     * Get the badge color for the priority.
     */
    public function color(): string
    {
        return match ($this) {
            self::LOW => 'secondary',
            self::NORMAL => 'primary',
            self::HIGH => 'warning',
            self::URGENT => 'danger',
        };
    }

    /**
     * Get the priority level (1-4).
     */
    public function getLevel(): int
    {
        return match ($this) {
            self::LOW => 1,
            self::NORMAL => 2,
            self::HIGH => 3,
            self::URGENT => 4,
        };
    }

    /**
     * Check if this priority is higher than another.
     */
    public function isHigherThan(RegistrationPriority $other): bool
    {
        return $this->getLevel() > $other->getLevel();
    }

    /**
     * Check if this priority is urgent.
     */
    public function isUrgent(): bool
    {
        return $this === self::URGENT;
    }
}
