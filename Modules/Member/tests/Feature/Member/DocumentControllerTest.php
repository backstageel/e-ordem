<?php

namespace Modules\Member\Tests\Feature\Member;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Member;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'member']);
    Role::firstOrCreate(['name' => 'admin']);

    $this->user = User::factory()->create();
    $this->user->assignRole('member');

    $this->person = Person::factory()->create(['user_id' => $this->user->id]);
    $this->member = Member::factory()->create(['person_id' => $this->person->id]);

    // Refresh to load relationships
    $this->user->refresh();
    $this->person->refresh();
    $this->member->refresh();

    $this->documentType = DocumentType::factory()->create(['is_active' => true]);
});

describe('index', function () {
    it('displays member documents list', function () {
        Document::factory()->count(3)->create([
            'person_id' => $this->person->id,
            'member_id' => $this->member->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.index'));

        $response->assertSuccessful()
            ->assertViewIs('member.documents.index')
            ->assertViewHas('documents')
            ->assertViewHas('member');
    });

    it('filters documents by status', function () {
        Document::factory()->create([
            'person_id' => $this->person->id,
            'status' => DocumentStatus::PENDING,
        ]);
        Document::factory()->create([
            'person_id' => $this->person->id,
            'status' => DocumentStatus::VALIDATED,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.index', ['status' => DocumentStatus::PENDING->value]));

        $response->assertSuccessful();
    });

    it('only shows documents belonging to authenticated member', function () {
        $otherPerson = Person::factory()->create();
        Document::factory()->create(['person_id' => $this->person->id]);
        Document::factory()->create(['person_id' => $otherPerson->id]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.index'));

        $response->assertSuccessful();
        $documents = $response->viewData('documents');
        expect($documents->every(fn ($doc) => $doc->person_id === $this->person->id))->toBeTrue();
    });
});

describe('pending', function () {
    it('displays pending documents checklist', function () {
        // Ensure relationships are loaded
        $this->user->load('person.member');

        // Verify relationships exist
        expect($this->user->person)->not->toBeNull()
            ->and($this->user->person->member)->not->toBeNull();

        DocumentType::factory()->create(['is_required' => true, 'is_active' => true]);
        DocumentType::factory()->create(['is_required' => true, 'is_active' => true]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.pending'));

        $response->assertSuccessful()
            ->assertViewIs('member.documents.pending')
            ->assertViewHas('pendingDocuments')
            ->assertViewHas('member');
    });

    it('shows missing required documents', function () {
        // Ensure relationships are loaded
        $this->user->load('person.member');

        // Verify relationships exist
        expect($this->user->person)->not->toBeNull()
            ->and($this->user->person->member)->not->toBeNull();

        $requiredType = DocumentType::factory()->create(['is_required' => true, 'is_active' => true]);
        DocumentType::factory()->create(['is_required' => false, 'is_active' => true]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.pending'));

        $response->assertSuccessful();
        $pendingDocs = $response->viewData('pendingDocuments');
        expect($pendingDocs)->not->toBeEmpty();
    });
});

describe('create', function () {
    it('displays document creation form', function () {
        $response = $this->actingAs($this->user)
            ->get(route('member.documents.create'));

        $response->assertSuccessful()
            ->assertViewIs('member.documents.create')
            ->assertViewHas('documentTypes')
            ->assertViewHas('member');
    });
});

describe('store', function () {
    it('creates a new document', function () {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($this->user)
            ->post(route('member.documents.store'), [
                'document_type_id' => $this->documentType->id,
                'document_file' => $file,
                'expiry_date' => now()->addYear()->format('Y-m-d'),
                'notes' => 'Test notes',
            ]);

        $response->assertRedirect(route('member.documents.index'));
        $this->assertDatabaseHas('documents', [
            'person_id' => $this->person->id,
            'member_id' => $this->member->id,
            'document_type_id' => $this->documentType->id,
            'status' => DocumentStatus::PENDING->value,
            'notes' => 'Test notes',
        ]);

        $document = Document::latest()->first();
        Storage::disk('public')->assertExists($document->file_path);
    });

    it('creates document with translation file when required', function () {
        Storage::fake('public');

        $docType = DocumentType::factory()->create(['requires_translation' => true]);
        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');
        $translationFile = UploadedFile::fake()->create('translation.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($this->user)
            ->post(route('member.documents.store'), [
                'document_type_id' => $docType->id,
                'document_file' => $file,
                'translation_file' => $translationFile,
            ]);

        $response->assertRedirect();
        $document = Document::latest()->first();
        expect($document->has_translation)->toBeTrue()
            ->and($document->translation_file_path)->not->toBeNull();
    });

    it('validates required fields', function () {
        $response = $this->actingAs($this->user)
            ->post(route('member.documents.store'), []);

        $response->assertSessionHasErrors(['document_type_id', 'document_file']);
    });

    it('validates file size limit', function () {
        $file = UploadedFile::fake()->create('document.pdf', 11 * 1024 * 1024);

        $response = $this->actingAs($this->user)
            ->post(route('member.documents.store'), [
                'document_type_id' => $this->documentType->id,
                'document_file' => $file,
            ]);

        $response->assertSessionHasErrors(['document_file']);
    });
});

describe('show', function () {
    it('displays document details', function () {
        $document = Document::factory()->create([
            'person_id' => $this->person->id,
            'member_id' => $this->member->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.show', $document));

        $response->assertSuccessful()
            ->assertViewIs('member.documents.show')
            ->assertViewHas('document');
    });

    it('prevents viewing documents belonging to other members', function () {
        $otherPerson = Person::factory()->create();
        $otherDocument = Document::factory()->create(['person_id' => $otherPerson->id]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.show', $otherDocument));

        $response->assertForbidden();
    });
});

describe('download', function () {
    it('downloads document file', function () {
        Storage::fake('public');
        $path = 'member-documents/document.pdf';
        Storage::disk('public')->put($path, 'PDF content');

        $document = Document::factory()->create([
            'person_id' => $this->person->id,
            'member_id' => $this->member->id,
            'file_path' => $path,
            'original_filename' => 'document.pdf',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.download', $document));

        $response->assertDownload('document.pdf');
    });

    it('prevents downloading documents belonging to other members', function () {
        $otherPerson = Person::factory()->create();
        $otherDocument = Document::factory()->create([
            'person_id' => $otherPerson->id,
            'file_path' => 'member-documents/other.pdf',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.download', $otherDocument));

        $response->assertForbidden();
    });

    it('returns error when file does not exist', function () {
        $document = Document::factory()->create([
            'person_id' => $this->person->id,
            'member_id' => $this->member->id,
            'file_path' => 'non-existent/document.pdf',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.download', $document));

        $response->assertRedirect()
            ->assertSessionHasErrors('error');
    });
});

describe('downloadTranslation', function () {
    it('downloads translation file', function () {
        Storage::fake('public');
        $path = 'member-documents/translations/translation.pdf';
        Storage::disk('public')->put($path, 'Translation content');

        $document = Document::factory()->create([
            'person_id' => $this->person->id,
            'member_id' => $this->member->id,
            'translation_file_path' => $path,
            'has_translation' => true,
            'original_filename' => 'document.pdf',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.download-translation', $document));

        $response->assertDownload('translation_document.pdf');
    });

    it('prevents downloading translation from other members documents', function () {
        $otherPerson = Person::factory()->create();
        $otherDocument = Document::factory()->create([
            'person_id' => $otherPerson->id,
            'has_translation' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.download-translation', $otherDocument));

        $response->assertForbidden();
    });

    it('returns error when translation file does not exist', function () {
        $document = Document::factory()->create([
            'person_id' => $this->person->id,
            'member_id' => $this->member->id,
            'translation_file_path' => null,
            'has_translation' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('member.documents.download-translation', $document));

        $response->assertRedirect()
            ->assertSessionHasErrors('error');
    });
});
