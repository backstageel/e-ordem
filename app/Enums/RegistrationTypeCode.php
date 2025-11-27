<?php

namespace App\Enums;

enum RegistrationTypeCode: string
{
    // Provisional registration codes
    case PROVISIONAL_FORMATION = 'provisional_formation';
    case PROVISIONAL_SHORT_TERM = 'provisional_short_term';
    case PROVISIONAL_TRAINEE = 'provisional_trainee';
    case PROVISIONAL_RESEARCH = 'provisional_research';
    case PROVISIONAL_HUMANITARIAN = 'provisional_humanitarian';
    case PROVISIONAL_COOPERATION = 'provisional_cooperation';
    case PROVISIONAL_PRIVATE = 'provisional_private';
    case PROVISIONAL_PUBLIC_GENERAL = 'provisional_public_general';
    case PROVISIONAL_PUBLIC_SPECIALIST = 'provisional_public_specialist';
    case PROVISIONAL_EXCHANGE = 'provisional_exchange';

    // Effective registration codes
    case EFFECTIVE_GENERAL = 'effective_general';
    case EFFECTIVE_SPECIALIST = 'effective_specialist';

    /**
     * Get the label for the code.
     */
    public function label(): string
    {
        return match ($this) {
            self::PROVISIONAL_FORMATION => 'Formação Médica Especializada (Formador)',
            self::PROVISIONAL_SHORT_TERM => 'Formação Médica de Curta Duração',
            self::PROVISIONAL_TRAINEE => 'Formação Médica Especializada (Formando)',
            self::PROVISIONAL_RESEARCH => 'Investigação Científica',
            self::PROVISIONAL_HUMANITARIAN => 'Missões Assistenciais Humanitárias',
            self::PROVISIONAL_COOPERATION => 'Cooperação Intergovernamental',
            self::PROVISIONAL_PRIVATE => 'Assistência Setor Privado',
            self::PROVISIONAL_PUBLIC_GENERAL => 'Exercício Setor Público (Clínico Geral)',
            self::PROVISIONAL_PUBLIC_SPECIALIST => 'Exercício Setor Público (Especialista)',
            self::PROVISIONAL_EXCHANGE => 'Intercâmbios com Médicos Nacionais',
            self::EFFECTIVE_GENERAL => 'Clínica Geral Nacional',
            self::EFFECTIVE_SPECIALIST => 'Especialista Nacional',
        };
    }

    /**
     * Get the category for this code.
     */
    public function getCategory(): RegistrationCategory
    {
        return match ($this) {
            self::PROVISIONAL_FORMATION,
            self::PROVISIONAL_SHORT_TERM,
            self::PROVISIONAL_TRAINEE,
            self::PROVISIONAL_RESEARCH,
            self::PROVISIONAL_HUMANITARIAN,
            self::PROVISIONAL_COOPERATION,
            self::PROVISIONAL_PRIVATE,
            self::PROVISIONAL_PUBLIC_GENERAL,
            self::PROVISIONAL_PUBLIC_SPECIALIST,
            self::PROVISIONAL_EXCHANGE => RegistrationCategory::PROVISIONAL,
            self::EFFECTIVE_GENERAL,
            self::EFFECTIVE_SPECIALIST => RegistrationCategory::EFFECTIVE,
        };
    }

    /**
     * Check if this code is provisional.
     */
    public function isProvisional(): bool
    {
        return $this->getCategory() === RegistrationCategory::PROVISIONAL;
    }

    /**
     * Check if this code is effective.
     */
    public function isEffective(): bool
    {
        return $this->getCategory() === RegistrationCategory::EFFECTIVE;
    }

    /**
     * Get all provisional codes.
     */
    public static function getProvisionalCodes(): array
    {
        return [
            self::PROVISIONAL_FORMATION,
            self::PROVISIONAL_SHORT_TERM,
            self::PROVISIONAL_TRAINEE,
            self::PROVISIONAL_RESEARCH,
            self::PROVISIONAL_HUMANITARIAN,
            self::PROVISIONAL_COOPERATION,
            self::PROVISIONAL_PRIVATE,
            self::PROVISIONAL_PUBLIC_GENERAL,
            self::PROVISIONAL_PUBLIC_SPECIALIST,
            self::PROVISIONAL_EXCHANGE,
        ];
    }

    /**
     * Get all effective codes.
     */
    public static function getEffectiveCodes(): array
    {
        return [
            self::EFFECTIVE_GENERAL,
            self::EFFECTIVE_SPECIALIST,
        ];
    }
}
