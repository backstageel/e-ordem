<?php

use App\Actions\Registration\CreateCertificationRegistrationAction;
use App\Enums\RegistrationStatus;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');

    $this->action = app(CreateCertificationRegistrationAction::class);

    $this->registrationType = RegistrationType::factory()->create([
        'code' => 'CERT-1',
        'category_number' => 1,
        'required_documents' => ['bi_valido', 'certificado_conclusao_curso'],
    ]);

    DocumentType::factory()->create(['code' => 'identity_document']);
    DocumentType::factory()->create(['code' => 'diploma']);
});

it('creates certification registration with person', function () {
    $contact = [
        'email' => 'test@example.com',
        'phone' => '+258849902058',
    ];

    $personal = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'gender_id' => 1,
        'birth_date' => '1990-01-01',
        'nationality_id' => 1,
    ];

    $identity = [
        'identity_document_number' => '123456789',
        'identity_document_issue_date' => '2020-01-01',
        'identity_document_expiry_date' => '2030-01-01',
    ];

    $academic = [
        'degree_type' => 'Medicina Geral',
        'university' => 'Universidade Test',
    ];

    $uploads = [];

    $registration = $this->action->execute(1, $contact, $personal, $identity, $academic, $uploads);

    expect($registration)->toBeInstanceOf(Registration::class);
    expect($registration->type)->toBe('certification');
    expect($registration->category)->toBe(1);
    expect($registration->status)->toBe(RegistrationStatus::SUBMITTED);
    expect($registration->person)->toBeInstanceOf(Person::class);
    expect($registration->person->email)->toBe('test@example.com');
});

it('processes uploaded documents', function () {
    $tempPath = 'temp/test-document.pdf';
    Storage::disk('local')->put($tempPath, 'test content');

    $uploads = [
        'bi_valido' => $tempPath,
    ];

    $registration = $this->action->execute(
        1,
        ['email' => 'test@example.com'],
        ['first_name' => 'John'],
        ['identity_document_number' => '123'],
        [],
        $uploads
    );

    expect(Document::where('registration_id', $registration->id)->count())->toBe(1);
    expect(Storage::disk('local')->exists("registrations/{$registration->id}/test-document.pdf"))->toBeTrue();
});

it('tracks missing documents', function () {
    $uploads = ['bi_valido' => 'temp/test.pdf'];

    $registration = $this->action->execute(
        1,
        ['email' => 'test@example.com'],
        ['first_name' => 'John'],
        ['identity_document_number' => '123'],
        [],
        $uploads
    );

    expect($registration->additional_documents_required)->toContain('certificado_conclusao_curso');
    expect($registration->documents_checked)->toBeFalse();
});

it('marks documents as checked when all uploaded', function () {
    Storage::disk('local')->put('temp/bi.pdf', 'content');
    Storage::disk('local')->put('temp/cert.pdf', 'content');

    $uploads = [
        'bi_valido' => 'temp/bi.pdf',
        'certificado_conclusao_curso' => 'temp/cert.pdf',
    ];

    $registration = $this->action->execute(
        1,
        ['email' => 'test@example.com'],
        ['first_name' => 'John'],
        ['identity_document_number' => '123'],
        [],
        $uploads
    );

    expect($registration->documents_checked)->toBeTrue();
    expect($registration->additional_documents_required)->toBeEmpty();
});

it('generates unique process number', function () {
    $registration1 = $this->action->execute(
        1,
        ['email' => 'test1@example.com'],
        ['first_name' => 'John'],
        ['identity_document_number' => '123'],
        [],
        []
    );

    $registration2 = $this->action->execute(
        1,
        ['email' => 'test2@example.com'],
        ['first_name' => 'Jane'],
        ['identity_document_number' => '456'],
        [],
        []
    );

    expect($registration1->process_number)->not->toBe($registration2->process_number);
    expect($registration1->process_number)->toStartWith('CERT-');
});
