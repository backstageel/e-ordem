<?php

namespace Modules\Document\Database\Seeders;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Member;
use App\Models\Person;
use Modules\Registration\Models\Registration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Document types are now managed by EnsureDocumentTypesArePresent State
        // No need to create them here

        // Get all document types
        $documentTypeIds = DocumentType::pluck('id')->toArray();

        if (empty($documentTypeIds)) {
            $this->command->warn('No document types found. Please run ensure-database-state-is-loaded first.');

            return;
        }

        // Get all people (required)
        $people = Person::with('member', 'registrations')->get();

        if ($people->isEmpty()) {
            $this->command->warn('No people found. Skipping document seeding.');

            return;
        }

        // Get all members (optional, for linking)
        $members = Member::all();

        // Get all registrations (optional, for linking)
        $registrations = Registration::all();

        // Common MIME types for documents
        $mimeTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        // Document statuses
        $statuses = [
            \App\Enums\DocumentStatus::PENDING->value,
            \App\Enums\DocumentStatus::UNDER_REVIEW->value,
            \App\Enums\DocumentStatus::REQUIRES_CORRECTION->value,
            \App\Enums\DocumentStatus::VALIDATED->value,
            \App\Enums\DocumentStatus::EXPIRED->value,
            \App\Enums\DocumentStatus::REJECTED->value,
        ];

        // Create 30 documents
        for ($i = 0; $i < 30; $i++) {
            // Select a random person (REQUIRED)
            $person = $people->random();

            // Optionally link to member if person has one
            $memberId = $person->member ? $person->member->id : null;

            // Optionally link to registration if person has one
            $personRegistrations = $registrations->where('person_id', $person->id);
            $registrationId = $personRegistrations->isNotEmpty() ? $personRegistrations->random()->id : null;

            // Select a random document type
            $documentTypeId = $documentTypeIds[array_rand($documentTypeIds)];

            // Generate a random file path
            $filePath = 'documents/'.Str::random(40).'.pdf';

            // Select a random status
            $status = $statuses[array_rand($statuses)];

            // Create submission date (between 1 month ago and today)
            $submissionDate = now()->subDays(rand(0, 30));

                // Create validation date (if status requires validation date)
                $finalStatuses = [
                    \App\Enums\DocumentStatus::VALIDATED->value,
                    \App\Enums\DocumentStatus::EXPIRED->value,
                    \App\Enums\DocumentStatus::REJECTED->value,
                ];
                $reviewStatuses = [
                    \App\Enums\DocumentStatus::UNDER_REVIEW->value,
                    \App\Enums\DocumentStatus::REQUIRES_CORRECTION->value,
                ];
                $validationDate = in_array($status, $finalStatuses) ? $submissionDate->copy()->addDays(rand(1, 5)) : null;

                // Create expiry date (if applicable, between 1 and 5 years from now)
                // For expired status, set expiry date in the past
                if ($status === \App\Enums\DocumentStatus::EXPIRED->value) {
                    $expiryDate = now()->subDays(rand(1, 365));
                } elseif ($status === \App\Enums\DocumentStatus::VALIDATED->value) {
                    $expiryDate = rand(0, 1) ? now()->addYears(rand(1, 5)) : null;
                } else {
                    $expiryDate = rand(0, 1) ? now()->addYears(rand(1, 5)) : null;
                }

                // Create rejection reason (if status is rejected or requires correction)
                $rejectionReason = in_array($status, [
                    \App\Enums\DocumentStatus::REJECTED->value,
                    \App\Enums\DocumentStatus::REQUIRES_CORRECTION->value,
                ]) ? 'Documento ilegível ou incompleto.' : null;

                // Create notes
                $notes = rand(0, 1) ? 'Observações sobre o documento.' : null;

                // Create validated by (if status is final or in review)
                $validatedBy = in_array($status, array_merge($finalStatuses, $reviewStatuses)) ? rand(1, 5) : null;

            // Create has_translation and translation_file_path
            $hasTranslation = rand(0, 1);
            $translationFilePath = $hasTranslation ? 'documents/translations/'.Str::random(40).'.pdf' : null;

            // Create the document
            Document::create([
                'person_id' => $person->id, // REQUIRED
                'member_id' => $memberId, // Optional: only if person has member
                'registration_id' => $registrationId, // Optional: only if person has registration
                'document_type_id' => $documentTypeId,
                'file_path' => $filePath,
                'original_filename' => 'documento_'.($i + 1).'.pdf',
                'mime_type' => $mimeTypes[array_rand($mimeTypes)],
                'file_size' => rand(100000, 5000000), // Between 100KB and 5MB
                'status' => $status,
                'submission_date' => $submissionDate,
                'validation_date' => $validationDate,
                'expiry_date' => $expiryDate,
                'rejection_reason' => $rejectionReason,
                'notes' => $notes,
                'validated_by' => $validatedBy,
                'has_translation' => $hasTranslation,
                'translation_file_path' => $translationFilePath,
            ]);
        }
    }
}
