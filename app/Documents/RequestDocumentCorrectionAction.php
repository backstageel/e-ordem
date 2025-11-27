<?php

namespace App\Documents;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\DocumentReview;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RequestDocumentCorrectionAction
{
    /**
     * Request correction for a document.
     */
    public function execute(Document $document, User $reviewer, string $feedback, ?string $rejectionReason = null): DocumentReview
    {
        // Update document status to requires_correction
        $document->update([
            'status' => DocumentStatus::REQUIRES_CORRECTION,
            'notes' => $feedback,
            'rejection_reason' => $rejectionReason,
        ]);

        // Create review record
        $review = DocumentReview::create([
            'document_id' => $document->id,
            'reviewed_by' => $reviewer->id,
            'review_status' => 'request_changes',
            'review_notes' => $feedback,
            'feedback' => $feedback,
            'reviewed_at' => now(),
            'review_order' => $this->getNextReviewOrder($document),
            'previous_review_id' => $document->latestReview?->id,
        ]);

        // Update registration status if needed
        if ($document->registration) {
            $checkPendencies = app(CheckDocumentPendenciesAction::class);
            $checkPendencies->execute($document->registration);
        }

        Log::info("Document {$document->id} correction requested by reviewer {$reviewer->id}");

        return $review;
    }

    /**
     * Reject a document permanently.
     */
    public function reject(Document $document, User $reviewer, string $rejectionReason): DocumentReview
    {
        // Update document status to rejected
        $document->update([
            'status' => DocumentStatus::REJECTED,
            'rejection_reason' => $rejectionReason,
        ]);

        // Create review record
        $review = DocumentReview::create([
            'document_id' => $document->id,
            'reviewed_by' => $reviewer->id,
            'review_status' => 'rejected',
            'review_notes' => $rejectionReason,
            'feedback' => $rejectionReason,
            'reviewed_at' => now(),
            'review_order' => $this->getNextReviewOrder($document),
            'previous_review_id' => $document->latestReview?->id,
        ]);

        // Update registration status if needed
        if ($document->registration) {
            $checkPendencies = app(CheckDocumentPendenciesAction::class);
            $checkPendencies->execute($document->registration);
        }

        Log::info("Document {$document->id} rejected by reviewer {$reviewer->id}");

        return $review;
    }

    /**
     * Allow document resubmission (reset status after correction request).
     */
    public function allowResubmission(Document $document): Document
    {
        // Reset status to pending to allow new upload
        if ($document->status === DocumentStatus::REQUIRES_CORRECTION || $document->status === DocumentStatus::REJECTED) {
            $document->update([
                'status' => DocumentStatus::PENDING,
                'notes' => null, // Clear previous notes for new submission
            ]);

            Log::info("Document {$document->id} resubmission allowed - status reset to PENDING");
        }

        return $document;
    }

    /**
     * Get next review order for document.
     */
    protected function getNextReviewOrder(Document $document): int
    {
        $lastReview = DocumentReview::where('document_id', $document->id)
            ->orderBy('review_order', 'desc')
            ->first();

        return ($lastReview?->review_order ?? 0) + 1;
    }
}
