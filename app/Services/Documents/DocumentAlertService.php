<?php

namespace App\Services\Documents;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Notifications\DocumentExpiredNotification;
use App\Notifications\DocumentExpiringNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class DocumentAlertService
{
    /**
     * Days before expiry to send alerts.
     */
    protected array $alertDays = [30, 7, 1];

    /**
     * Check and send alerts for expiring documents.
     */
    public function checkExpiringDocuments(): int
    {
        $count = 0;
        $today = now()->startOfDay();

        foreach ($this->alertDays as $daysBefore) {
            $targetDate = $today->copy()->addDays($daysBefore);

            $documents = Document::whereNotNull('expiry_date')
                ->where('status', DocumentStatus::VALIDATED)
                ->whereDate('expiry_date', $targetDate->format('Y-m-d'))
                ->with(['registration', 'registration.person', 'member', 'member.person', 'person', 'documentType'])
                ->get();

            foreach ($documents as $document) {
                $this->sendExpiringAlert($document, $daysBefore);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Check and mark expired documents, then send notifications.
     */
    public function checkExpiredDocuments(): int
    {
        $count = 0;
        $today = now()->startOfDay();

        // Find documents that should be marked as expired
        $documents = Document::whereNotNull('expiry_date')
            ->where('status', '!=', DocumentStatus::EXPIRED)
            ->whereDate('expiry_date', '<', $today->format('Y-m-d'))
            ->with(['registration', 'registration.person', 'member', 'member.person', 'person', 'documentType'])
            ->get();

        foreach ($documents as $document) {
            // Mark as expired
            $document->update([
                'status' => DocumentStatus::EXPIRED,
            ]);

            // Send notification
            $this->sendExpiredAlert($document);
            $count++;
        }

        return $count;
    }

    /**
     * Send expiring alert notification.
     */
    protected function sendExpiringAlert(Document $document, int $daysUntilExpiry): void
    {
        try {
            $person = $this->getNotifiable($document);

            if (! $person || ! $person->email) {
                Log::warning("Document {$document->id} has no valid notifiable with email (person/member)");

                return;
            }

            // Use Notification facade to send via email directly
            \Illuminate\Support\Facades\Notification::route('mail', $person->email)
                ->notify(new DocumentExpiringNotification($document, $daysUntilExpiry));

            Log::info("Expiring alert sent for document {$document->id} ({$daysUntilExpiry} days before expiry)");
        } catch (\Exception $e) {
            Log::error("Failed to send expiring alert for document {$document->id}: ".$e->getMessage());
        }
    }

    /**
     * Send expired alert notification.
     */
    protected function sendExpiredAlert(Document $document): void
    {
        try {
            $person = $this->getNotifiable($document);

            if (! $person || ! $person->email) {
                Log::warning("Document {$document->id} has no valid notifiable with email (person/member)");

                return;
            }

            // Use Notification facade to send via email directly
            \Illuminate\Support\Facades\Notification::route('mail', $person->email)
                ->notify(new DocumentExpiredNotification($document));

            Log::info("Expired alert sent for document {$document->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send expired alert for document {$document->id}: ".$e->getMessage());
        }
    }

    /**
     * Get the notifiable entity (Person) from document.
     */
    protected function getNotifiable(Document $document): ?\App\Models\Person
    {
        // Priority: registration person > member person > person directly
        if ($document->registration && $document->registration->person) {
            return $document->registration->person;
        }

        if ($document->member && $document->member->person) {
            return $document->member->person;
        }

        if ($document->person) {
            return $document->person;
        }

        return null;
    }

    /**
     * Get all documents that need attention (expiring soon or expired).
     */
    public function getDocumentsNeedingAttention(): array
    {
        $today = now()->startOfDay();
        $thirtyDaysFromNow = $today->copy()->addDays(30);

        return [
            'expiring_soon' => Document::whereNotNull('expiry_date')
                ->where('status', DocumentStatus::VALIDATED)
                ->whereDate('expiry_date', '>', $today)
                ->whereDate('expiry_date', '<=', $thirtyDaysFromNow)
                ->with(['registration', 'member', 'documentType'])
                ->get(),
            'expired' => Document::where('status', DocumentStatus::EXPIRED)
                ->orWhere(function ($query) use ($today) {
                    $query->whereNotNull('expiry_date')
                        ->where('status', DocumentStatus::VALIDATED)
                        ->whereDate('expiry_date', '<', $today);
                })
                ->with(['registration', 'member', 'documentType'])
                ->get(),
        ];
    }
}
