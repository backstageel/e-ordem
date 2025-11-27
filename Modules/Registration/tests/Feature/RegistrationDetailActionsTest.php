<?php

use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use App\Models\Person;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

function makeAdminUser(): User {
    Role::firstOrCreate(['name' => 'admin']);
    $user = User::factory()->create();
    $user->assignRole('admin');
    return $user;
}

function makeRegistrationWithDocs(int $docCount = 2): Registration {
    $type = RegistrationType::factory()->create([
        'fee' => 3000,
        'category' => 'effective',
        'payment_type_code' => 'enrollment_fee',
    ]);
    $person = Person::factory()->create();
    $registration = Registration::factory()->create([
        'registration_type_id' => $type->id,
        'person_id' => $person->id,
        'status' => 'submitted',
        'documents_validated' => false,
        'is_paid' => false,
    ]);
    // Use existing document type (identity_document)
    $docType = DocumentType::where('code', 'identity_document')->first();
    for ($i = 0; $i < $docCount; $i++) {
        Document::factory()->create([
            'registration_id' => $registration->id,
            'document_type_id' => $docType->id,
            'status' => 'pending',
            'file_path' => 'dummy/file.pdf',
            'original_filename' => 'file'.$i.'.pdf',
        ]);
    }
    return $registration->fresh(['documents','registrationType','person']);
}

it('exports registration to PDF', function () {
    $this->actingAs(makeAdminUser());
    $registration = makeRegistrationWithDocs(1);

    $response = $this->get(route('admin.registrations.export-pdf', $registration));
    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('application/pdf');
});

it('approves a single document and updates registration flag when all validated', function () {
    Notification::fake();
    $this->actingAs(makeAdminUser());
    $registration = makeRegistrationWithDocs(1);
    $document = $registration->documents->first();

    $this->post(route('admin.registrations.documents.approve', [$registration, $document]))
        ->assertRedirect();

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'status' => 'validated',
    ]);

    $this->assertDatabaseHas('registrations', [
        'id' => $registration->id,
        'documents_validated' => true,
    ]);

    Notification::assertSentOnDemandTimes(\App\Notifications\SimpleRegistrationNotification::class, 1);
});

it('rejects a single document and clears registration validated flag', function () {
    $this->actingAs(makeAdminUser());
    $registration = makeRegistrationWithDocs(1);
    $document = $registration->documents->first();

    $this->post(route('admin.registrations.documents.reject', [$registration, $document]), [
        'reason' => 'Documento ilegível',
    ])->assertRedirect();

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'status' => 'rejected',
        'rejection_reason' => 'Documento ilegível',
    ]);

    $this->assertDatabaseHas('registrations', [
        'id' => $registration->id,
        'documents_validated' => false,
    ]);
});

it('approves all documents', function () {
    $this->actingAs(makeAdminUser());
    $registration = makeRegistrationWithDocs(2);

    $this->post(route('admin.registrations.documents.approve-all', $registration))
        ->assertRedirect();

    foreach ($registration->documents as $doc) {
        $this->assertDatabaseHas('documents', [ 'id' => $doc->id, 'status' => 'validated' ]);
    }
    $this->assertDatabaseHas('registrations', [ 'id' => $registration->id, 'documents_validated' => true ]);
});

it('rejects all documents', function () {
    $this->actingAs(makeAdminUser());
    $registration = makeRegistrationWithDocs(2);

    $this->post(route('admin.registrations.documents.reject-all', $registration), [ 'reason' => 'Faltam páginas' ])
        ->assertRedirect();

    foreach ($registration->documents as $doc) {
        $this->assertDatabaseHas('documents', [ 'id' => $doc->id, 'status' => 'rejected' ]);
    }
    $this->assertDatabaseHas('registrations', [ 'id' => $registration->id, 'documents_validated' => false ]);
});

it('validates payment via controller', function () {
    Notification::fake();
    $this->actingAs(makeAdminUser());
    $registration = makeRegistrationWithDocs(1);

    $payload = [
        'payment_date' => now()->toDateString(),
        'payment_method' => 'Transferência',
        'reference_number' => 'REF-123',
        'amount' => 3000,
    ];

    $this->post(route('admin.registrations.validate-payment', $registration), $payload)
        ->assertRedirect();

    $this->assertDatabaseHas('registrations', [ 'id' => $registration->id, 'is_paid' => true ]);
    $this->assertDatabaseHas('payments', [ 'payable_type' => \Modules\Registration\Models\Registration::class, 'payable_id' => $registration->id, 'reference_number' => 'REF-123', 'status' => 'completed' ]);
    Notification::assertSentOnDemandTimes(\App\Notifications\SimpleRegistrationNotification::class, 1);
});

it('approves registration cascading docs and payment if needed', function () {
    Notification::fake();
    $this->actingAs(makeAdminUser());
    $registration = makeRegistrationWithDocs(1);

    $this->post(route('admin.registrations.approve', $registration))->assertRedirect();

    $this->assertDatabaseHas('registrations', [ 'id' => $registration->id, 'status' => 'approved', 'is_paid' => true ]);
    foreach ($registration->documents as $doc) {
        $this->assertDatabaseHas('documents', [ 'id' => $doc->id, 'status' => 'validated' ]);
    }
    Notification::assertSentOnDemandTimes(\App\Notifications\SimpleRegistrationNotification::class, 1);
});

it('rejects registration with reason', function () {
    $this->actingAs(makeAdminUser());
    $registration = makeRegistrationWithDocs(1);

    $this->post(route('admin.registrations.reject', $registration), [ 'rejection_reason' => 'Dados incorretos' ])
        ->assertRedirect();

    $this->assertDatabaseHas('registrations', [ 'id' => $registration->id, 'status' => 'rejected', 'rejection_reason' => 'Dados incorretos' ]);
});


