<?php

namespace App\Services\Member;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\Member;

class MemberComplianceService
{
    public function checkMemberCompliance(Member $member): array
    {
        $issues = [];

        // Check documents
        $expiredDocuments = $this->getExpiredDocuments($member);
        if (! empty($expiredDocuments)) {
            $issues['expired_documents'] = $expiredDocuments;
        }

        $missingDocuments = $this->getMissingRequiredDocuments($member);
        if (! empty($missingDocuments)) {
            $issues['missing_documents'] = $missingDocuments;
        }

        // Check profile update
        if ($this->needsProfileUpdate($member)) {
            $issues['profile_update_required'] = true;
        }

        // Check quotas
        if (! $member->isQuotaRegular()) {
            $issues['quota_irregular'] = true;
            $issues['quota_irregular_details'] = [
                'overdue_count' => $member->overdueQuotas()->count(),
                'total_due' => $member->totalQuotaDue(),
            ];
        }

        return $issues;
    }

    public function isCompliant(Member $member): bool
    {
        $issues = $this->checkMemberCompliance($member);

        return empty($issues);
    }

    private function getExpiredDocuments(Member $member): array
    {
        $graceDays = config('members.documents.expiry_grace_days', 0);
        $expiryThreshold = now()->addDays($graceDays);

        return Document::where('member_id', $member->id)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', $expiryThreshold)
            ->whereIn('status', [DocumentStatus::VALIDATED, DocumentStatus::PENDING])
            ->get()
            ->toArray();
    }

    private function getMissingRequiredDocuments(Member $member): array
    {
        // Define required document types based on member type
        $requiredTypes = $this->getRequiredDocumentTypes($member);

        $existingTypes = $member->documents()
            ->where('status', DocumentStatus::VALIDATED)
            ->pluck('document_type_id')
            ->toArray();

        return array_diff($requiredTypes, $existingTypes);
    }

    private function getRequiredDocumentTypes(Member $member): array
    {
        // Default required documents for all members
        // This can be customized based on member type, registration type, etc.
        $defaultTypes = \App\Models\DocumentType::whereIn('code', [
            'identity_document',
            'diploma',
        ])->pluck('id')->toArray();

        // Add specific requirements based on member characteristics
        // Example: if member has specialty, require specialty certificate
        if ($member->medical_speciality_id) {
            $specialtyCert = \App\Models\DocumentType::where('code', 'specialty_certificate')
                ->first();
            if ($specialtyCert) {
                $defaultTypes[] = $specialtyCert->id;
            }
        }

        return array_unique($defaultTypes);
    }

    private function needsProfileUpdate(Member $member): bool
    {
        $intervalYears = config('members.profile.update_interval_years', 5);
        $lastUpdate = $member->updated_at;
        $requiredUpdateDate = $lastUpdate->copy()->addYears($intervalYears);

        return now()->greaterThan($requiredUpdateDate);
    }

    public function getDocumentsExpiringSoon(Member $member, int $days = 30): array
    {
        $expiryAlertDays = config('members.documents.expiry_alert_days', $days);

        return Document::where('member_id', $member->id)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addDays($expiryAlertDays))
            ->whereIn('status', [DocumentStatus::VALIDATED, DocumentStatus::PENDING])
            ->get()
            ->toArray();
    }
}
