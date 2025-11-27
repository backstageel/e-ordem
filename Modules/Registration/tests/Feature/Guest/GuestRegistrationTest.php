<?php

use App\Models\DocumentType;
use App\Models\Gender;
use App\Models\Person;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;

uses(Tests\TestCase::class);
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Create a gender
    $this->gender = Gender::firstOrCreate([
        'name' => 'Masculino',
    ], [
        'code' => 'M',
    ]);

    // Create registration type if it doesn't exist
    $this->registrationType = RegistrationType::firstOrCreate(
        ['code' => 'provisional_private'],
        [
            'name' => 'Provisional Private',
            'category' => 'provisional',
            'fee' => 5000.00,
            'payment_type_code' => 'registration_fee',
        ]
    );

    // Create document type if it doesn't exist
    $this->documentType = DocumentType::firstOrCreate(
        ['code' => 'identity_document'],
        [
            'name' => 'Identity Document',
            'description' => 'Identity document',
        ]
    );
});

// Legacy guest registration pages removed; covered by new wizard tests.

test('success page is displayed with registration number', function () {
    $registrationNumber = 'PROV-2024-0001';

    $response = $this->withSession(['registration_number' => $registrationNumber])
        ->get(route('guest.registrations.success'));

    $response->assertStatus(200);
    $response->assertViewIs('registration::guest.registrations.success');
    $response->assertSee($registrationNumber);
});

test('check status form is displayed', function () {
    $response = $this->get(route('guest.registrations.check-status'));

    $response->assertStatus(200);
    $response->assertViewIs('registration::guest.registrations.check-status');
    $response->assertSee('Verificar Status da Inscrição');
    $response->assertSee('Número de Inscrição');
    $response->assertSee('Número do Documento de Identidade');
});

test('show registration status with valid data', function () {
    // Create a person with unique data
    $uniqueId = uniqid();
    $person = Person::create([
        'first_name' => 'João',
        'last_name' => 'Silva',
        'email' => "joao{$uniqueId}@example.com",
        'phone' => "123456789{$uniqueId}",
        'identity_document_number' => "123456789{$uniqueId}",
    ]);

    // Create a registration
    $registration = Registration::create([
        'person_id' => $person->id,
        'registration_type_id' => $this->registrationType->id,
        'registration_number' => "PROV-2024-{$uniqueId}",
        'status' => 'submitted',
        'submission_date' => now(),
        'is_paid' => false,
        'documents_validated' => false,
    ]);

    $response = $this->post(route('guest.registrations.show-status'), [
        'registration_number' => "PROV-2024-{$uniqueId}",
        'identity_document_number' => "123456789{$uniqueId}",
    ]);

    $response->assertStatus(200);
    $response->assertViewIs('registration::guest.registrations.status');
    $response->assertSee("PROV-2024-{$uniqueId}");
    $response->assertSee('João Silva');
});

test('error is shown when registration is not found', function () {
    $response = $this->post(route('guest.registrations.show-status'), [
        'registration_number' => 'INVALID-2024-0001',
        'identity_document_number' => '123456789',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['error']);
});
