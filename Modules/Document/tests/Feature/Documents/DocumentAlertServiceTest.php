<?php

namespace Modules\Document\Tests\Feature\Documents;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Person;
use App\Notifications\DocumentExpiredNotification;
use App\Notifications\DocumentExpiringNotification;
use App\Services\Documents\DocumentAlertService;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->person = Person::factory()->create(['email' => 'test@example.com']);
    $this->documentType = DocumentType::factory()->create();
    $this->service = app(DocumentAlertService::class);
});

it('marks expired documents and sends notifications', function () {
    Notification::fake();

    $document = Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->subDay(),
    ]);

    $count = $this->service->checkExpiredDocuments();

    expect($count)->toBe(1);

    $document->refresh();
    expect($document->status)->toBe(DocumentStatus::EXPIRED);

    Notification::assertSentTimes(DocumentExpiredNotification::class, 1);
});

it('sends expiring alerts at configured intervals', function () {
    Notification::fake();

    // Create documents expiring at different intervals
    Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->addDays(30),
    ]);

    Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->addDays(7),
    ]);

    Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->addDay(),
    ]);

    $count = $this->service->checkExpiringDocuments();

    expect($count)->toBeGreaterThan(0);
    Notification::assertSentTimes(DocumentExpiringNotification::class, $count);
});

it('identifies documents needing attention', function () {
    Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->addDays(15), // Expiring soon
    ]);

    Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
        'status' => DocumentStatus::EXPIRED,
    ]);

    $result = $this->service->getDocumentsNeedingAttention();

    expect($result['expiring_soon'])->not->toBeEmpty();
    expect($result['expired'])->not->toBeEmpty();
});
