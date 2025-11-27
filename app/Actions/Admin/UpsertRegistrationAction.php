<?php

namespace App\Actions\Admin;

use App\Data\Registration\RegistrationData;
use App\Enums\RegistrationStatus;
use App\Models\AcademicQualification;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Payment;
use App\Models\Person;
use Modules\Registration\Models\Registration;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpsertRegistrationAction
{
    public function execute(RegistrationData $data, ?Registration $existing = null): Registration
    {
        return DB::transaction(function () use ($data, $existing) {
            // Person upsert
            $contactEmail = $data->contact['email'] ?? null;
            $contactPhone = $data->contact['phone'] ?? null;

            $person = $existing?->person ?: new Person;
            $personPayload = array_filter([
                'email' => $contactEmail,
                'phone' => $contactPhone,
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
            ], static fn ($v) => $v !== null && $v !== '');
            if (! empty($personPayload)) {
                $person->fill($personPayload);
            }
            $person->save();

            // Academic & work (current)
            $aq = $existing?->person?->currentAcademicQualification ?: new AcademicQualification;
            $aq->fill([
                'university' => $data->academic['university'] ?? null,
                'graduation_date' => $data->academic['graduation_date'] ?? null,
                'country_id' => $data->academic['country_of_formation_id'] ?? null,
                'degree' => $data->academic['academic_degree'] ?? null,
                'completion_date' => $data->academic['completion_date'] ?? null,
            ]);
            $aq->save();

            $we = $existing?->person?->currentWorkExperience ?: new WorkExperience;
            $we->fill([
                'institution' => $data->academic['current_institution'] ?? null,
                'position' => $data->academic['professional_category'] ?? null,
                'start_date' => $data->academic['start_date'] ?? null,
                'end_date' => $data->academic['end_date'] ?? null,
                'years_of_experience' => $data->academic['years_of_experience'] ?? null,
            ]);
            $we->save();

            $person->current_academic_qualification_id = $aq->id;
            $person->current_work_experience_id = $we->id;
            $person->save();

            // Registration upsert
            $registration = $existing ?: new Registration;
            $registrationBase = [
                'person_id' => $person->id,
                'status' => RegistrationStatus::SUBMITTED->value,
                'submission_date' => now(),
            ];
            // Only set registration_type_id on create or when provided explicitly
            if (! $existing && $data->registration_type_id) {
                $registrationBase['registration_type_id'] = $data->registration_type_id;
            }
            $registration->fill($registrationBase);
            if (! $registration->registration_number) {
                $registration->registration_number = 'REG-'.now()->year.'-'.str_pad((string) (Registration::max('id') + 1), 4, '0', STR_PAD_LEFT);
            }
            $registration->documents_validated = true; // internal: auto-validate
            $registration->save();

            // Documents: replace only the provided ones
            foreach (($data->uploads ?? []) as $key => $tempPath) {
                // Only replace when an actual new temp file was uploaded
                if (! $tempPath || $tempPath === '__existing__') {
                    continue;
                }
                if (! \Storage::exists($tempPath)) {
                    continue;
                }

                $ext = pathinfo($tempPath, PATHINFO_EXTENSION) ?: 'bin';
                $filename = Str::slug($key).'_'.uniqid().'.'.$ext;
                $finalPath = 'public/registrations/'.$registration->id.'/'.$filename;
                \Storage::move($tempPath, $finalPath);

                // Map upload keys to document type codes
                $codeMap = [
                    'identity_document' => 'identity_document',
                    'diploma' => 'diploma',
                    'curriculum_vitae' => 'curriculum_vitae',
                    'criminal_record' => 'criminal_record',
                    'nuit' => 'nuit',
                    'passport_photos' => 'passport_photos',
                    'specialty_certificate' => 'specialty_certificate',
                ];
                $code = $codeMap[$key] ?? null;
                $docType = $code ? DocumentType::where('code', $code)->first() : DocumentType::first();

                $mime = null;
                $size = null;
                try {
                    $mime = \Storage::mimeType($finalPath);
                } catch (\Throwable) {
                    $mime = null;
                }
                try {
                    $size = \Storage::size($finalPath);
                } catch (\Throwable) {
                    $size = null;
                }

                Document::updateOrCreate([
                    'registration_id' => $registration->id,
                    'document_type_id' => $docType->id,
                ], [
                    'person_id' => $person->id,
                    'path' => $finalPath,
                    'mime_type' => $mime,
                    'file_size' => $size,
                    'validated_at' => now(),
                ]);
            }

            // Payment: if proof uploaded under key 'payment_proof', mark as paid
            $paymentProof = $data->uploads['payment_proof'] ?? null;
            if ($paymentProof && $paymentProof !== '__existing__' && \Storage::exists($paymentProof)) {
                $payment = Payment::firstOrCreate([
                    'registration_id' => $registration->id,
                ], [
                    'amount' => optional($registration->registrationType)->fee,
                    'reference_number' => 'INT-'.now()->timestamp,
                ]);
                $payment->paid_at = now();
                $payment->status = 'paid';
                $payment->save();

                $registration->is_paid = true;
                $registration->payment_date = now();
                $registration->payment_amount = $payment->amount;
                $registration->save();

                // Attach proof as document
                $ext = pathinfo($paymentProof, PATHINFO_EXTENSION) ?: 'bin';
                $filename = 'payment_proof_'.uniqid().'.'.$ext;
                $finalPath = 'public/registrations/'.$registration->id.'/'.$filename;
                \Storage::move($paymentProof, $finalPath);

                $docType = DocumentType::where('code', 'payment_proof')->first();
                $mime = null;
                $size = null;
                try {
                    $mime = \Storage::mimeType($finalPath);
                } catch (\Throwable) {
                    $mime = null;
                }
                try {
                    $size = \Storage::size($finalPath);
                } catch (\Throwable) {
                    $size = null;
                }

                Document::updateOrCreate([
                    'registration_id' => $registration->id,
                    'document_type_id' => $docType->id,
                ], [
                    'person_id' => $person->id,
                    'path' => $finalPath,
                    'mime_type' => $mime,
                    'file_size' => $size,
                    'validated_at' => now(),
                ]);
            }

            return $registration->fresh(['person', 'registrationType']);
        });
    }
}
