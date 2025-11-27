<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requested_at' => 'datetime',
        'expected_delivery_date' => 'datetime',
        'delivered_at' => 'datetime',
        'status_history' => 'array',
    ];

    /**
     * Get the member that owns the card.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the user who requested the card.
     */
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the card type as a human-readable string.
     *
     * @return string
     */
    public function getCardTypeTextAttribute()
    {
        $types = [
            'professional_card' => 'Cartão Profissional',
            'digital_wallet' => 'Carteira Digital',
        ];

        return $types[$this->card_type] ?? $this->card_type;
    }

    /**
     * Get the status as a human-readable string.
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Pendente',
            'in_production' => 'Em Produção',
            'completed' => 'Concluída',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get the status badge HTML.
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'pending' => 'bg-warning',
            'in_production' => 'bg-info',
            'completed' => 'bg-success',
            'delivered' => 'bg-secondary',
            'cancelled' => 'bg-danger',
        ];

        $class = $classes[$this->status] ?? 'bg-primary';

        return '<span class="badge '.$class.'">'.$this->status_text.'</span>';
    }

    /**
     * Get the card type badge HTML.
     *
     * @return string
     */
    public function getCardTypeBadgeAttribute()
    {
        $classes = [
            'professional_card' => 'bg-primary',
            'digital_wallet' => 'bg-success',
        ];

        $class = $classes[$this->card_type] ?? 'bg-primary';

        return '<span class="badge '.$class.'">'.$this->card_type_text.'</span>';
    }

    /**
     * Get the issue reason as a human-readable string.
     *
     * @return string
     */
    public function getIssueReasonTextAttribute()
    {
        $reasons = [
            'first_issue' => 'Primeira Via',
            'second_issue' => 'Segunda Via (Perda/Roubo)',
            'renewal' => 'Renovação',
            'update' => 'Atualização de Dados',
            'damaged' => 'Cartão Danificado',
        ];

        return $reasons[$this->issue_reason] ?? $this->issue_reason;
    }

    /**
     * Get the urgency as a human-readable string.
     *
     * @return string
     */
    public function getUrgencyTextAttribute()
    {
        $urgencies = [
            'normal' => 'Normal (7 dias úteis)',
            'urgent' => 'Urgente (3 dias úteis)',
            'express' => 'Expressa (24 horas)',
        ];

        return $urgencies[$this->urgency] ?? $this->urgency;
    }

    /**
     * Get the delivery method as a human-readable string.
     *
     * @return string
     */
    public function getDeliveryMethodTextAttribute()
    {
        $methods = [
            'pickup' => 'Retirada no Local',
            'mail' => 'Envio pelos Correios',
            'courier' => 'Entrega por Motoboy',
        ];

        return $methods[$this->delivery_method] ?? $this->delivery_method;
    }
}
