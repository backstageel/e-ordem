<?php

namespace App\Documents;

use App\Enums\DocumentStatus;
use App\Enums\RegistrationStatus;
use App\Models\Document;
use App\Models\DocumentChecklist;
use Modules\Registration\Models\Registration;
use Illuminate\Support\Facades\Log;

class CheckDocumentPendenciesAction
{
    /**
     * Check document pendencies for a registration and update status if needed.
     */
    public function execute(Registration $registration): bool
    {
        $hasPendencies = $this->hasDocumentPendencies($registration);

        if ($hasPendencies && $registration->status !== RegistrationStatus::DOCUMENTS_PENDING) {
            // Update registration status to documents_pending
            $registration->update([
                'status' => RegistrationStatus::DOCUMENTS_PENDING,
            ]);

            Log::info("Registration {$registration->id} status updated to DOCUMENTS_PENDING due to document pendencies");

            return true;
        } elseif (! $hasPendencies && $registration->status === RegistrationStatus::DOCUMENTS_PENDING) {
            // If no pendencies but status is still DOCUMENTS_PENDING, check if we can move forward
            // This could be triggered after documents are submitted/corrected
            // For now, we'll just log it - status change should be manual or via workflow
            Log::info("Registration {$registration->id} has no document pendencies but status is still DOCUMENTS_PENDING");

            return false;
        }

        return $hasPendencies;
    }

    /**
     * Check if registration has document pendencies.
     */
    public function hasDocumentPendencies(Registration $registration): bool
    {
        // Get required documents for this registration type
        $requiredDocuments = $this->getRequiredDocuments($registration);

        if (empty($requiredDocuments)) {
            // No required documents, no pendencies
            return false;
        }

        // Check each required document
        foreach ($requiredDocuments as $documentTypeId) {
            $isValid = $this->isDocumentValid($registration, $documentTypeId);

            if (! $isValid) {
                return true; // Has at least one pending document
            }
        }

        return false; // All documents are valid
    }

    /**
     * Get required document type IDs for registration.
     */
    protected function getRequiredDocuments(Registration $registration): array
    {
        $registrationType = $registration->registrationType;

        if (! $registrationType || ! $registrationType->required_documents) {
            return [];
        }

        // Get document checklist items
        $checklistItems = DocumentChecklist::where('registration_type_id', $registrationType->id)
            ->where('is_required', true)
            ->where('is_active', true)
            ->pluck('document_type_id')
            ->toArray();

        return $checklistItems;
    }

    /**
     * Check if a specific document type is valid for registration.
     */
    protected function isDocumentValid(Registration $registration, int $documentTypeId): bool
    {
        // Find the latest valid document of this type for this registration
        $document = Document::where('registration_id', $registration->id)
            ->where('document_type_id', $documentTypeId)
            ->whereIn('status', [
                DocumentStatus::VALIDATED,
            ])
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->latest('submission_date')
            ->first();

        // Document must exist, be validated, and not expired
        return $document !== null;
    }

    /**
     * Get list of pending documents for registration.
     */
    public function getPendingDocuments(Registration $registration): array
    {
        $requiredDocuments = $this->getRequiredDocuments($registration);
        $pending = [];

        foreach ($requiredDocuments as $documentTypeId) {
            $isValid = $this->isDocumentValid($registration, $documentTypeId);

            if (! $isValid) {
                // Check if document exists but is invalid
                $document = Document::where('registration_id', $registration->id)
                    ->where('document_type_id', $documentTypeId)
                    ->latest('submission_date')
                    ->first();

                $pending[] = [
                    'document_type_id' => $documentTypeId,
                    'status' => $document?->status ?? 'missing',
                    'has_document' => $document !== null,
                    'is_expired' => $document?->isExpired() ?? false,
                    'needs_correction' => $document?->status === DocumentStatus::REQUIRES_CORRECTION,
                    'is_rejected' => $document?->status === DocumentStatus::REJECTED,
                ];
            }
        }

        return $pending;
    }

    /**
     * Check if all documents are valid for registration.
     */
    public function allDocumentsValid(Registration $registration): bool
    {
        return ! $this->hasDocumentPendencies($registration);
    }
}
