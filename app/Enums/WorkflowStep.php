<?php

namespace App\Enums;

enum WorkflowStep: string
{
    case INITIAL_REVIEW = 'initial_review';
    case DOCUMENT_VALIDATION = 'document_validation';
    case PAYMENT_VERIFICATION = 'payment_verification';
    case TECHNICAL_REVIEW = 'technical_review';
    case EXAM_SCHEDULING = 'exam_scheduling';
    case EXAM_EVALUATION = 'exam_evaluation';
    case FINAL_APPROVAL = 'final_approval';
    case COMPLETED = 'completed';

    /**
     * Get the label for the step.
     */
    public function label(): string
    {
        return match ($this) {
            self::INITIAL_REVIEW => 'Revisão Inicial',
            self::DOCUMENT_VALIDATION => 'Validação de Documentos',
            self::PAYMENT_VERIFICATION => 'Verificação de Pagamento',
            self::TECHNICAL_REVIEW => 'Revisão Técnica',
            self::EXAM_SCHEDULING => 'Agendamento de Exame',
            self::EXAM_EVALUATION => 'Avaliação de Exame',
            self::FINAL_APPROVAL => 'Aprovação Final',
            self::COMPLETED => 'Concluído',
        };
    }

    /**
     * Get the badge color for the step.
     */
    public function color(): string
    {
        return match ($this) {
            self::INITIAL_REVIEW => 'primary',
            self::DOCUMENT_VALIDATION => 'warning',
            self::PAYMENT_VERIFICATION => 'info',
            self::TECHNICAL_REVIEW => 'secondary',
            self::EXAM_SCHEDULING => 'info',
            self::EXAM_EVALUATION => 'warning',
            self::FINAL_APPROVAL => 'success',
            self::COMPLETED => 'success',
        };
    }

    /**
     * Check if this step is final.
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::COMPLETED,
        ]);
    }

    /**
     * Get the next step in the workflow.
     */
    public function getNextStep(): ?WorkflowStep
    {
        return match ($this) {
            self::INITIAL_REVIEW => self::DOCUMENT_VALIDATION,
            self::DOCUMENT_VALIDATION => self::PAYMENT_VERIFICATION,
            self::PAYMENT_VERIFICATION => self::TECHNICAL_REVIEW,
            self::TECHNICAL_REVIEW => self::FINAL_APPROVAL,
            self::EXAM_SCHEDULING => self::EXAM_EVALUATION,
            self::EXAM_EVALUATION => self::FINAL_APPROVAL,
            self::FINAL_APPROVAL => self::COMPLETED,
            self::COMPLETED => null,
        };
    }

    /**
     * Get the previous step in the workflow.
     */
    public function getPreviousStep(): ?WorkflowStep
    {
        return match ($this) {
            self::INITIAL_REVIEW => null,
            self::DOCUMENT_VALIDATION => self::INITIAL_REVIEW,
            self::PAYMENT_VERIFICATION => self::DOCUMENT_VALIDATION,
            self::TECHNICAL_REVIEW => self::PAYMENT_VERIFICATION,
            self::EXAM_SCHEDULING => self::TECHNICAL_REVIEW,
            self::EXAM_EVALUATION => self::EXAM_SCHEDULING,
            self::FINAL_APPROVAL => self::TECHNICAL_REVIEW,
            self::COMPLETED => self::FINAL_APPROVAL,
        };
    }

    /**
     * Get the default workflow steps for provisional registrations.
     */
    public static function getProvisionalSteps(): array
    {
        return [
            self::INITIAL_REVIEW,
            self::DOCUMENT_VALIDATION,
            self::PAYMENT_VERIFICATION,
            self::TECHNICAL_REVIEW,
            self::FINAL_APPROVAL,
            self::COMPLETED,
        ];
    }

    /**
     * Get the default workflow steps for effective registrations.
     */
    public static function getEffectiveSteps(): array
    {
        return [
            self::INITIAL_REVIEW,
            self::DOCUMENT_VALIDATION,
            self::PAYMENT_VERIFICATION,
            self::EXAM_SCHEDULING,
            self::EXAM_EVALUATION,
            self::FINAL_APPROVAL,
            self::COMPLETED,
        ];
    }
}
