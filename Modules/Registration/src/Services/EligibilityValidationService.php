<?php

namespace Modules\Registration\Services;

use App\Enums\RegistrationSubtype;
use App\Models\Country;
use Modules\Registration\Models\RegistrationType;

class EligibilityValidationService
{
    /**
     * Validate eligibility for certification registration.
     *
     * @param  array  $data  Form data from wizard steps
     * @param  int  $category  Category number (1, 2, or 3)
     * @return array{eligible: bool, issues: array, reason: ?string}
     */
    public function validateForCertification(array $data, int $category): array
    {
        $issues = [];
        $registrationType = RegistrationType::where('category_number', $category)
            ->where('code', 'like', 'CERT-%')
            ->first();

        if (! $registrationType) {
            return [
                'eligible' => false,
                'issues' => ['Tipo de inscrição não encontrado.'],
                'reason' => 'Tipo de inscrição inválido.',
            ];
        }

        $criteria = $registrationType->getEligibilityCriteria();

        // Check nationality requirement
        if (isset($criteria['nationality'])) {
            $nationalityId = $data['nationality_id'] ?? null;
            $nationality = $nationalityId ? Country::find($nationalityId) : null;

            if ($category === 1 || $category === 2) {
                // Categories 1 and 2: Must be Mozambican
                if (! $nationality || ! $this->isMozambican($nationality)) {
                    $issues[] = 'Categoria requer nacionalidade moçambicana.';
                }
            } elseif ($category === 3) {
                // Category 3: Must be foreign
                if ($nationality && $this->isMozambican($nationality)) {
                    $issues[] = 'Categoria 3 é exclusiva para médicos estrangeiros.';
                }
            }
        }

        // Check training country requirement (only if training_country_id is provided)
        // This validation should be done in AcademicProfessionalStep when the field is available
        if (isset($criteria['training_country']) && isset($data['training_country_id'])) {
            $trainingCountryId = $data['training_country_id'];
            $trainingCountry = $trainingCountryId ? Country::find($trainingCountryId) : null;

            if ($category === 1) {
                // Category 1: Must be trained in Mozambique
                if (! $trainingCountry || ! $this->isMozambique($trainingCountry)) {
                    $issues[] = 'Categoria 1 requer formação em instituições moçambicanas.';
                }
            } elseif ($category === 2) {
                // Category 2: Must be trained abroad
                if ($trainingCountry && $this->isMozambique($trainingCountry)) {
                    $issues[] = 'Categoria 2 requer formação em instituições estrangeiras.';
                }
            } elseif ($category === 3) {
                // Category 3: Must be trained in Mozambique
                if (! $trainingCountry || ! $this->isMozambique($trainingCountry)) {
                    $issues[] = 'Categoria 3 requer formação em instituições moçambicanas.';
                }
            }
        }

        // Check age requirement (minimum 22 years)
        if (isset($data['birth_date'])) {
            $birthDate = \Carbon\Carbon::parse($data['birth_date']);
            $age = $birthDate->age;

            if ($age < 22) {
                $issues[] = 'Idade mínima de 22 anos é obrigatória para inscrição.';
            }
        }

        return [
            'eligible' => empty($issues),
            'issues' => $issues,
            'reason' => empty($issues) ? null : implode(' ', $issues),
        ];
    }

