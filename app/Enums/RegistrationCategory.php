<?php

namespace App\Enums;

enum RegistrationCategory: string
{
    case PROVISIONAL = 'provisional';
    case EFFECTIVE = 'effective';
    case RENEWAL = 'renewal';
    case REINSTATEMENT = 'reinstatement';

    /**
     * Get the label for the category.
     */
    public function label(): string
    {
        return match ($this) {
            self::PROVISIONAL => 'Provisória',
            self::EFFECTIVE => 'Efetiva',
            self::RENEWAL => 'Renovação',
            self::REINSTATEMENT => 'Reinscrição',
        };
    }

    /**
     * Get the badge color for the category.
     */
    public function color(): string
    {
        return match ($this) {
            self::PROVISIONAL => 'warning',
            self::EFFECTIVE => 'success',
            self::RENEWAL => 'info',
            self::REINSTATEMENT => 'primary',
        };
    }

    /**
     * Check if this category is provisional.
     */
    public function isProvisional(): bool
    {
        return $this === self::PROVISIONAL;
    }

    /**
     * Check if this category is effective.
     */
    public function isEffective(): bool
    {
        return $this === self::EFFECTIVE;
    }

    /**
     * Check if this category is renewable.
     */
    public function isRenewable(): bool
    {
        return in_array($this, [
            self::PROVISIONAL,
            self::EFFECTIVE,
        ]);
    }
}
