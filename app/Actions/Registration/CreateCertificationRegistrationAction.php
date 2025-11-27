<?php

namespace App\Actions\Registration;

use App\Enums\DocumentStatus;
use App\Enums\RegistrationStatus;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use Modules\Registration\Models\TemporaryRegistration;

class CreateCertificationRegistrationAction
{
    public function execute(
        int $category,
        array $contact,
        array $personal,
        array $identity,
        array $academic,
        array $uploads,
        ?string $email = null,
        ?string $phone = null
    ): Registration {
        return DB::transaction(function () use ($category, $contact, $personal, $identity, $academic, $uploads, $email, $phone) {
            // Get registration type
            $categoryData = $this->getCategoryData($category);
            $registrationType = RegistrationType::where('code', $categoryData['code'])->firstOrFail();

            // Create or update Person
            $person = Person::firstOrNew([
                'email' => $email ?? ($contact['email'] ?? null),
                'phone' => $phone ?? ($contact['phone'] ?? null),
            ]);

            // Fill personal data
            $person->fill(array_filter([
                'first_name' => $personal['first_name'] ?? null,
                'middle_name' => $personal['middle_name'] ?? null,
                'last_name' => $personal['last_name'] ?? null,
                'father_name' => $personal['father_name'] ?? null,
                'mother_name' => $personal['mother_name'] ?? null,
                'gender_id' => $personal['gender_id'] ?? null,
                'birth_date' => $personal['birth_date'] ?? null,
                'birth_country_id' => $personal['birth_country_id'] ?? null,
                'birth_province_id' => $personal['birth_province_id'] ?? null,
                'birth_district_id' => $personal['birth_district_id'] ?? null,
                'marital_status_id' => $personal['marital_status_id'] ?? null,
                'nationality_id' => $personal['nationality_id'] ?? null,
                'email' => $email ?? ($contact['email'] ?? null),
                'phone' => $phone ?? ($contact['phone'] ?? null),
            ], fn ($v) => $v !== null && $v !== ''));

            // Fill identity and address data
            $person->fill(array_filter([
                'identity_document_id' => $identity['identity_document_id'] ?? null,
                'identity_document_number' => $identity['identity_document_number'] ?? null,
                'identity_document_issue_date' => $identity['identity_document_issue_date'] ?? null,
                'identity_document_expiry_date' => $identity['identity_document_expiry_date'] ?? null,
                'living_address' => $identity['living_address'] ?? null,
                'living_country_id' => $identity['living_country_id'] ?? null,
                'living_province_id' => $identity['living_province_id'] ?? null,
                'living_district_id' => $identity['living_district_id'] ?? null,
                'neighborhood' => $identity['neighborhood'] ?? null,
            ], fn ($v) => $v !== null && $v !== ''));

            // Fill academic data
            $person->fill(array_filter([
                'degree_type' => $academic['degree_type'] ?? null,
                'university' => $academic['university'] ?? null,
                'university_start_year' => $academic['university_start_year'] ?? null,
                'university_end_year' => $academic['university_end_year'] ?? null,
                'university_country_id' => $academic['university_country_id'] ?? null,
                'university_city_district' => $academic['university_city_district'] ?? null,
                'university_final_grade' => $academic['university_final_grade'] ?? null,
                'high_school_institution' => $academic['high_school_institution'] ?? null,
                'high_school_country_id' => $academic['high_school_country_id'] ?? null,
                'high_school_city_district' => $academic['high_school_city_district'] ?? null,
                'high_school_completion_year' => $academic['high_school_completion_year'] ?? null,
                'high_school_final_grade' => $academic['high_school_final_grade'] ?? null,
            ], fn ($v) => $v !== null && $v !== ''));

            $person->save();

            // Generate process number
            $processNumber = $this->generateProcessNumber('certification', $category);

            // Create registration
            $registration = Registration::create([
                'registration_type_id' => $registrationType->id,
                'person_id' => $person->id,
                'type' => 'certification',
                'category' => $category,
                'process_number' => $processNumber,
                'registration_number' => $processNumber, // Same as process number for now
                'status' => RegistrationStatus::SUBMITTED->value,
                'submission_date' => now(),
            ]);

            // Move and create documents
            $this->processDocuments($registration, $person, $uploads, $registrationType);

            // Track missing documents
            $requiredDocs = $registrationType->required_documents ?? [];
            if (is_string($requiredDocs)) {
                $requiredDocs = json_decode($requiredDocs, true) ?: [];
            }
            $uploadedKeys = array_keys($uploads);
            $missing = array_values(array_diff($requiredDocs, $uploadedKeys));
            if (! empty($missing)) {
                $registration->additional_documents_required = $missing;
                $registration->documents_checked = false;
            } else {
                $registration->documents_checked = true;
            }
            $registration->save();

            // Clean up temporary registration
            if ($email || $phone) {
                TemporaryRegistration::where('email', $email)
                    ->orWhere('phone', $phone)
                    ->delete();
            }

            return $registration;
        });
    }

