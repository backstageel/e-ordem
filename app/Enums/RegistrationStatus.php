<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case UNDER_REVIEW = 'under_review';
    case DOCUMENTS_PENDING = 'documents_pending';
    case PAYMENT_PENDING = 'payment_pending';
    case VALIDATED = 'validated';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case ARCHIVED = 'archived';
    case EXPIRED = 'expired';

    /**
     * Get the label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Rascunho',
            self::SUBMITTED => 'Submetido',
            self::UNDER_REVIEW => 'Em Análise',
            self::DOCUMENTS_PENDING => 'Documentos Pendentes',
            self::PAYMENT_PENDING => 'Pagamento Pendente',
            self::VALIDATED => 'Validado',
            self::APPROVED => 'Aprovado',
            self::REJECTED => 'Rejeitado',
            self::ARCHIVED => 'Arquivado',
            self::EXPIRED => 'Expirado',
        };
    }

    /**
     * Get the badge color for the status.
     */
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'secondary',
            self::SUBMITTED => 'warning',
            self::UNDER_REVIEW => 'info',
            self::DOCUMENTS_PENDING => 'warning',
            self::PAYMENT_PENDING => 'warning',
            self::VALIDATED => 'primary',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::ARCHIVED => 'dark',
            self::EXPIRED => 'danger',
        };
    }

    /**
     * Get the icon for the status badge.
     */
    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'fa-file-alt',
            self::SUBMITTED => 'fa-paper-plane',
            self::UNDER_REVIEW => 'fa-search',
            self::DOCUMENTS_PENDING => 'fa-file-exclamation',
            self::PAYMENT_PENDING => 'fa-credit-card',
            self::VALIDATED => 'fa-check-circle',
            self::APPROVED => 'fa-check-circle',
            self::REJECTED => 'fa-times-circle',
            self::ARCHIVED => 'fa-archive',
            self::EXPIRED => 'fa-clock',
        };
    }

    /**
     * Get the description for the status.
     */
    public function description(): string
    {
        return match ($this) {
            self::DRAFT => 'Inscrição em rascunho, ainda não submetida',
            self::SUBMITTED => 'Inscrição submetida e aguardando análise',
            self::UNDER_REVIEW => 'Inscrição em análise pelo secretariado',
            self::DOCUMENTS_PENDING => 'Aguardando documentos adicionais',
            self::PAYMENT_PENDING => 'Aguardando confirmação de pagamento',
            self::VALIDATED => 'Inscrição validada, pronta para aprovação',
            self::APPROVED => 'Inscrição aprovada e ativa',
            self::REJECTED => 'Inscrição rejeitada',
            self::ARCHIVED => 'Inscrição arquivada (inativa há mais de 45 dias)',
            self::EXPIRED => 'Inscrição expirada',
        };
    }

    /**
     * Check if the status is pending.
     */
    public function isPending(): bool
    {
        return in_array($this, [
            self::SUBMITTED,
            self::UNDER_REVIEW,
            self::DOCUMENTS_PENDING,
            self::PAYMENT_PENDING,
            self::VALIDATED,
        ]);
    }

    /**
     * Check if the status is active.
     */
    public function isActive(): bool
    {
        return $this === self::APPROVED;
    }

    /**
     * Check if the status is final.
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::APPROVED,
            self::REJECTED,
            self::ARCHIVED,
            self::EXPIRED,
        ]);
    }

    /**
     * Get all pending statuses.
     */
    public static function getPendingStatuses(): array
    {
        return [
            self::SUBMITTED,
            self::UNDER_REVIEW,
            self::DOCUMENTS_PENDING,
            self::PAYMENT_PENDING,
            self::VALIDATED,
        ];
    }

    /**
     * Get all final statuses.
     */
    public static function getFinalStatuses(): array
    {
        return [
            self::APPROVED,
            self::REJECTED,
            self::ARCHIVED,
            self::EXPIRED,
        ];
    }
}
