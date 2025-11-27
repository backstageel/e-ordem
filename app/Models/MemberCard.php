<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cards';

    protected $guarded = false;

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'pending',
        'card_type_id' => 1, // Default to Provisional Member Card
        'is_physical' => true,
        'is_digital' => true,
    ];

    // Map qr_code_path to qr_code
    public function setQrCodePathAttribute($value)
    {
        $this->attributes['qr_code'] = $value;
    }

    public function getQrCodePathAttribute()
    {
        return $this->attributes['qr_code'];
    }

    /**
     * Get the member that owns the card.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Check if the card is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' &&
               (! $this->expiry_date || $this->expiry_date >= now());
    }

    /**
     * Check if the card is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    /**
     * Check if the card is revoked.
     */
    public function isRevoked(): bool
    {
        return $this->status === 'revoked';
    }

    /**
     * Get the QR code URL.
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        return $this->qr_code ? asset('storage/'.$this->qr_code) : null;
    }
}
