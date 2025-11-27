<?php

namespace Modules\Document\Tests\Feature;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Member;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Illuminate\Foundation\Testing\WithFaker::class);

beforeEach(function () {
    // Create roles
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'member']);
    Role::firstOrCreate(['name' => 'teacher']);
    Role::firstOrCreate(['name' => 'evaluator']);

    // Create permissions for documents
    Permission::firstOrCreate(['name' => 'view documents']);
    Permission::firstOrCreate(['name' => 'manage documents']);

    // Create a user for authentication
    $this->user = User::factory()->create();

    // Assign admin role to the user
    $this->user->assignRole('admin');

    // Give document permissions to user
    $this->user->givePermissionTo(['view documents', 'manage documents']);

    // Create a person and member
    $person = Person::factory()->create();
    $this->member = Member::factory()->create(['person_id' => $person->id]);

    // Use existing document type from database
    $this->documentType = DocumentType::first();
});

test('documents index page can be rendered', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.documents.index'));

    $response->assertStatus(200);
    $response->assertViewIs('documents.index');
});

test('document create page can be rendered', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.documents.create'));

    $response->assertStatus(200);
    $response->assertViewIs('documents.create');
});

test('document can be created', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('document.pdf', 1000);

    $response = $this->actingAs($this->user)
        ->post(route('admin.documents.store'), [
            'person_id' => $this->member->person_id,
            'document_type_id' => $this->documentType->id,
            'document_file' => $file,
            'expiry_date' => now()->addYear()->format('Y-m-d'),
            'notes' => 'Test document notes',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('documents', [
        'member_id' => $this->member->id,
        'document_type_id' => $this->documentType->id,
        'original_filename' => 'document.pdf',
        'status' => 'pending',
        'notes' => 'Test document notes',
    ]);

    // Check that the file was stored
    $document = Document::where('member_id', $this->member->id)->first();
    Storage::disk('public')->assertExists($document->file_path);
});

test('document show page can be rendered', function () {
    // Create a document
    $document = Document::factory()->create([
        'member_id' => $this->member->id,
        'document_type_id' => $this->documentType->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.documents.show', $document));

    $response->assertStatus(200);
    $response->assertViewIs('documents.show');
    $response->assertViewHas('document');
});

test('document edit page can be rendered', function () {
    // Create a document
    $document = Document::factory()->create([
        'member_id' => $this->member->id,
        'document_type_id' => $this->documentType->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.documents.edit', $document));

    $response->assertStatus(200);
    $response->assertViewIs('documents.edit');
    $response->assertViewHas('document');
});

test('document can be updated', function () {
    Storage::fake('public');

    // Create a document
    $document = Document::factory()->create([
        'member_id' => $this->member->id,
        'document_type_id' => $this->documentType->id,
        'status' => 'pending',
        'notes' => 'Original notes',
    ]);

    $file = UploadedFile::fake()->create('updated-document.pdf', 1000);

    $response = $this->actingAs($this->user)
        ->put(route('admin.documents.update', $document), [
            'document_type_id' => $this->documentType->id,
            'document_file' => $file,
            'expiry_date' => now()->addYear()->format('Y-m-d'),
            'notes' => 'Updated notes',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'member_id' => $this->member->id,
        'document_type_id' => $this->documentType->id,
        'original_filename' => 'updated-document.pdf',
        'status' => 'pending',
        'notes' => 'Updated notes',
    ]);
});

test('document can be deleted', function () {
    Storage::fake('public');

    // Create a document with a fake file
    $file = UploadedFile::fake()->create('document.pdf', 1000);
    $path = $file->store('member-documents', 'public');

    $document = Document::factory()->create([
        'member_id' => $this->member->id,
        'document_type_id' => $this->documentType->id,
        'file_path' => $path,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->delete(route('admin.documents.destroy', $document));

    $response->assertRedirect();
    $this->assertSoftDeleted('documents', ['id' => $document->id]);
    Storage::disk('public')->assertMissing($path);
});

test('document validation form can be rendered', function () {
    // Create a document
    $document = Document::factory()->create([
        'member_id' => $this->member->id,
        'document_type_id' => $this->documentType->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.documents.validate', $document));

    $response->assertStatus(200);
    $response->assertViewIs('documents.validate');
    $response->assertViewHas('document');
});

test('document can be validated', function () {
    // Create a document
    $document = Document::factory()->create([
        'member_id' => $this->member->id,
        'document_type_id' => $this->documentType->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('admin.documents.validate', $document), [
            'status' => 'validated',
            'notes' => 'Validation notes',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'status' => 'validated',
        'notes' => 'Validation notes',
        'validated_by' => $this->user->id,
    ]);
});

test('document can be rejected', function () {
    // Create a document
    $document = Document::factory()->create([
        'member_id' => $this->member->id,
        'document_type_id' => $this->documentType->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('admin.documents.validate', $document), [
            'status' => 'rejected',
            'rejection_reason' => 'Document is invalid',
            'notes' => 'Rejection notes',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'status' => 'rejected',
        'rejection_reason' => 'Document is invalid',
        'notes' => 'Rejection notes',
        'validated_by' => $this->user->id,
    ]);
});

test('document checklist can be rendered', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.documents.checklist', $this->member));

    $response->assertStatus(200);
    $response->assertViewIs('documents.checklist');
    $response->assertViewHas('member');
    $response->assertViewHas('checklist');
});

test('document status check page can be rendered', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.documents.check-status'));

    $response->assertStatus(200);
    $response->assertViewIs('documents.status');
});
