<?php

namespace App\Actions\Registration;

use App\Data\Registration\RegistrationData;
use App\Enums\RegistrationStatus;
use App\Models\AcademicQualification;
use App\Models\Document;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Person;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use App\Models\WorkExperience;
use App\Notifications\NewRegistrationCreated;
use App\Notifications\RegistrationSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateRegistrationAction
{
    public function execute(RegistrationData $data): Registration
    {
        return DB::transaction(function () use ($data) {
            // Create or update Person using provided contact/personal/identity fields
            $person = Person::firstOrNew([
                'email' => $data->contact['email'] ?? null,
                'phone' => $data->contact['phone'] ?? null,
            ]);

            $person->fill([
                'civility' => $data->personal['civility'] ?? null,
                'first_name' => $data->personal['first_name'] ?? null,
                'middle_name' => $data->personal['middle_name'] ?? null,
                'last_name' => $data->personal['last_name'] ?? null,
                'father_name' => $data->personal['father_name'] ?? null,
                'mother_name' => $data->personal['mother_name'] ?? null,
                'gender_id' => $data->personal['gender_id'] ?? null,
                'birth_date' => $data->personal['birth_date'] ?? null,
                'birth_country_id' => $data->personal['birth_country_id'] ?? null,
                'birth_province_id' => $data->personal['birth_province_id'] ?? null,
                'birth_district_id' => $data->personal['birth_district_id'] ?? null,
                'marital_status_id' => $data->personal['marital_status_id'] ?? null,
                'nationality_id' => $data->personal['nationality_id'] ?? null,

                'identity_document_id' => $data->identity['identity_document_id'] ?? null,
                'identity_document_number' => $data->identity['identity_document_number'] ?? null,
                'identity_document_issue_place' => $data->identity['identity_document_issue_place'] ?? null,
                'identity_document_issue_date' => $data->identity['identity_document_issue_date'] ?? null,
                'identity_document_expiry_date' => $data->identity['identity_document_expiry_date'] ?? null,
                'living_address' => $data->identity['living_address'] ?? null,
                'living_country_id' => $data->identity['living_country_id'] ?? null,
                'living_province_id' => $data->identity['living_province_id'] ?? null,
                'living_district_id' => $data->identity['living_district_id'] ?? null,
                'living_neighborhood_id' => $data->identity['living_neighborhood_id'] ?? null,
            ]);
            $person->save();

            // Persist academic qualification (current snapshot)
            $academic = null;
            if (! empty($data->academic)) {
                $academic = AcademicQualification::create([
                    'person_id' => $person->id,
                    'institution_name' => $data->academic['university'] ?? null,
                    'qualification_type' => $data->academic['professional_category'] ?? null,
                    'field_of_study' => $data->academic['specialty'] ?? null,
                    'start_date' => null,
                    'completion_date' => $data->academic['graduation_date'] ?? null,
                    'is_verified' => false,
                    'notes' => null,
                ]);
                $person->current_academic_qualification_id = $academic->id;
                $person->save();
            }

            // Persist work experience (current snapshot)
            $work = null;
            if (! empty($data->academic) || ! empty($data->personal)) {
                $work = WorkExperience::create([
                    'person_id' => $person->id,
                    'institution_name' => $data->academic['current_institution'] ?? ($data->personal['workplace'] ?? null),
                    'position' => $data->academic['professional_category'] ?? null,
                    'start_date' => null,
                    'end_date' => null,
                    'is_current' => true,
                    'description' => null,
                ]);
                $person->current_work_experience_id = $work->id;
                $person->save();
            }

            $type = RegistrationType::findOrFail($data->registration_type_id);

            $registrationNumber = 'REG-'.date('Y').'-'.str_pad((string) (Registration::count() + 1), 4, '0', STR_PAD_LEFT);

            $registration = Registration::create([
                'person_id' => $person->id,
                'registration_type_id' => $type->id,
                'registration_number' => $registrationNumber,
                'status' => RegistrationStatus::SUBMITTED,
                'submission_date' => now(),
            ]);

            // Store uploaded documents mapped to required documents list
            $requiredKeys = [];
            if (isset($type->required_documents) && is_array($type->required_documents)) {
                $requiredKeys = $type->required_documents;
            } elseif (is_string($type->required_documents) && \Illuminate\Support\Str::startsWith($type->required_documents, '[')) {
                $requiredKeys = json_decode($type->required_documents, true) ?: [];
            }

            $uploadedKeys = [];

            foreach (($data->uploads ?? []) as $key => $tempPath) {
                if (! $tempPath) {
                    continue;
                }
                $uploadedKeys[] = (string) $key;
                $basename = basename($tempPath);
                $dest = 'registrations/'.$registration->id.'/'.$basename;
                if (Storage::disk('public')->exists($dest) === false) {
                    if (Storage::exists($tempPath)) {
                        // Move from default disk to public
                        Storage::move($tempPath, 'public/'.$dest);
                    } elseif (Storage::disk('public')->exists($tempPath)) {
                        // If already on public disk, copy into registration folder
                        Storage::disk('public')->copy($tempPath, $dest);
                    } else {
                        // As a last resort, try to read from default disk and write to public
                        if (Storage::exists($tempPath)) {
                            Storage::disk('public')->put($dest, Storage::get($tempPath));
                        }
                    }
                }

                // Determine document type by key, mapping to existing document types
                $documentTypeId = null;
                // Map upload keys to document type codes
                $codeMap = [
                    'identity_document' => 'identity_document',
                    'diploma' => 'diploma',
                    'curriculum_vitae' => 'curriculum_vitae',
                    'criminal_record' => 'criminal_record',
                    'nuit' => 'nuit',
                    'passport_photos' => 'passport_photos',
                    'specialty_certificate' => 'specialty_certificate',
                    'payment_proof' => 'payment_proof',
                ];
                $code = $codeMap[$key] ?? null;
                $foundType = $code ? \App\Models\DocumentType::where('code', $code)->first() : \App\Models\DocumentType::first();
                if (! $foundType) {
                    // Fallback to first available document type
                    $foundType = \App\Models\DocumentType::first();
                }
                $documentTypeId = $foundType?->id;

                // Gather file meta from public disk
                $mimeType = Storage::disk('public')->exists($dest) ? (Storage::disk('public')->mimeType($dest) ?: 'application/octet-stream') : 'application/octet-stream';
                $fileSize = Storage::disk('public')->exists($dest) ? (int) Storage::disk('public')->size($dest) : 0;

                Document::create([
                    'person_id' => $person->id,
                    'registration_id' => $registration->id,
                    'document_type_id' => $documentTypeId,
                    'file_path' => $dest,
                    'original_filename' => $basename,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                    'status' => 'pending',
                    'submission_date' => now(),
                    'notes' => 'uploaded_key: '.$key,
                ]);
            }

            // Track missing required documents on the Registration record
            if (! empty($requiredKeys)) {
                $missing = array_values(array_diff($requiredKeys, $uploadedKeys));
                if (! empty($missing)) {
                    $registration->additional_documents_required = $missing;
                    $registration->documents_checked = false;
                    $registration->save();
                } else {
                    $registration->documents_checked = true;
                    $registration->save();
                }
            }

            // Create initial pending payment based on RegistrationType payment_type_code
            if (! empty($type->payment_type_code)) {
                $paymentType = PaymentType::where('code', $type->payment_type_code)->first();
                Payment::create([
                    'payable_type' => \Modules\Registration\Models\Registration::class,
                    'payable_id' => $registration->id,
                    'person_id' => $person->id,
                    'payment_type_id' => $paymentType?->id,
                    'amount' => $type->fee ?? $paymentType?->default_amount ?? 0,
                    'status' => 'pending',
                    'due_date' => now()->addDays(10),
                    'reference_number' => 'PAY-'.Str::upper(Str::random(8)),
                ]);
            }

            // Notify candidate and admins
            try {
                if (! empty($person->email)) {
                    $person->notify(new RegistrationSubmitted($registration));
                }
                foreach (\App\Models\User::role('super-admin')->get() as $admin) {
                    $admin->notify(new NewRegistrationCreated($registration));
                }
            } catch (\Throwable $e) {
                // swallow notification errors
            }

            return $registration;
        });
    }
}