    /**
     * Validate eligibility for provisional registration.
     *
     * @param  array  $data  Form data from wizard steps
     * @param  int  $subtype  Subtype number (1-12)
     * @return array{eligible: bool, issues: array, reason: ?string}
     */
    public function validateForProvisional(array $data, int $subtype): array
    {
        $issues = [];
        $registrationType = RegistrationType::where('subtype_number', $subtype)
            ->where('code', 'like', 'PROV-%')
            ->first();

        if (! $registrationType) {
            return [
                'eligible' => false,
                'issues' => ['Tipo de inscrição não encontrado.'],
                'reason' => 'Tipo de inscrição inválido.',
            ];
        }

        $criteria = $registrationType->getEligibilityCriteria();

        // CRITICAL: Provisional registrations are EXCLUSIVELY for FOREIGN doctors
        $nationalityId = $data['nationality_id'] ?? null;
        $nationality = $nationalityId ? Country::find($nationalityId) : null;

        if ($nationality && $this->isMozambican($nationality)) {
            $issues[] = 'Inscrições provisórias são exclusivas para médicos estrangeiros. Moçambicanos devem usar Pré-inscrição para Certificação ou Inscrição Efetiva.';
        }

        // Check subtype-specific requirements
        $subtypeEnum = RegistrationSubtype::tryFrom($subtype);
        if ($subtypeEnum) {
            // Check if exempt from common requirements (Subtype 4)
            if ($subtypeEnum->isExemptFromCommonRequirements()) {
                // Subtype 4 has different requirements
                // Additional validation can be added here
            }
        }

        // Check age requirement (minimum 22 years)
        if (isset($data['birth_date'])) {
            $birthDate = \Carbon\Carbon::parse($data['birth_date']);
            $age = $birthDate->age;

            if ($age < 22) {
                $issues[] = 'Idade mínima de 22 anos é obrigatória para inscrição.';
            }
        }

        return [
            'eligible' => empty($issues),
            'issues' => $issues,
            'reason' => empty($issues) ? null : implode(' ', $issues),
        ];
    }

    /**
     * Validate eligibility for effective registration.
     *
     * @param  array  $data  Form data from wizard steps
     * @param  string  $grade  Grade (A, B, or C)
     * @return array{eligible: bool, issues: array, reason: ?string}
     */
    public function validateForEffective(array $data, string $grade): array
    {
        $issues = [];

        // CRITICAL: Effective registrations are EXCLUSIVELY for MOZAMBICAN doctors
        $nationalityId = $data['nationality_id'] ?? null;
        $nationality = $nationalityId ? Country::find($nationalityId) : null;

        if (! $nationality || ! $this->isMozambican($nationality)) {
            $issues[] = 'Inscrições efetivas são exclusivas para médicos moçambicanos.';
        }

        // Check if exam result exists and is approved
        $examResultId = $data['exam_result_id'] ?? null;
        if (! $examResultId) {
            $issues[] = 'É necessário ter resultado de exame aprovado para inscrição efetiva.';
        }

        return [
            'eligible' => empty($issues),
            'issues' => $issues,
            'reason' => empty($issues) ? null : implode(' ', $issues),
        ];
    }

    /**
     * Get eligibility issues for a registration type.
     *
     * @param  array  $data  Form data from wizard steps
     */
    public function getEligibilityIssues(array $data, RegistrationType $registrationType): array
    {
        // Certification types are identified by code starting with "CERT-"
        if ($registrationType->isCertification()) {
            return $this->validateForCertification(
                $data,
                $registrationType->category_number ?? 0
            );
        }

        $category = $registrationType->category;

        return match ($category) {
            \App\Enums\RegistrationCategory::PROVISIONAL => $this->validateForProvisional(
                $data,
                $registrationType->subtype_number ?? 0
            ),
            \App\Enums\RegistrationCategory::EFFECTIVE => $this->validateForEffective(
                $data,
                $registrationType->grade ?? ''
            ),
            default => [
                'eligible' => false,
                'issues' => ['Categoria de inscrição não reconhecida.'],
                'reason' => 'Categoria inválida.',
            ],
        };
    }

    /**
     * Check if country is Mozambique.
     */
    protected function isMozambique(Country $country): bool
    {
        // Check by name (case-insensitive) or ISO code
        return strtolower($country->name ?? '') === 'moçambique' ||
            strtolower($country->name ?? '') === 'mozambique' ||
            strtolower($country->iso ?? '') === 'moz' ||
            strtolower($country->iso ?? '') === 'mz' ||
            strtolower($country->code ?? '') === 'moz' ||
            strtolower($country->code ?? '') === 'mz';
    }

    /**
     * Check if nationality is Mozambican.
     */
    protected function isMozambican(?Country $country): bool
    {
        if (! $country) {
            return false;
        }

        return $this->isMozambique($country);
    }
}
