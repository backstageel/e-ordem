<?php

namespace Modules\Document\Tests\Feature\Documents;

use App\Exports\DocumentsExport;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Person;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->person = Person::factory()->create();
    $this->documentType = DocumentType::factory()->create();
});

it('exports documents to excel', function () {
    Excel::fake();

    $documents = Document::factory()->count(3)->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
    ]);

    $export = new DocumentsExport($documents);
    $filename = 'documentos_test.xlsx';
    Excel::download($export, $filename);

    Excel::assertDownloaded($filename);
});

it('exports documents with correct headings', function () {
    $documents = Document::factory()->count(2)->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
    ]);

    $export = new DocumentsExport($documents);

    $headings = $export->headings();

    expect($headings)->toContain('ID');
    expect($headings)->toContain('Tipo de Documento');
    expect($headings)->toContain('Status');
});

it('maps documents correctly in export', function () {
    $document = Document::factory()->create([
        'person_id' => $this->person->id,
        'document_type_id' => $this->documentType->id,
    ]);

    $export = new DocumentsExport(collect([$document]));
    $mapped = $export->map($document);

    expect($mapped[0])->toBe($document->id);
    expect($mapped[1])->toBe($document->documentType->name);
});
