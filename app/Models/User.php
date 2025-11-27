<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use AuditableTrait, HasFactory, HasRoles, Notifiable;

    /**
     * Get the person associated with the user.
     */
    public function person()
    {
        return $this->hasOne(Person::class);
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Get the unread messages received by the user.
     */
    public function unreadMessages()
    {
        return $this->receivedMessages()->where('read', false);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_recovery_codes' => 'array',
        ];
    }

    /**
     * Check if MFA is enabled for the user.
     */
    public function isMfaEnabled(): bool
    {
        return $this->two_factor_enabled ?? false;
    }

    /**
     * Enable MFA for the user.
     */
    public function enableMfa(string $secretKey, array $recoveryCodes): void
    {
        $this->two_factor_secret = $secretKey;
        $this->two_factor_recovery_codes = $recoveryCodes;
        $this->two_factor_enabled = true;
        $this->save();
    }

    /**
     * Disable MFA for the user.
     */
    public function disableMfa(): void
    {
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->two_factor_enabled = false;
        $this->save();
    }

    /**
     * Validate a recovery code and mark it as used.
     */
    public function validateRecoveryCode(string $code): bool
    {
        if (! $this->two_factor_recovery_codes) {
            return false;
        }

        $recoveryCodes = $this->two_factor_recovery_codes;

        $position = array_search($code, $recoveryCodes);

        if ($position !== false) {
            unset($recoveryCodes[$position]);
            $this->two_factor_recovery_codes = array_values($recoveryCodes);
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
