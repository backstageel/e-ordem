<?php

use App\Enums\PaymentStatus;
use App\Enums\RegistrationStatus;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Person;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $this->adminUser = User::factory()->create();
    $this->adminUser->assignRole('admin');
    $this->actingAs($this->adminUser);

    $this->registrationType = RegistrationType::factory()->create([
        'name' => 'Provisional Private',
        'category' => 'provisional',
        'fee' => 5000.00,
        'payment_type_code' => 'registration_fee',
    ]);

    $this->person = Person::factory()->create();

    $this->registration = Registration::factory()->create([
        'person_id' => $this->person->id,
        'registration_type_id' => $this->registrationType->id,
        'status' => 'submitted',
        'is_paid' => false,
        'documents_validated' => false,
    ]);
});

describe('index', function () {
    it('displays the registrations index page', function () {
        $response = $this->get(route('admin.registrations.index'));

        $response->assertSuccessful();
        $response->assertViewIs('registration::admin.registrations.index');
    });

    it('displays all registrations', function () {
        Registration::factory()->count(5)->create();

        $response = $this->get(route('admin.registrations.index'));

        $response->assertSuccessful();
        $response->assertViewHas('registrations');
        expect($response->viewData('registrations')->total())->toBe(6); // 5 + 1 from beforeEach
    });

    it('filters registrations by search query', function () {
        $person1 = Person::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $person2 = Person::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);

        Registration::factory()->create([
            'person_id' => $person1->id,
            'registration_type_id' => $this->registrationType->id,
            'registration_number' => 'REG-001',
        ]);
        Registration::factory()->create([
            'person_id' => $person2->id,
            'registration_type_id' => $this->registrationType->id,
            'registration_number' => 'REG-002',
        ]);

        $response = $this->get(route('admin.registrations.index', ['filter' => ['search' => 'John']]));

        $response->assertSuccessful();
        $registrations = $response->viewData('registrations');
        expect($registrations->count())->toBeGreaterThanOrEqual(1);
    });

    it('filters registrations by status', function () {
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'status' => 'approved',
        ]);

        $response = $this->get(route('admin.registrations.index', ['filter' => ['status' => 'approved']]));

        $response->assertSuccessful();
        $registrations = $response->viewData('registrations');
        foreach ($registrations as $registration) {
            expect($registration->status)->toBe(RegistrationStatus::APPROVED);
        }
    });

    it('filters registrations by date range', function () {
        $dateFrom = now()->subDays(10)->toDateString();
        $dateTo = now()->toDateString();

        $response = $this->get(route('admin.registrations.index', [
            'filter' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ]));

        $response->assertSuccessful();
        $response->assertViewHas('registrations');
    });

    it('displays registration statistics', function () {
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'status' => 'approved',
        ]);
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'status' => 'rejected',
        ]);

        $response = $this->get(route('admin.registrations.index'));

        $response->assertSuccessful();
        $response->assertViewHas('totalCount');
        $response->assertViewHas('approvedCount');
        $response->assertViewHas('rejectedCount');
        $response->assertViewHas('pendingCount');
    });

    it('displays status and type options', function () {
        $response = $this->get(route('admin.registrations.index'));

        $response->assertSuccessful();
        $response->assertViewHas('statusOptions');
        $response->assertViewHas('typeOptions');
    });
});

