<?php

namespace App\Enums;

enum RegistrationSubtype: int
{
    case SUBTYPE_1 = 1; // Formador em Residência Médica Especializada
    case SUBTYPE_2 = 2; // Formando em Residência Médica Especializada
    case SUBTYPE_3 = 3; // Formador de Curta Duração (Geral)
    case SUBTYPE_4 = 4; // Formador de Curta Duração (Reconhecido Mérito)
    case SUBTYPE_5 = 5; // Formando de Curta Duração
    case SUBTYPE_6 = 6; // Investigação Científica
    case SUBTYPE_7 = 7; // Missão Assistencial Filantrópica
    case SUBTYPE_8 = 8; // Cooperação Intergovernamental
    case SUBTYPE_9 = 9; // Exercício no Setor Privado
    case SUBTYPE_10 = 10; // Médico Estrangeiro Formado em Moçambique (Setor Público)
    case SUBTYPE_11 = 11; // Especialista Estrangeiro Formado em Moçambique (Setor Público)
    case SUBTYPE_12 = 12; // Intercâmbio com Médicos Nacionais

    /**
     * Get the label for the subtype.
     */
    public function label(): string
    {
        return match ($this) {
            self::SUBTYPE_1 => 'Formador em Residência Médica Especializada',
            self::SUBTYPE_2 => 'Formando em Residência Médica Especializada',
            self::SUBTYPE_3 => 'Formador de Curta Duração (Geral)',
            self::SUBTYPE_4 => 'Formador de Curta Duração (Reconhecido Mérito)',
            self::SUBTYPE_5 => 'Formando de Curta Duração',
            self::SUBTYPE_6 => 'Investigação Científica',
            self::SUBTYPE_7 => 'Missão Assistencial Filantrópica',
            self::SUBTYPE_8 => 'Cooperação Intergovernamental',
            self::SUBTYPE_9 => 'Exercício no Setor Privado',
            self::SUBTYPE_10 => 'Médico Estrangeiro Formado em Moçambique (Setor Público)',
            self::SUBTYPE_11 => 'Especialista Estrangeiro Formado em Moçambique (Setor Público)',
            self::SUBTYPE_12 => 'Intercâmbio com Médicos Nacionais',
        };
    }

    /**
     * Get the duration in days for the subtype.
     */
    public function durationDays(): ?int
    {
        return match ($this) {
            self::SUBTYPE_1 => 730, // 24 meses
            self::SUBTYPE_2 => 730, // 24 meses
            self::SUBTYPE_3 => 90,  // 3 meses
            self::SUBTYPE_4 => 90,  // 3 meses
            self::SUBTYPE_5 => 90,  // 3 meses
            self::SUBTYPE_6 => 365, // 12 meses
            self::SUBTYPE_7 => 90,  // 3 meses
            self::SUBTYPE_8 => 730,  // 24 meses
            self::SUBTYPE_9 => 365,  // 12 meses
            self::SUBTYPE_10 => 300, // 10 meses
            self::SUBTYPE_11 => 300, // 10 meses
            self::SUBTYPE_12 => 90,  // 3 meses
        };
    }

    /**
     * Check if the subtype is renewable.
     */
    public function isRenewable(): bool
    {
        return match ($this) {
            self::SUBTYPE_1 => true,  // Renovável por mais 24 meses
            self::SUBTYPE_2 => true,  // Renovável por mais 24 meses
            self::SUBTYPE_3 => true,  // Renovável por uma vez consecutiva
            self::SUBTYPE_4 => true,  // Renovável por uma vez consecutiva
            self::SUBTYPE_5 => true,  // Renovável por uma vez consecutiva
            self::SUBTYPE_6 => true,  // Renovável por uma vez consecutiva
            self::SUBTYPE_7 => true,  // Renovável por uma vez consecutiva
            self::SUBTYPE_8 => true,  // Renovável por mais 12 meses
            self::SUBTYPE_9 => false, // Não renovável
            self::SUBTYPE_10 => false, // Não renovável
            self::SUBTYPE_11 => false, // Não renovável
            self::SUBTYPE_12 => true,  // Renovável por uma vez consecutiva
        };
    }

    /**
     * Get the maximum number of renewals.
     */
    public function maxRenewals(): int
    {
        return match ($this) {
            self::SUBTYPE_1 => 1,  // Uma renovação
            self::SUBTYPE_2 => 1,  // Uma renovação
            self::SUBTYPE_3 => 1,  // Uma renovação
            self::SUBTYPE_4 => 1,  // Uma renovação
            self::SUBTYPE_5 => 1,  // Uma renovação
            self::SUBTYPE_6 => 1,  // Uma renovação
            self::SUBTYPE_7 => 1,  // Uma renovação
            self::SUBTYPE_8 => 1,  // Uma renovação
            self::SUBTYPE_9 => 0,  // Não renovável
            self::SUBTYPE_10 => 0, // Não renovável
            self::SUBTYPE_11 => 0, // Não renovável
            self::SUBTYPE_12 => 1,  // Uma renovação
        };
    }

    /**
     * Check if the subtype is exempt from common requirements.
     */
    public function isExemptFromCommonRequirements(): bool
    {
        return $this === self::SUBTYPE_4; // Apenas subtipo 4 (Reconhecido Mérito)
    }

    /**
     * Get all subtypes.
     */
    public static function all(): array
    {
        return self::cases();
    }

    /**
     * Get subtype by value.
     */
    public static function fromValue(int $value): ?self
    {
        return match ($value) {
            1 => self::SUBTYPE_1,
            2 => self::SUBTYPE_2,
            3 => self::SUBTYPE_3,
            4 => self::SUBTYPE_4,
            5 => self::SUBTYPE_5,
            6 => self::SUBTYPE_6,
            7 => self::SUBTYPE_7,
            8 => self::SUBTYPE_8,
            9 => self::SUBTYPE_9,
            10 => self::SUBTYPE_10,
            11 => self::SUBTYPE_11,
            12 => self::SUBTYPE_12,
            default => null,
        };
    }
}