    protected function getCategoryData(int $category): array
    {
        $categories = [
            1 => [
                'code' => 'CERT-1',
                'name' => 'Pré-inscrição para Certificação - Categoria 1',
            ],
            2 => [
                'code' => 'CERT-2',
                'name' => 'Pré-inscrição para Certificação - Categoria 2',
            ],
            3 => [
                'code' => 'CERT-3',
                'name' => 'Pré-inscrição para Certificação - Categoria 3',
            ],
        ];

        return $categories[$category] ?? $categories[1];
    }

    protected function generateProcessNumber(string $type, int $category): string
    {
        $year = date('Y');
        $prefix = match ($type) {
            'certification' => 'CERT',
            'provisional' => 'PROV',
            'effective' => 'EFF',
            default => 'REG',
        };

        // Get the last registration number for this type and year
        $lastNumber = Registration::where('type', $type)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->value('process_number');

        if ($lastNumber) {
            // Extract number from last process number (e.g., CERT-2025-0001 -> 1)
            preg_match('/-(\d+)$/', $lastNumber, $matches);
            $nextNumber = isset($matches[1]) ? (int) $matches[1] + 1 : 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $year, $nextNumber);
    }

    protected function processDocuments(Registration $registration, Person $person, array $uploads, RegistrationType $registrationType): void
    {
        $documentTypeMap = [
            'bi_valido' => 'identity_document',
            'certificado_conclusao_curso' => 'diploma',
            'curriculum_vitae' => 'curriculum_vitae',
            'fotografias_tipo_passe' => 'passport_photos',
            'nuit' => 'nuit',
            'certificado_registo_criminal_mz' => 'criminal_record',
            'comprovativo_pagamento_exame' => 'payment_proof',
        ];

        foreach ($uploads as $key => $tempPath) {
            if (! $tempPath || ! Storage::disk('local')->exists($tempPath)) {
                continue;
            }

            // Move file from temp to permanent location
            $filename = basename($tempPath);
            $permanentPath = 'registrations/'.$registration->id.'/'.$filename;

            // Ensure directory exists
            $directory = dirname($permanentPath);
            if (! Storage::disk('local')->exists($directory)) {
                Storage::disk('local')->makeDirectory($directory);
            }

            // Move file
            Storage::disk('local')->move($tempPath, $permanentPath);

            // Get document type
            $docTypeCode = $documentTypeMap[$key] ?? 'other';
            $documentType = DocumentType::where('code', $docTypeCode)->first() ?? DocumentType::first();

            // Get file metadata
            $mimeType = Storage::disk('local')->mimeType($permanentPath) ?: 'application/octet-stream';
            $fileSize = Storage::disk('local')->size($permanentPath);

            // Create document record
            Document::create([
                'person_id' => $person->id,
                'registration_id' => $registration->id,
                'document_type_id' => $documentType?->id ?? 1, // Fallback to first document type
                'file_path' => $permanentPath,
                'original_filename' => $filename,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'status' => DocumentStatus::PENDING->value,
                'submission_date' => now(),
                'notes' => 'Uploaded key: '.$key,
            ]);
        }
    }
}