describe('generateRegistrationNumber', function () {
    it('generates a registration number with correct format', function () {
        $controller = new \Modules\Registration\Http\Controllers\Admin\RegistrationController();

        $number = $controller->generateRegistrationNumber($this->registrationType);

        expect($number)->toMatch('/^PRO-\d{4}-[A-Z0-9]{4}$/');
    });

    it('generates unique registration numbers', function () {
        $controller = new \Modules\Registration\Http\Controllers\Admin\RegistrationController();

        $numbers = [];
        for ($i = 0; $i < 10; $i++) {
            $numbers[] = $controller->generateRegistrationNumber($this->registrationType);
        }

        expect(array_unique($numbers))->toHaveCount(10);
    });

    it('includes current year in registration number', function () {
        $controller = new \Modules\Registration\Http\Controllers\Admin\RegistrationController();

        $number = $controller->generateRegistrationNumber($this->registrationType);

        expect($number)->toContain(date('Y'));
    });

    it('uses first 3 characters of registration type name as prefix', function () {
        $controller = new \Modules\Registration\Http\Controllers\Admin\RegistrationController();
        $type = RegistrationType::factory()->create([
            'name' => 'Effective General',
            'payment_type_code' => 'test_code',
        ]);

        $number = $controller->generateRegistrationNumber($type);

        expect($number)->toStartWith('EFF-');
    });

    it('handles registration types with spaces in name', function () {
        $controller = new \Modules\Registration\Http\Controllers\Admin\RegistrationController();
        $type = RegistrationType::factory()->create([
            'name' => 'Provisional Private',
            'payment_type_code' => 'test_code',
        ]);

        $number = $controller->generateRegistrationNumber($type);

        expect($number)->toStartWith('PRO-');
    });
});

describe('show', function () {
    it('displays the registration show page', function () {
        $response = $this->get(route('admin.registrations.show', $this->registration));

        $response->assertSuccessful();
        $response->assertViewIs('registration::admin.registrations.show');
        $response->assertViewHas('registration');
    });

    it('loads registration with all required relationships', function () {
        // Create related data using existing document type
        $docType = DocumentType::first();
        Document::factory()->create([
            'registration_id' => $this->registration->id,
            'document_type_id' => $docType->id,
        ]);

        $response = $this->get(route('admin.registrations.show', $this->registration));

        $response->assertSuccessful();
        $registration = $response->viewData('registration');
        
        expect($registration->person)->not->toBeNull();
        expect($registration->registrationType)->not->toBeNull();
        expect($registration->relationLoaded('documents'))->toBeTrue();
        expect($registration->relationLoaded('approvedBy'))->toBeTrue();
    });

    it('loads person currentAcademicQualification relationship', function () {
        $academicQualification = \App\Models\AcademicQualification::create([
            'person_id' => $this->person->id,
            'institution_name' => 'Test University',
            'qualification_type' => 'Bachelor',
            'field_of_study' => 'Medicine',
            'completion_date' => now()->subYears(5),
        ]);
        $this->person->current_academic_qualification_id = $academicQualification->id;
        $this->person->save();
        $this->registration->refresh();

        $response = $this->get(route('admin.registrations.show', $this->registration));

        $response->assertSuccessful();
        $registration = $response->viewData('registration');
        
        // Person should have currentAcademicQualification loaded
        expect($registration->person->currentAcademicQualification)->not->toBeNull();
        expect($registration->person->currentAcademicQualification->id)->toBe($academicQualification->id);
    });

    it('loads person currentWorkExperience relationship', function () {
        $workExperience = \App\Models\WorkExperience::create([
            'person_id' => $this->person->id,
            'institution_name' => 'Test Hospital',
            'position' => 'Doctor',
            'is_current' => true,
        ]);
        $this->person->current_work_experience_id = $workExperience->id;
        $this->person->save();
        $this->registration->refresh();

        $response = $this->get(route('admin.registrations.show', $this->registration));

        $response->assertSuccessful();
        $registration = $response->viewData('registration');
        
        // Person should have currentWorkExperience loaded
        expect($registration->person->currentWorkExperience)->not->toBeNull();
        expect($registration->person->currentWorkExperience->id)->toBe($workExperience->id);
    });

    it('loads documents with documentType relationship', function () {
        // Use existing document type
        $docType = DocumentType::first();
        $document = Document::factory()->create([
            'registration_id' => $this->registration->id,
            'document_type_id' => $docType->id,
        ]);

        $response = $this->get(route('admin.registrations.show', $this->registration));

        $response->assertSuccessful();
        $registration = $response->viewData('registration');
        
        expect($registration->documents)->not->toBeEmpty();
        expect($registration->documents->first()->documentType)->not->toBeNull();
        expect($registration->documents->first()->documentType->id)->toBe($docType->id);
    });

    it('handles registration with no documents', function () {
        $response = $this->get(route('admin.registrations.show', $this->registration));

        $response->assertSuccessful();
        $registration = $response->viewData('registration');
        
        expect($registration->documents)->toBeEmpty();
    });

    it('displays approvedBy user when registration is approved', function () {
        $approver = User::factory()->create();
        $this->registration->update([
            'status' => RegistrationStatus::APPROVED,
            'approved_by' => $approver->id,
        ]);

        $response = $this->get(route('admin.registrations.show', $this->registration));

        $response->assertSuccessful();
        $registration = $response->viewData('registration');
        
        expect($registration->approvedBy)->not->toBeNull();
        expect($registration->approvedBy->id)->toBe($approver->id);
    });

    it('handles registration without approvedBy', function () {
        // Create a registration explicitly without approved_by
        $registrationWithoutApprover = Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'status' => RegistrationStatus::SUBMITTED,
            'approved_by' => null,
        ]);

        $response = $this->get(route('admin.registrations.show', $registrationWithoutApprover));

        $response->assertSuccessful();
        $registration = $response->viewData('registration');
        
        expect($registration->approvedBy)->toBeNull();
    });
});

