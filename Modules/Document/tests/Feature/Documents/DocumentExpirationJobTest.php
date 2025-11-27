<?php

namespace Modules\Document\Tests\Feature\Documents;

use App\Enums\DocumentStatus;
use App\Jobs\CheckDocumentExpiration;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Person;
use App\Notifications\DocumentExpiredNotification;
use App\Notifications\DocumentExpiringNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->person = Person::factory()->create(['email' => 'test@example.com']);
    $this->documentType = DocumentType::factory()->create();
});

it('marks documents as expired when expiry date has passed', function () {
    $document = Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->subDay(),
    ]);

    $alertService = app(\App\Services\Documents\DocumentAlertService::class);
    $job = new CheckDocumentExpiration;
    $job->handle($alertService);

    $document->refresh();

    expect($document->status)->toBe(DocumentStatus::EXPIRED);
});

it('sends expiring notifications at configured intervals', function () {
    Notification::fake();

    // Document expiring in 30 days
    Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->addDays(30),
    ]);

    // Document expiring in 7 days
    Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->addDays(7),
    ]);

    $alertService = app(\App\Services\Documents\DocumentAlertService::class);
    $job = new CheckDocumentExpiration;
    $job->handle($alertService);

    Notification::assertSentTimes(DocumentExpiringNotification::class, 2);
});

it('sends expired notification when document expires', function () {
    Notification::fake();

    $document = Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->subDay(),
    ]);

    $alertService = app(\App\Services\Documents\DocumentAlertService::class);
    $job = new CheckDocumentExpiration;
    $job->handle($alertService);

    Notification::assertSentTimes(DocumentExpiredNotification::class, 1);
});
