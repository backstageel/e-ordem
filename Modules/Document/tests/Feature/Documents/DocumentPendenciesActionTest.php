<?php

namespace Modules\Document\Tests\Feature\Documents;

use App\Documents\CheckDocumentPendenciesAction;
use App\Enums\DocumentStatus;
use App\Enums\RegistrationStatus;
use App\Models\Document;
use App\Models\DocumentChecklist;
use App\Models\DocumentType;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;

beforeEach(function () {
    $this->registrationType = RegistrationType::factory()->create([
        'required_documents' => ['BI', 'PASSPORT'],
    ]);

    $this->documentType1 = DocumentType::factory()->create(['code' => 'BI']);
    $this->documentType2 = DocumentType::factory()->create(['code' => 'PASSPORT']);

    // Create checklists
    DocumentChecklist::create([
        'registration_type_id' => $this->registrationType->id,
        'document_type_id' => $this->documentType1->id,
        'is_required' => true,
        'is_active' => true,
    ]);

    DocumentChecklist::create([
        'registration_type_id' => $this->registrationType->id,
        'document_type_id' => $this->documentType2->id,
        'is_required' => true,
        'is_active' => true,
    ]);
});

it('sets registration status to documents_pending when required documents are missing', function () {
    $registration = Registration::factory()->create([
        'registration_type_id' => $this->registrationType->id,
        'status' => RegistrationStatus::SUBMITTED,
    ]);

    $action = new CheckDocumentPendenciesAction;
    $action->execute($registration);

    $registration->refresh();

    expect($registration->status)->toBe(RegistrationStatus::DOCUMENTS_PENDING);
});

it('does not change status when all required documents are valid', function () {
    $registration = Registration::factory()->create([
        'registration_type_id' => $this->registrationType->id,
        'status' => RegistrationStatus::SUBMITTED,
    ]);

    // Create valid documents for both required types
    Document::factory()->create([
        'registration_id' => $registration->id,
        'document_type_id' => $this->documentType1->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->addYear(),
    ]);

    Document::factory()->create([
        'registration_id' => $registration->id,
        'document_type_id' => $this->documentType2->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->addYear(),
    ]);

    $action = new CheckDocumentPendenciesAction;
    $hasPendencies = $action->execute($registration);

    expect($hasPendencies)->toBeFalse();
});

it('detects expired documents as pendencies', function () {
    $registration = Registration::factory()->create([
        'registration_type_id' => $this->registrationType->id,
        'status' => RegistrationStatus::SUBMITTED,
    ]);

    Document::factory()->create([
        'registration_id' => $registration->id,
        'document_type_id' => $this->documentType1->id,
        'status' => DocumentStatus::VALIDATED,
        'expiry_date' => now()->subDay(), // Expired
    ]);

    $action = new CheckDocumentPendenciesAction;
    $hasPendencies = $action->hasDocumentPendencies($registration);

    expect($hasPendencies)->toBeTrue();
});
