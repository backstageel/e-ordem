<?php

namespace Modules\Registration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'registration_type',
        'current_step',
        'step_data',
        'expires_at',
    ];

    protected $casts = [
        'step_data' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the temporary registration has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Extend the expiration time.
     */
    public function extendExpiration(int $hours = 24): void
    {
        $this->update(['expires_at' => now()->addHours($hours)]);
    }

    /**
     * Get data for a specific step.
     */
    public function getStepData(int $step): ?array
    {
        return $this->step_data[$step] ?? null;
    }

    /**
     * Set data for a specific step.
     */
    public function setStepData(int $step, array $data): void
    {
        $stepData = $this->step_data ?? [];
        $stepData[$step] = $data;
        $this->update(['step_data' => $stepData]);
    }

    /**
     * Find or create a temporary registration by email and phone.
     */
    public static function findOrCreateByContact(string $email, string $phone, string $registrationType): self
    {
        $tempRegistration = self::where('email', $email)
            ->orWhere('phone', $phone)
            ->where('registration_type', $registrationType)
            ->where('expires_at', '>', now())
            ->first();

        if (! $tempRegistration) {
            $tempRegistration = self::create([
                'email' => $email,
                'phone' => $phone,
                'registration_type' => $registrationType,
                'current_step' => 1,
                'expires_at' => now()->addHours(24),
            ]);
        }

        return $tempRegistration;
    }

    /**
     * Scope to only include non-expired registrations.
     */
    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>', now());
    }
}

