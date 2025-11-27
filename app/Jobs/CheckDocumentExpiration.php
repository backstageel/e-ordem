<?php

namespace App\Jobs;

use App\Services\Documents\DocumentAlertService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckDocumentExpiration implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(DocumentAlertService $alertService): void
    {
        Log::info('Starting document expiration check job');

        // Check and mark expired documents
        $expiredCount = $alertService->checkExpiredDocuments();
        Log::info("Marked {$expiredCount} documents as expired");

        // Check and send alerts for expiring documents
        $expiringCount = $alertService->checkExpiringDocuments();
        Log::info("Sent {$expiringCount} expiring document alerts");

        Log::info('Document expiration check job completed');
    }
}
