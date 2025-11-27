<?php

use App\Models\DocumentType;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Person;
use App\Models\User;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use Spatie\Permission\Models\Role;

uses(Tests\TestCase::class);
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Illuminate\Foundation\Testing\WithFaker::class);

beforeEach(function () {
    // Create roles
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'member']);
    Role::firstOrCreate(['name' => 'teacher']);
    Role::firstOrCreate(['name' => 'evaluator']);

    // Create a user for authentication
    $this->user = User::factory()->create();

    // Assign admin role to the user
    $this->user->assignRole('admin');

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

    // Create a payment method
    $this->paymentMethod = PaymentMethod::create([
        'name' => 'Test Payment Method',
        'description' => 'Test payment method',
        'is_active' => true,
    ]);

    // Create a payment type
    $this->paymentType = PaymentType::create([
        'code' => 'registration_fee',
        'name' => 'Registration Fee',
        'description' => 'Fee for registration',
        'default_amount' => 1000.00,
        'is_active' => true,
    ]);

    // Use existing document type from database
    $this->documentType = DocumentType::first();
});

// Legacy guest multi-step removed; covered by wizard flow tests.

test('approving registration creates member', function () {
    // Create a person
    $person = Person::factory()->create();

    // Create a registration
    $registration = Registration::create([
        'person_id' => $person->id,
        'registration_type_id' => $this->registrationType->id,
        'registration_number' => 'TEST-'.date('Y').'-'.rand(1000, 9999),
        'status' => 'submitted',
        'submission_date' => now(),
        'notes' => 'Test registration',
        'is_paid' => true,
        'payment_reference' => 'TEST-REF-'.rand(1000, 9999),
        'payment_date' => now(),
        'payment_amount' => 1000.00,
        'documents_validated' => false,
        'professional_category' => 'Test Category',
        'sub_specialty' => 'Test Sub-specialty',
        'workplace' => 'Test Workplace',
        'workplace_address' => 'Test Workplace Address',
        'workplace_phone' => '123456789',
        'workplace_email' => 'test@workplace.com',
        'academic_degree' => 'PhD',
    ]);

    // Create a payment for the registration
    $payment = Payment::create([
        'payment_type_id' => $this->paymentType->id,
        'payment_method_id' => $this->paymentMethod->id,
        'reference_number' => 'REG-'.$registration->registration_number,
        'amount' => 1000.00,
        'payment_date' => now(),
        'status' => 'completed',
        'notes' => 'Payment for registration '.$registration->registration_number,
        'payable_type' => \Modules\Registration\Models\Registration::class,
        'payable_id' => $registration->id,
        'person_id' => $registration->person_id,
        'recorded_by' => $this->user->id,
    ]);

    // Refresh the registration to ensure it's in the database
    $registration->refresh();

    // Debug: Check the registration status
    expect($registration->status->value)->toBe('submitted');

    // Approve the registration
    $response = $this->actingAs($this->user)
        ->post(route('admin.registrations.approve', $registration), [
            'notes' => 'Approved for testing',
        ]);

    // Assert redirect to registration show page
    $response->assertRedirect(route('admin.registrations.show', $registration));

    // Refresh the registration from the database
    $registration->refresh();

    // Assert registration was approved
    expect($registration->status)->toEqual(\App\Enums\RegistrationStatus::APPROVED);
    expect($registration->approval_date)->not->toBeNull();
    expect($registration->person_id)->not->toBeNull();

    // Assert member was created
    $member = Member::where('person_id', $person->id)->first();
    $this->assertDatabaseHas('members', [
        'id' => $member->id,
        'person_id' => $person->id,
        'professional_category' => 'Test Category',
        'status' => 'active',
    ]);

    // Assert payment was linked to the member
    $payment->refresh();
    expect($payment->member_id)->toEqual($member->id);
});

test('admin wizard pages load for create and edit flows', function () {
    // create
    $response = $this->actingAs($this->user)
        ->get(route('guest.registrations.type-selection'));
    $response->assertOk();

    // edit
    $registration = Registration::factory()->create([
        'registration_type_id' => $this->registrationType->id,
        'person_id' => Person::factory()->create()->id,
        'status' => 'submitted',
    ]);
    $response = $this->actingAs($this->user)
        ->get(route('admin.registrations.edit-wizard', $registration));
    $response->assertOk();
});
