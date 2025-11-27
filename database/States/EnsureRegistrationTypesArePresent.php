<?php

namespace Database\States;

use App\Enums\RegistrationCategory;
use App\Enums\WorkflowStep;
use Illuminate\Support\Facades\DB;

class EnsureRegistrationTypesArePresent
{
    public function __invoke()
    {
        // Check if required tables exist before proceeding
        if (!$this->tablesExist()) {
            return;
        }

        if ($this->present()) {
            return;
        }

        $registrationTypes = [
            // Provisional Registrations (Article 4)
            [
                'name' => 'Formação Médica Especializada (Formador)',
                'code' => 'provisional_formation',
                'description' => 'Inscrição provisória para formadores em residência médica especializada',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 1.00,
                'payment_type_code' => 'provisional_authorization_other',
                'validity_period_days' => 730, // 24 months
                'renewable' => true,
                'max_renewals' => 1,
                'required_documents' => json_encode([
                    'identity_document',
                    'diploma',
                    'professional_license',
                    'criminal_record',
                    'curriculum_vitae',
                    'invitation_letter',
                    'supervisor_indication',
                    'supervisor_declaration',
                    'supervisor_ormm_card',
                    'specialty_certificate_validated',
                    'ethics_course_certificate',
                    'good_standing_declaration',
                    'experience_10_years_proof',
                    'teaching_5_years_proof',
                    'recommendation_letter',
                    'curriculum_program',
                    'accreditation_certificate',
                    'portuguese_proficiency',
                    'conformity_declaration',
                    'competence_verification',
                ]),
                'eligibility_criteria' => json_encode([
                    'min_experience_years' => 10,
                    'min_teaching_years' => 5,
                    'international_certification_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Formação Médica de Curta Duração',
                'code' => 'provisional_short_term',
                'description' => 'Inscrição provisória para formação médica de curta duração',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 10000.00,
                'payment_type_code' => 'provisional_authorization_3m',
                'validity_period_days' => 90, // 3 months
                'renewable' => true,
                'max_renewals' => 1,
                'required_documents' => json_encode([
                    'identity_document',
                    'specialty_certificate',
                    'curriculum_vitae',
                    'invitation_letter',
                    'supervisor_indication',
                    'supervisor_declaration',
                    'supervisor_ormm_card',
                    'ethics_course_certificate',
                ]),
                'eligibility_criteria' => json_encode([
                    'specialty_certification_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Formação Médica Especializada (Formando)',
                'code' => 'provisional_trainee',
                'description' => 'Inscrição provisória para formandos em residência médica especializada',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 1.00,
                'payment_type_code' => 'provisional_authorization_other',
                'validity_period_days' => 730, // 24 months
                'renewable' => true,
                'max_renewals' => 1,
                'required_documents' => json_encode([
                    'identity_document',
                    'diploma',
                    'employer_reference',
                    'health_ministry_letter',
                    'cnrm_acceptance',
                    'reciprocity_declaration',
                    'criminal_record',
                    'curriculum_vitae',
                    'supervisor_indication',
                    'supervisor_declaration',
                    'supervisor_ormm_card',
                    'ethics_course_certificate',
                    'curriculum_program',
                    'accreditation_certificate',
                    'education_ministry_recognition',
                    'portuguese_proficiency',
                    'conformity_declaration',
                ]),
                'eligibility_criteria' => json_encode([
                    'cnrm_acceptance_required' => true,
                    'reciprocity_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Investigação Científica',
                'code' => 'provisional_research',
                'description' => 'Inscrição provisória para investigação científica',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 20000.00,
                'payment_type_code' => 'provisional_authorization_6m',
                'validity_period_days' => 180, // 6 months
                'renewable' => true,
                'max_renewals' => 1,
                'required_documents' => json_encode([
                    'identity_document',
                    'invitation_letter',
                    'supervisor_declaration',
                    'supervisor_ormm_card',
                    'ethics_exam_certificate',
                    'ethics_approval',
                    'research_protocol',
                    'publication_evidence',
                    'criminal_record',
                    'curriculum_vitae',
                    'supervisor_curriculum',
                    'institution_recommendation',
                    'conformity_declaration',
                ]),
                'eligibility_criteria' => json_encode([
                    'ethics_approval_required' => true,
                    'min_publications' => 2,
                    'supervisor_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Missões Assistenciais Humanitárias',
                'code' => 'provisional_humanitarian',
                'description' => 'Inscrição provisória para missões assistenciais humanitárias',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 1.00,
                'payment_type_code' => 'provisional_authorization_other',
                'validity_period_days' => 365, // 12 months
                'renewable' => false,
                'max_renewals' => 0,
                'required_documents' => json_encode([
                    'identity_document',
                    'invitation_letter',
                    'supervisor_indication',
                    'supervisor_declaration',
                    'supervisor_ormm_card',
                    'verification_certificate',
                    'criminal_record',
                    'curriculum_vitae',
                    'recommendation_letters',
                    'curriculum_program',
                    'accreditation_certificate',
                    'competence_verification',
                    'liability_insurance',
                    'work_visa',
                    'reciprocity_declaration',
                    'portuguese_proficiency',
                    'conformity_declaration',
                ]),
                'eligibility_criteria' => json_encode([
                    'liability_insurance_required' => true,
                    'work_visa_required' => true,
                    'competence_verification_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cooperação Intergovernamental',
                'code' => 'provisional_cooperation',
                'description' => 'Inscrição provisória para cooperação intergovernamental',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 1.00,
                'payment_type_code' => 'provisional_authorization_other',
                'validity_period_days' => 730, // 24 months
                'renewable' => true,
                'max_renewals' => 1,
                'required_documents' => json_encode([
                    'identity_document',
                    'diploma',
                    'criminal_record',
                    'curriculum_vitae',
                    'invitation_letter',
                    'supervisor_indication',
                    'supervisor_declaration',
                    'supervisor_ormm_card',
                    'specialty_certificate_validated',
                    'ethics_course_certificate',
                    'recommendation_letters',
                    'curriculum_program',
                    'accreditation_certificate',
                    'competence_verification',
                    'portuguese_proficiency',
                    'conformity_declaration',
                ]),
                'eligibility_criteria' => json_encode([
                    'government_cooperation_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Assistência Setor Privado',
                'code' => 'provisional_private',
                'description' => 'Inscrição provisória para assistência no setor privado',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 1.00,
                'payment_type_code' => 'provisional_authorization_other',
                'validity_period_days' => 365, // 12 months
                'renewable' => false,
                'max_renewals' => 0,
                'required_documents' => json_encode([
                    'identity_document',
                    'diploma',
                    'nuit',
                    'criminal_record',
                    'health_ministry_authorization',
                    'work_visa',
                    'employment_contract_promise',
                    'reciprocity_declaration',
                    'liability_insurance',
                ]),
                'eligibility_criteria' => json_encode([
                    'nuit_required' => true,
                    'health_ministry_authorization_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Exercício Setor Público (Clínico Geral)',
                'code' => 'provisional_public_general',
                'description' => 'Inscrição provisória para exercício no setor público como clínico geral',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 1.00,
                'payment_type_code' => 'provisional_authorization_other',
                'validity_period_days' => 548, // 18 months
                'renewable' => false,
                'max_renewals' => 0,
                'required_documents' => json_encode([
                    'identity_document',
                    'diploma',
                    'nuit',
                    'criminal_record',
                    'curriculum_vitae',
                    'health_ministry_authorization',
                ]),
                'eligibility_criteria' => json_encode([
                    'graduated_in_mozambique' => true,
                    'health_ministry_authorization_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Exercício Setor Público (Especialista)',
                'code' => 'provisional_public_specialist',
                'description' => 'Inscrição provisória para exercício no setor público como especialista',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 1.00,
                'payment_type_code' => 'provisional_authorization_other',
                'validity_period_days' => 548, // 18 months
                'renewable' => false,
                'max_renewals' => 0,
                'required_documents' => json_encode([
                    'identity_document',
                    'diploma',
                    'nuit',
                    'criminal_record',
                    'curriculum_vitae',
                    'health_ministry_authorization',
                ]),
                'eligibility_criteria' => json_encode([
                    'graduated_in_mozambique' => true,
                    'specialist_required' => true,
                    'health_ministry_authorization_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intercâmbios com Médicos Nacionais',
                'code' => 'provisional_exchange',
                'description' => 'Inscrição provisória para intercâmbios com médicos nacionais',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'fee' => 10000.00,
                'payment_type_code' => 'provisional_authorization_3m',
                'validity_period_days' => 90, // 3 months
                'renewable' => true,
                'max_renewals' => 1,
                'required_documents' => json_encode([
                    'identity_document',
                    'diploma',
                    'criminal_record',
                    'curriculum_vitae',
                ]),
                'eligibility_criteria' => json_encode([
                    'observational_only' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getProvisionalSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Effective Registrations (Article 9)
            [
                'name' => 'Clínica Geral Nacional',
                'code' => 'effective_general',
                'description' => 'Inscrição efetiva para médicos de clínica geral nacionais',
                'category' => RegistrationCategory::EFFECTIVE->value,
                'fee' => 5000.00,
                'payment_type_code' => 'enrollment_fee',
                'validity_period_days' => null, // Permanent
                'renewable' => false,
                'max_renewals' => 0,
                'required_documents' => json_encode([
                    'identity_document',
                    'diploma',
                    'nuit',
                    'criminal_record',
                    'curriculum_vitae',
                    'good_standing_declaration',
                ]),
                'eligibility_criteria' => json_encode([
                    'national_required' => true,
                    'general_practice_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getEffectiveSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Especialista Nacional',
                'code' => 'effective_specialist',
                'description' => 'Inscrição efetiva para médicos especialistas nacionais',
                'category' => RegistrationCategory::EFFECTIVE->value,
                'fee' => 3000.00,
                'payment_type_code' => 'enrollment_fee',
                'validity_period_days' => null, // Permanent
                'renewable' => false,
                'max_renewals' => 0,
                'required_documents' => json_encode([
                    'identity_document',
                    'diploma',
                    'nuit',
                    'specialty_certificate',
                    'criminal_record',
                    'curriculum_vitae',
                    'good_standing_declaration',
                ]),
                'eligibility_criteria' => json_encode([
                    'national_required' => true,
                    'specialist_required' => true,
                ]),
                'workflow_steps' => json_encode(WorkflowStep::getEffectiveSteps()),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('registration_types')->insert($registrationTypes);

    }

    private function tablesExist(): bool
    {
        try {
            // Check if required tables exist
            if (!DB::getSchemaBuilder()->hasTable('registration_types')) {
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            // If there's any error checking tables, assume they don't exist yet
            return false;
        }
    }

    private function present()
    {
        return DB::table('registration_types')->count() > 0;
    }
}
