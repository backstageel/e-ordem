<?php

namespace App\Services\Exam;

use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;

class ExamEligibilityService
{
    public function checkEligibility(Exam $exam, int $userId): array
    {
        $user = User::findOrFail($userId);
        $person = $user->person;

        if (! $person) {
            return [
                'eligible' => false,
                'reason' => 'Pessoa não encontrada associada ao utilizador',
            ];
        }

        // Check if user has professional card (should not have)
        $member = $person->member;
        if ($member && $member->member_number) {
            return [
                'eligible' => false,
                'reason' => 'Candidato já possui carteira profissional da OrMM',
            ];
        }

        // Check nationality or residence
        if (! $this->checkNationalityOrResidence($person)) {
            return [
                'eligible' => false,
                'reason' => 'Candidato não é moçambicano nem possui autorização de residência válida',
            ];
        }

        // Check license requirements
        if (! $this->checkLicenseRequirements($person)) {
            return [
                'eligible' => false,
                'reason' => 'Candidato não possui licenciatura válida',
            ];
        }

        // Check date requirements for Mozambican graduates
        if (! $this->checkDateRequirements($person)) {
            return [
                'eligible' => false,
                'reason' => 'Graduação anterior a 1 de Junho de 2016 - não é elegível para exame obrigatório',
            ];
        }

        // All checks passed
        return [
            'eligible' => true,
            'reason' => null,
            'exemption_type' => $this->checkExemptions($person),
        ];
    }

    private function checkNationalityOrResidence($person): bool
    {
        // Must be Mozambican or have valid residence authorization
        $isMozambican = $person->nationality_id && $this->isMozambicanNationality($person->nationality_id);

        if ($isMozambican) {
            return true;
        }

        // Check for valid residence authorization or passport with visa
        // This would need to be checked against documents
        return false; // Simplified - should check documents
    }

    private function checkLicenseRequirements($person): bool
    {
        // Check if person has valid academic qualification (license)
        $academic = $person->academicQualifications()
            ->where('qualification_type', 'like', '%licenciatura%')
            ->orWhere('qualification_type', 'like', '%medicine%')
            ->orWhere('qualification_type', 'like', '%dentistry%')
            ->first();

        return $academic !== null;
    }

    private function checkDateRequirements($person): bool
    {
        $mandatoryStartDate = Carbon::parse(config('exams.eligibility.mandatory_start_date', '2016-06-01'));

        // If foreign graduate, always eligible regardless of date
        if (! $this->isMozambicanNationality($person->nationality_id)) {
            return true;
        }

        // Check graduation date
        $academic = $person->academicQualifications()
            ->where('qualification_type', 'like', '%licenciatura%')
            ->first();

        if (! $academic || ! $academic->completion_date) {
            return true; // Assume eligible if no date available
        }

        $graduationDate = Carbon::parse($academic->completion_date);

        // If graduated before mandatory date, check if from non-accredited institution
        if ($graduationDate->lt($mandatoryStartDate)) {
            // Check if from accredited institution - would need institution check
            // For now, assume eligible if from non-accredited
            return true;
        }

        return true; // Graduated after mandatory date or foreign
    }

    private function checkExemptions($person): ?string
    {
        // Check for philanthropic mission exemptions
        // This would need to check work experience or special records
        return null;
    }

    private function isMozambicanNationality(?int $nationalityId): bool
    {
        if (! $nationalityId) {
            return false;
        }

        // Check if nationality is Mozambique (would need to check Country model)
        // Simplified - would need actual country code check
        return true; // Simplified
    }
}
