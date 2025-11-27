<?php

namespace Modules\Registration\Database\Seeders;

use App\Enums\RegistrationCategory;
use App\Enums\RegistrationSubtype;
use Illuminate\Database\Seeder;
use Modules\Registration\Models\RegistrationType;

class RegistrationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PRÉ-INSCRIÇÃO PARA CERTIFICAÇÃO (3 categorias)
        $this->createCertificationTypes();

        // INSCRIÇÕES PROVISÓRIAS (12 subtipos)
        $this->createProvisionalTypes();

        // INSCRIÇÕES EFETIVAS (3 subtipos)
        $this->createEffectiveTypes();
    }

    /**
     * Create certification registration types (3 categories).
     */
    protected function createCertificationTypes(): void
    {
        // CATEGORIA 1: Moçambicanos Formados em Moçambique
        RegistrationType::updateOrCreate(
            ['code' => 'CERT-1'],
            [
                'name' => 'Pré-inscrição para Certificação - Categoria 1',
                'code' => 'CERT-1',
                'category' => RegistrationCategory::PROVISIONAL->value, // Usa provisional temporariamente
                'category_number' => 1, // CRITICAL: Set category_number
                'payment_type_code' => 'certification_category_1',
                'description' => 'Moçambicanos formados em Moçambique',
                'fee' => 1000.00, // Taxa inscrição exame
                'validity_period_days' => null,
                'renewable' => false,
                'max_renewals' => 0,
                'required_documents' => [
                    'bi_valido',
                    'certificado_conclusao_curso',
                    'curriculum_vitae',
                    'fotografias_tipo_passe',
                    'nuit',
                    'certificado_registo_criminal_mz',
                    'comprovativo_pagamento_exame',
                ],
                'eligibility_criteria' => [
                    'nationality' => 'moçambicano',
                    'training_country' => 'moçambique',
                ],
                'workflow_steps' => [1, 2, 3, 4, 5, 6, 7, 8, 9], // 9 etapas
                'is_active' => true,
            ]
        );

        // CATEGORIA 2: Moçambicanos Formados no Estrangeiro
        RegistrationType::updateOrCreate(
            ['code' => 'CERT-2'],
            [
                'name' => 'Pré-inscrição para Certificação - Categoria 2',
                'code' => 'CERT-2',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'category_number' => 2, // CRITICAL: Set category_number
                'payment_type_code' => 'certification_category_2',
                'description' => 'Moçambicanos formados no estrangeiro',
                'fee' => 3500.00, // 2500 tramitação + 1000 exame
                'validity_period_days' => null,
                'renewable' => false,
                'max_renewals' => 0,
                'required_documents' => [
                    'bi_valido',
                    'certificado_conclusao_curso',
                    'certificado_equivalencia_mec',
                    'programa_curricular_detalhado',
                    'comprovativo_acreditacao_medical_council',
                    'carta_reconhecimento_ministerio_ensino_superior',
                    'curriculum_vitae',
                    'fotografias_tipo_passe',
                    'nuit',
                    'certificado_registo_criminal_mz',
                    'certificado_registo_criminal_pais_estudo',
                    'comprovativo_pagamento_tramitacao',
                    'comprovativo_pagamento_exame',
                ],
                'eligibility_criteria' => [
                    'nationality' => 'moçambicano',
                    'training_country' => 'estrangeiro',
                ],
                'workflow_steps' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
                'is_active' => true,
            ]
        );

        // CATEGORIA 3: Estrangeiros Formados em Moçambique
        RegistrationType::updateOrCreate(
            ['code' => 'CERT-3'],
            [
                'name' => 'Pré-inscrição para Certificação - Categoria 3',
                'code' => 'CERT-3',
                'category' => RegistrationCategory::PROVISIONAL->value,
                'category_number' => 3, // CRITICAL: Set category_number
                'payment_type_code' => 'certification_category_3',
                'description' => 'Estrangeiros formados em Moçambique',
                'fee' => 1000.00, // Taxa inscrição exame
                'validity_period_days' => null,
                'renewable' => false,
                'max_renewals' => 0,
                'required_documents' => [
                    'documento_identificacao_valido',
                    'certificado_conclusao_curso',
                    'curriculum_vitae',
                    'fotografias_tipo_passe',
                    'nuit',
                    'certificado_registo_criminal_mz',
                    'certificado_registo_criminal_pais_origem',
                    'carta_autorizacao_ministerio_saude_pais_origem',
                    'comprovativo_pagamento_exame',
                ],
                'eligibility_criteria' => [
                    'nationality' => 'estrangeiro',
                    'training_country' => 'moçambique',
                ],
                'workflow_steps' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
                'is_active' => true,
            ]
        );
    }

    /**
     * Create provisional registration types (12 subtypes).
     */
    protected function createProvisionalTypes(): void
    {
        $subtypes = RegistrationSubtype::cases();

        foreach ($subtypes as $subtype) {
            RegistrationType::updateOrCreate(
                ['code' => "PROV-{$subtype->value}"],
                [
                    'name' => "Inscrição Provisória - {$subtype->label()}",
                    'code' => "PROV-{$subtype->value}",
                    'category' => RegistrationCategory::PROVISIONAL->value,
                    'subtype_number' => $subtype->value, // CRITICAL: Set subtype_number
                    'payment_type_code' => "provisional_subtype_{$subtype->value}",
                    'description' => $subtype->label(),
                    'fee' => $this->calculateProvisionalFee($subtype),
                    'validity_period_days' => $subtype->durationDays(),
                    'renewable' => $subtype->isRenewable(),
                    'max_renewals' => $subtype->maxRenewals(),
                    'required_documents' => $this->getProvisionalDocuments($subtype),
                    'eligibility_criteria' => [
                        'nationality' => 'estrangeiro',
                        'subtype' => $subtype->value,
                    ],
                    'workflow_steps' => [1, 2, 3, 4, 5, 6, 7], // 7 estados
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Create effective registration types (3 subtypes).
     */
    protected function createEffectiveTypes(): void
    {
        // Grau A: Especialistas
        RegistrationType::updateOrCreate(
            ['code' => 'EFET-A'],
            [
                'name' => 'Inscrição Efetiva - Grau A (Especialistas)',
                'code' => 'EFET-A',
                'category' => RegistrationCategory::EFFECTIVE->value,
                'grade' => 'A', // CRITICAL: Set grade
                'payment_type_code' => 'effective_grade_a',
                'description' => 'Médicos especialistas moçambicanos',
                'fee' => 7300.00, // 3000 jóia + 4000 quota + 300 cartão
                'validity_period_days' => null, // Permanente
                'renewable' => true,
                'max_renewals' => 0, // 0 = ilimitado (renovação anual)
                'required_documents' => [
                    'bi_valido',
                    'certificado_conclusao_curso',
                    'certificado_especialidade',
                    'curriculum_vitae',
                    'fotografias_tipo_passe',
                    'nuit',
                    'certificado_registo_criminal_mz',
                    'comprovativo_aprovacao_exame',
                    'comprovativo_pagamento_joia_quota_cartao',
                ],
                'eligibility_criteria' => [
                    'nationality' => 'moçambicano',
                    'exam_approved' => true,
                    'grade' => 'A',
                ],
                'workflow_steps' => [1, 2, 3, 4, 5, 6, 7], // Workflow simplificado
                'is_active' => true,
            ]
        );

        // Grau B: Clínica Geral
        RegistrationType::updateOrCreate(
            ['code' => 'EFET-B'],
            [
                'name' => 'Inscrição Efetiva - Grau B (Clínica Geral)',
                'code' => 'EFET-B',
                'category' => RegistrationCategory::EFFECTIVE->value,
                'grade' => 'B', // CRITICAL: Set grade
                'payment_type_code' => 'effective_grade_b',
                'description' => 'Médicos de clínica geral moçambicanos',
                'fee' => 7300.00,
                'validity_period_days' => null,
                'renewable' => true,
                'max_renewals' => 0,
                'required_documents' => [
                    'bi_valido',
                    'certificado_conclusao_curso',
                    'curriculum_vitae',
                    'fotografias_tipo_passe',
                    'nuit',
                    'certificado_registo_criminal_mz',
                    'comprovativo_aprovacao_exame',
                    'comprovativo_pagamento_joia_quota_cartao',
                ],
                'eligibility_criteria' => [
                    'nationality' => 'moçambicano',
                    'exam_approved' => true,
                    'grade' => 'B',
                ],
                'workflow_steps' => [1, 2, 3, 4, 5, 6, 7],
                'is_active' => true,
            ]
        );

        // Grau C: Dentistas
        RegistrationType::updateOrCreate(
            ['code' => 'EFET-C'],
            [
                'name' => 'Inscrição Efetiva - Grau C (Dentistas)',
                'code' => 'EFET-C',
                'category' => RegistrationCategory::EFFECTIVE->value,
                'grade' => 'C', // CRITICAL: Set grade
                'payment_type_code' => 'effective_grade_c',
                'description' => 'Médicos dentistas moçambicanos',
                'fee' => 7300.00,
                'validity_period_days' => null,
                'renewable' => true,
                'max_renewals' => 0,
                'required_documents' => [
                    'bi_valido',
                    'certificado_conclusao_curso',
                    'curriculum_vitae',
                    'fotografias_tipo_passe',
                    'nuit',
                    'certificado_registo_criminal_mz',
                    'comprovativo_aprovacao_exame',
                    'comprovativo_pagamento_joia_quota_cartao',
                ],
                'eligibility_criteria' => [
                    'nationality' => 'moçambicano',
                    'exam_approved' => true,
                    'grade' => 'C',
                ],
                'workflow_steps' => [1, 2, 3, 4, 5, 6, 7],
                'is_active' => true,
            ]
        );
    }

    /**
     * Calculate fee for provisional subtype.
     */
    protected function calculateProvisionalFee(RegistrationSubtype $subtype): float
    {
        return match ($subtype) {
            RegistrationSubtype::SUBTYPE_1, RegistrationSubtype::SUBTYPE_2 => 9800.00, // 2500 + exame + 7300
            RegistrationSubtype::SUBTYPE_3, RegistrationSubtype::SUBTYPE_4, RegistrationSubtype::SUBTYPE_5,
            RegistrationSubtype::SUBTYPE_7, RegistrationSubtype::SUBTYPE_12 => 10000.00, // Autorização 0-3m + crachá
            RegistrationSubtype::SUBTYPE_6 => 29600.00, // 2500 + 20000 + 7300
            RegistrationSubtype::SUBTYPE_8 => 22500.00, // 2500 + 20000
            RegistrationSubtype::SUBTYPE_9 => 29800.00, // 2500 + 20000 + exame + 7300
            RegistrationSubtype::SUBTYPE_10, RegistrationSubtype::SUBTYPE_11 => 8300.00, // 1000 + 7300
            default => 0.00,
        };
    }

    /**
     * Get required documents for provisional subtype.
     */
    protected function getProvisionalDocuments(RegistrationSubtype $subtype): array
    {
        $common = [
            'formulario_pedido',
            'documento_identificacao_valido',
            'fotografias_tipo_passe',
            'carta_convite',
            'supervisor_indicacao',
            'supervisor_declaracao',
            'supervisor_cartao',
            'diploma_licenciatura',
            'certificado_etica',
            'certificado_idoneidade',
            'cartao_profissional',
            'comprovativo_tramitacao',
            'comprovativo_inscricao',
        ];

        // Se subtipo 4, não tem documentos comuns
        if ($subtype->isExemptFromCommonRequirements()) {
            return [
                'certificado_especialidade',
                'curriculum_vitae',
                'carta_convite_presidente_colegio',
                'termo_responsabilidade_supervisor',
                'comprovativo_pagamento_inscricao',
                'comprovativo_pagamento_cracha',
            ];
        }

        // Documentos específicos por subtipo (simplificado - detalhes completos no seeder final)
        $specific = match ($subtype) {
            RegistrationSubtype::SUBTYPE_1 => [
                'comprovativo_exercicio_10_anos',
                'comprovativo_docencia_5_anos',
                'certificado_especialidade_validado',
                'certificado_registo_criminal_pais_origem',
                'curriculum_vitae_detalhado',
                'carta_recomendacao_instituicao',
                'programa_curricular_especialidade',
                'comprovativo_acreditacao_instituicao',
                'comprovativo_proficiencia_portugues',
                'declaracao_conformidade_curricular',
                'comprovativo_inexistencia_mocambicanos',
                'comprovativo_pagamento_exame',
                'comprovativo_pagamento_joia_quota_cartao',
            ],
            RegistrationSubtype::SUBTYPE_2 => [
                'certificado_licenciatura_validado',
                'carta_referencia_instituicao_empregadora',
                'carta_ministerio_saude_pais_origem',
                'carta_aceitacao_comissao_residencias',
                'declaracao_reciprocidade_orgao_regulador',
                'certificado_registo_criminal_pais_origem',
                'curriculum_vitae',
                'programa_curricular_licenciatura',
                'comprovativo_acreditacao_instituicao',
                'carta_reconhecimento_programa_ministerio',
                'comprovativo_proficiencia_portugues',
                'declaracao_conformidade_curricular',
                'comprovativo_pagamento_exame',
                'comprovativo_pagamento_joia_quota_cartao',
            ],
            // ... outros subtipos (simplificado por espaço)
            default => [],
        };

        return [
            'common' => $common,
            'specific' => [
                "subtype_{$subtype->value}" => $specific,
            ],
        ];
    }
}
