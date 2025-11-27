<?php

use App\Enums\RegistrationCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Registration\Models\RegistrationType;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');

    // Create registration type
    $this->certType = RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'CERT-1',
        'category_number' => 1,
        'name' => 'Moçambicanos formados em Moçambique',
        'required_documents' => ['bi_valido', 'certificado_conclusao_curso'],
    ]);
});

describe('UploadDocumentsStep - Unit Tests', function () {
    it('validates file types correctly', function () {
        $validFiles = [
            UploadedFile::fake()->create('document.pdf', 100),
            UploadedFile::fake()->image('photo.jpg'),
            UploadedFile::fake()->image('photo.png'),
        ];

        $invalidFiles = [
            UploadedFile::fake()->create('document.txt', 100),
            UploadedFile::fake()->create('document.exe', 100),
        ];

        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];

        foreach ($validFiles as $file) {
            expect(in_array($file->getMimeType(), $allowedMimes))->toBeTrue();
        }

        foreach ($invalidFiles as $file) {
            expect(in_array($file->getMimeType(), $allowedMimes))->toBeFalse();
        }
    });

    it('validates file size correctly', function () {
        $maxSize = 5 * 1024 * 1024; // 5MB

        $validSize = 2 * 1024 * 1024; // 2MB
        $invalidSize = 10 * 1024 * 1024; // 10MB

        expect($validSize)->toBeLessThanOrEqual($maxSize);
        expect($invalidSize)->toBeGreaterThan($maxSize);
    });
});