describe('export', function () {
    it('exports registrations as Excel file', function () {
        Registration::factory()->count(3)->create([
            'registration_type_id' => $this->registrationType->id,
        ]);

        $response = $this->get(route('admin.registrations.export'));

        $response->assertSuccessful();
        expect($response->headers->get('content-type'))->toContain('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        expect($response->headers->get('content-disposition'))->toContain('inscricoes.xlsx');
    });

    it('exports filtered registrations by status', function () {
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'status' => RegistrationStatus::APPROVED,
        ]);
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'status' => RegistrationStatus::SUBMITTED,
        ]);

        $response = $this->get(route('admin.registrations.export', [
            'filter' => ['status' => 'approved'],
        ]));

        $response->assertSuccessful();
        expect($response->headers->get('content-type'))->toContain('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    });

    it('exports filtered registrations by search query - registration number', function () {
        $reg1 = Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'registration_number' => 'REG-2024-1234',
        ]);
        $reg2 = Registration::factory()->create([
            'person_id' => Person::factory()->create()->id,
            'registration_type_id' => $this->registrationType->id,
            'registration_number' => 'REG-2024-5678',
        ]);

        $response = $this->get(route('admin.registrations.export', [
            'filter' => ['search' => '1234'],
        ]));

        $response->assertSuccessful();
    });

    it('exports filtered registrations by search query - person name', function () {
        $person1 = Person::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $person2 = Person::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);

        Registration::factory()->create([
            'person_id' => $person1->id,
            'registration_type_id' => $this->registrationType->id,
        ]);
        Registration::factory()->create([
            'person_id' => $person2->id,
            'registration_type_id' => $this->registrationType->id,
        ]);

        $response = $this->get(route('admin.registrations.export', [
            'filter' => ['search' => 'John'],
        ]));

        $response->assertSuccessful();
    });

    it('exports filtered registrations by search query - email', function () {
        $person1 = Person::factory()->create(['email' => 'john@example.com']);
        $person2 = Person::factory()->create(['email' => 'jane@example.com']);

        Registration::factory()->create([
            'person_id' => $person1->id,
            'registration_type_id' => $this->registrationType->id,
        ]);
        Registration::factory()->create([
            'person_id' => $person2->id,
            'registration_type_id' => $this->registrationType->id,
        ]);

        $response = $this->get(route('admin.registrations.export', [
            'filter' => ['search' => 'john@example.com'],
        ]));

        $response->assertSuccessful();
    });

    it('exports filtered registrations by type category', function () {
        $effectiveType = RegistrationType::factory()->create([
            'category' => 'effective',
            'payment_type_code' => 'test_code',
        ]);
        $provisionalType = RegistrationType::factory()->create([
            'category' => 'provisional',
            'payment_type_code' => 'test_code',
        ]);

        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $effectiveType->id,
        ]);
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $provisionalType->id,
        ]);

        $response = $this->get(route('admin.registrations.export', [
            'filter' => ['type' => 'effective'],
        ]));

        $response->assertSuccessful();
    });

    it('exports filtered registrations by date_from', function () {
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'submission_date' => now()->subDays(5),
        ]);
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'submission_date' => now()->subDays(20),
        ]);

        $response = $this->get(route('admin.registrations.export', [
            'filter' => ['date_from' => now()->subDays(10)->toDateString()],
        ]));

        $response->assertSuccessful();
    });

    it('exports filtered registrations by date_to', function () {
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'submission_date' => now()->subDays(5),
        ]);
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'submission_date' => now()->subDays(20),
        ]);

        $response = $this->get(route('admin.registrations.export', [
            'filter' => ['date_to' => now()->subDays(10)->toDateString()],
        ]));

        $response->assertSuccessful();
    });

    it('exports filtered registrations by date range', function () {
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'submission_date' => now()->subDays(5),
        ]);
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'submission_date' => now()->subDays(15),
        ]);
        Registration::factory()->create([
            'person_id' => $this->person->id,
            'registration_type_id' => $this->registrationType->id,
            'submission_date' => now()->subDays(25),
        ]);

        $response = $this->get(route('admin.registrations.export', [
            'filter' => [
                'date_from' => now()->subDays(10)->toDateString(),
                'date_to' => now()->subDays(3)->toDateString(),
            ],
        ]));

        $response->assertSuccessful();
    });

    it('exports filtered registrations with multiple filters combined', function () {
        $effectiveType = RegistrationType::factory()->create([
            'category' => 'effective',
            'payment_type_code' => 'test_code',
        ]);

        $person = Person::factory()->create(['first_name' => 'John', 'email' => 'john@test.com']);
        Registration::factory()->create([
            'person_id' => $person->id,
            'registration_type_id' => $effectiveType->id,
            'status' => RegistrationStatus::APPROVED,
            'submission_date' => now()->subDays(5),
        ]);

        $response = $this->get(route('admin.registrations.export', [
            'filter' => [
                'search' => 'John',
                'status' => 'approved',
                'type' => 'effective',
                'date_from' => now()->subDays(10)->toDateString(),
                'date_to' => now()->toDateString(),
            ],
        ]));

        $response->assertSuccessful();
    });

    it('includes related data in export', function () {
        $person = Person::factory()->create();
        $registration = Registration::factory()->create([
            'person_id' => $person->id,
            'registration_type_id' => $this->registrationType->id,
        ]);

        // Create related documents and payments using existing document type
        $docType = DocumentType::first();
        Document::factory()->create([
            'registration_id' => $registration->id,
            'document_type_id' => $docType->id,
        ]);

        $paymentType = PaymentType::factory()->create();
        Payment::factory()->create([
            'payable_type' => \Modules\Registration\Models\Registration::class,
            'payable_id' => $registration->id,
            'person_id' => $person->id,
            'payment_type_id' => $paymentType->id,
        ]);

        $response = $this->get(route('admin.registrations.export'));

        $response->assertSuccessful();
    });

    it('exports empty result when no registrations match filters', function () {
        $response = $this->get(route('admin.registrations.export', [
            'filter' => [
                'status' => 'approved',
                'date_from' => now()->addDays(10)->toDateString(),
            ],
        ]));

        $response->assertSuccessful();
        expect($response->headers->get('content-type'))->toContain('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    });
});

describe('validatePayment', function () {
    it('validates payment successfully', function () {
        Notification::fake();
        Storage::fake('public');

        $payload = [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
            'amount' => 5000.00,
        ];

        $response = $this->post(route('admin.registrations.validate-payment', $this->registration), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('registrations', [
            'id' => $this->registration->id,
            'is_paid' => true,
        ]);

        $this->assertDatabaseHas('payments', [
            'payable_type' => \Modules\Registration\Models\Registration::class,
            'payable_id' => $this->registration->id,
            'reference_number' => 'REF-123456',
            'status' => PaymentStatus::COMPLETED->value,
        ]);
    });

    it('creates payment type if it does not exist', function () {
        $this->registrationType->update(['payment_type_code' => 'new_payment_type']);

        $payload = [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
            'amount' => 5000.00,
        ];

        $this->post(route('admin.registrations.validate-payment', $this->registration), $payload);

        $this->assertDatabaseHas('payment_types', [
            'code' => 'new_payment_type',
        ]);
    });

    it('creates payment method if it does not exist', function () {
        $payload = [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Novo Método de Pagamento',
            'reference_number' => 'REF-123456',
            'amount' => 5000.00,
        ];

        $this->post(route('admin.registrations.validate-payment', $this->registration), $payload);

        $this->assertDatabaseHas('payment_methods', [
            'name' => 'Novo Método de Pagamento',
        ]);
    });

    it('uses registration type fee as default amount when amount not provided', function () {
        $payload = [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
        ];

        $this->post(route('admin.registrations.validate-payment', $this->registration), $payload);

        $payment = Payment::where('payable_id', $this->registration->id)->first();
        expect((float) $payment->amount)->toBe(5000.0);
    });

    it('uploads payment proof file when provided', function () {
        Storage::fake('local');
        Notification::fake();

        $file = \Illuminate\Http\UploadedFile::fake()->create('proof.pdf', 100);

        $payload = [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
            'amount' => 5000.00,
            'proof' => $file,
        ];

        $this->post(route('admin.registrations.validate-payment', $this->registration), $payload);

        // The controller stores with path 'public/registrations/{id}/{file}' using local disk
        // Then it strips 'public/' from the path before saving to database
        $expectedPath = 'public/registrations/'.$this->registration->id.'/'.$file->hashName();
        Storage::disk('local')->assertExists($expectedPath);

        // Verify document was created - the controller looks up payment_proof by code
        $docType = \App\Models\DocumentType::where('code', 'payment_proof')->first();
        expect($docType)->not->toBeNull();
        
        $document = \App\Models\Document::where('registration_id', $this->registration->id)
            ->where('document_type_id', $docType->id)
            ->first();
        expect($document)->not->toBeNull();
        expect($document->status)->toBe(\App\Enums\DocumentStatus::VALIDATED);
        expect($document->file_path)->toContain('registrations/'.$this->registration->id.'/');
    });

    it('updates existing payment when payment already exists', function () {
        Notification::fake();

        $existingPayment = Payment::factory()->create([
            'payable_type' => \Modules\Registration\Models\Registration::class,
            'payable_id' => $this->registration->id,
            'person_id' => $this->person->id,
            'amount' => 1000.00,
            'status' => PaymentStatus::PENDING->value,
        ]);

        $payload = [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-UPDATED',
            'amount' => 5000.00,
        ];

        $this->post(route('admin.registrations.validate-payment', $this->registration), $payload);

        $existingPayment->refresh();
        expect($existingPayment->reference_number)->toBe('REF-UPDATED');
        expect($existingPayment->status)->toBe(PaymentStatus::COMPLETED);
    });

    it('validates required fields', function () {
        $response = $this->post(route('admin.registrations.validate-payment', $this->registration), []);

        $response->assertSessionHasErrors(['payment_date', 'payment_method', 'reference_number']);
    });

    it('validates payment_date is a valid date', function () {
        $response = $this->post(route('admin.registrations.validate-payment', $this->registration), [
            'payment_date' => 'invalid-date',
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
        ]);

        $response->assertSessionHasErrors(['payment_date']);
    });

    it('validates amount is numeric when provided', function () {
        $response = $this->post(route('admin.registrations.validate-payment', $this->registration), [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
            'amount' => 'not-a-number',
        ]);

        $response->assertSessionHasErrors(['amount']);
    });

    it('validates proof file size', function () {
        Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->create('proof.pdf', 11000); // 11MB

        $response = $this->post(route('admin.registrations.validate-payment', $this->registration), [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
            'proof' => $file,
        ]);

        $response->assertSessionHasErrors(['proof']);
    });

    it('adds history entry when payment is validated', function () {
        Notification::fake();

        $payload = [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
            'amount' => 5000.00,
        ];

        $this->post(route('admin.registrations.validate-payment', $this->registration), $payload);

        $this->registration->refresh();
        $history = json_decode($this->registration->workflow_history, true);
        expect($history)->not->toBeNull();
        expect(end($history)['action'])->toBe('payment_validated');
    });

    it('sends notification when payment is validated', function () {
        Notification::fake();

        $payload = [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
            'amount' => 5000.00,
        ];

        $this->post(route('admin.registrations.validate-payment', $this->registration), $payload);

        Notification::assertSentOnDemand(\App\Notifications\SimpleRegistrationNotification::class);
    });

    it('updates registration payment fields', function () {
        Notification::fake();

        $payload = [
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transferência Bancária',
            'reference_number' => 'REF-123456',
            'amount' => 5000.00,
        ];

        $this->post(route('admin.registrations.validate-payment', $this->registration), $payload);

        $this->registration->refresh();
        expect($this->registration->is_paid)->toBeTrue();
        expect(abs((float) $this->registration->payment_amount - 5000.0))->toBeLessThan(0.01);
        expect($this->registration->payment_date)->not->toBeNull();
    });
});

describe('validateRegistration', function () {
    it('validates registration successfully when payment is validated', function () {
        Notification::fake();
        $this->registration->update(['is_paid' => true]);

        $response = $this->post(route('admin.registrations.validate', $this->registration));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->registration->refresh();
        expect($this->registration->status)->toBe(RegistrationStatus::VALIDATED);
    });

    it('requires payment to be validated before registration validation', function () {
        $this->registration->update(['is_paid' => false]);

        $response = $this->post(route('admin.registrations.validate', $this->registration));

        $response->assertRedirect();
        $response->assertSessionHasErrors(['error']);
    });

    it('allows validation from submitted status', function () {
        Notification::fake();
        $this->registration->update([
            'status' => RegistrationStatus::SUBMITTED,
            'is_paid' => true,
        ]);

        $response = $this->post(route('admin.registrations.validate', $this->registration));

        $response->assertRedirect();
        $this->registration->refresh();
        expect($this->registration->status)->toBe(RegistrationStatus::VALIDATED);
    });

    it('allows validation from under_review status', function () {
        Notification::fake();
        $this->registration->update([
            'status' => RegistrationStatus::UNDER_REVIEW,
            'is_paid' => true,
        ]);

        $response = $this->post(route('admin.registrations.validate', $this->registration));

        $response->assertRedirect();
        $this->registration->refresh();
        expect($this->registration->status)->toBe(RegistrationStatus::VALIDATED);
    });

    it('allows validation from documents_pending status', function () {
        Notification::fake();
        $this->registration->update([
            'status' => RegistrationStatus::DOCUMENTS_PENDING,
            'is_paid' => true,
        ]);

        $response = $this->post(route('admin.registrations.validate', $this->registration));

        $response->assertRedirect();
        $this->registration->refresh();
        expect($this->registration->status)->toBe(RegistrationStatus::VALIDATED);
    });

    it('allows validation from payment_pending status', function () {
        Notification::fake();
        $this->registration->update([
            'status' => RegistrationStatus::PAYMENT_PENDING,
            'is_paid' => true,
        ]);

        $response = $this->post(route('admin.registrations.validate', $this->registration));

        $response->assertRedirect();
        $this->registration->refresh();
        expect($this->registration->status)->toBe(RegistrationStatus::VALIDATED);
    });

    it('prevents validation from invalid status', function () {
        $this->registration->update([
            'status' => RegistrationStatus::APPROVED,
            'is_paid' => true,
        ]);

        $response = $this->post(route('admin.registrations.validate', $this->registration));

        $response->assertRedirect();
        $response->assertSessionHasErrors(['error']);
    });

    it('adds history entry when registration is validated', function () {
        Notification::fake();
        $this->registration->update(['is_paid' => true]);

        $this->post(route('admin.registrations.validate', $this->registration));

        $this->registration->refresh();
        $history = json_decode($this->registration->workflow_history, true);
        expect($history)->not->toBeNull();
        expect(end($history)['action'])->toBe('registration_validated');
    });

    it('sends notification when registration is validated', function () {
        Notification::fake();
        $this->registration->update(['is_paid' => true]);

        $this->post(route('admin.registrations.validate', $this->registration));

        Notification::assertSentOnDemand(\App\Notifications\SimpleRegistrationNotification::class);
    });
});

