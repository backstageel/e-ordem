<?php

use App\Enums\RegistrationCategory;
use App\Enums\RegistrationStatus;
use App\Models\Payment;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->registrationType = RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'PROV-01',
    ]);

    $this->person = Person::factory()->create([
        'identity_document_number' => '123456789',
    ]);

    $this->registration = Registration::factory()->create([
        'person_id' => $this->person->id,
        'registration_type_id' => $this->registrationType->id,
        'registration_number' => 'REG-2025-0001',
        'status' => RegistrationStatus::SUBMITTED,
    ]);
});

it('redirects wizard to type selection', function () {
    $response = $this->get(route('guest.registrations.wizard'));

    $response->assertRedirect(route('guest.registrations.type-selection'));
});

it('shows success page with registration number', function () {
    $response = $this->withSession(['registration_number' => 'REG-2025-0001'])
        ->get(route('guest.registrations.success'));

    $response->assertStatus(200);
    $response->assertViewIs('registration::guest.registrations.success');
    $response->assertSee('REG-2025-0001');
});

it('redirects success page if no registration number in session', function () {
    $response = $this->get(route('guest.registrations.success'));

    $response->assertRedirect(route('guest.registrations.type-selection'));
});

it('shows check status form', function () {
    $response = $this->get(route('guest.registrations.check-status'));

    $response->assertStatus(200);
    $response->assertViewIs('registration::guest.registrations.check-status');
});

it('shows registration status with valid data', function () {
    $response = $this->post(route('guest.registrations.show-status'), [
        'registration_number' => 'REG-2025-0001',
        'identity_document_number' => '123456789',
    ]);

    $response->assertStatus(200);
    $response->assertViewIs('registration::guest.registrations.status');
    $response->assertViewHas('registration');
});

it('shows error when registration not found', function () {
    $response = $this->post(route('guest.registrations.show-status'), [
        'registration_number' => 'INVALID-123',
        'identity_document_number' => '999999999',
    ]);

    $response->assertSessionHasErrors(['error']);
});

it('validates required fields for status check', function () {
    $response = $this->post(route('guest.registrations.show-status'), []);

    $response->assertSessionHasErrors(['registration_number', 'identity_document_number']);
});

it('shows payment information on success page if available', function () {
    $payment = Payment::factory()->create([
        'payable_type' => Registration::class,
        'payable_id' => $this->registration->id,
    ]);

    $response = $this->withSession(['registration_number' => 'REG-2025-0001'])
        ->get(route('guest.registrations.success'));

    $response->assertStatus(200);
    $response->assertViewHas('payment');
});
