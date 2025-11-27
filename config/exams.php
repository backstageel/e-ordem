<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Exam Module Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration parameters for the Exam and Assessment module
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Exam Types
    |--------------------------------------------------------------------------
    */
    'types' => [
        'certification' => [
            'name' => 'Exame de Certificação para Pré-graduação',
            'description' => 'Exame de estado para licenciados em Medicina e Medicina Dentária',
            'mandatory_for' => [
                'mozambican_graduates_after_june_2016',
                'foreign_graduates_any_year',
            ],
            'exemptions' => [
                'philanthropic_missions_under_90_days',
            ],
        ],
        'specialty' => [
            'name' => 'Exame de Especialidade',
            'description' => 'Exame para certificação em especialidades médicas',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exam Fees (in MZN)
    |--------------------------------------------------------------------------
    */
    'fees' => [
        'mozambican_trained_mozambican' => 500,
        'foreign_trained_mozambican' => 500,
        'foreign_trained_mozambican_institution' => 500,
        'foreign_trained_foreign_institution' => 5000,
        'recovery_fee' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Exam Periods
    |--------------------------------------------------------------------------
    */
    'periods' => [
        'ordinary' => [
            'months' => ['march', 'november'],
            'advance_publication_days' => 15,
        ],
        'extraordinary' => [
            'minimum_candidates' => 100,
            'advance_publication_days' => 15,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exam Format
    |--------------------------------------------------------------------------
    */
    'format' => [
        'max_duration_hours' => 4,
        'total_questions_range' => [
            'min' => 150,
            'max' => 200,
        ],
        'question_types' => [
            'multiple_choice' => [
                'alternatives' => 5,
                'only_one_correct' => true,
            ],
            'true_false' => [
                'statements_range' => [
                    'min' => 8,
                    'max' => 12,
                ],
            ],
            'correlations' => true,
            'clinical_cases' => [
                'questions_range' => [
                    'min' => 3,
                    'max' => 5,
                ],
                'may_include_development' => true,
            ],
        ],
        'total_score' => 20,
        'separate_exams' => [
            'medicine',
            'dentistry',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exam Content Areas
    |--------------------------------------------------------------------------
    */
    'content_areas' => [
        'portuguese_language',
        'mozambique_general_culture',
        'numeracy',
        'public_health_mozambique',
        'biosafety',
        'pharmacology_therapeutics',
        'semiology',
        'diagnostic_complementary_exams',
        'radiology',
        'clinical_practice',
        'laboratory',
        'general_medical_specialties_knowledge',
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Requirements
    |--------------------------------------------------------------------------
    */
    'application_requirements' => [
        'exam_request' => true,
        'authenticated_diploma_copy' => true,
        'mozambican_id_copy' => true,
        'residence_authorization_or_passport' => true, // For foreigners
        'payment_proof' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Rules
    |--------------------------------------------------------------------------
    */
    'payment' => [
        'required_days_before_exam' => 15,
        'non_refundable' => true,
        'payment_methods' => [
            'cash',
            'pos',
            'bank_transfer',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Appeals
    |--------------------------------------------------------------------------
    */
    'appeals' => [
        'deadline_business_days' => 10,
        'response_deadline_business_days' => 10,
        'final_decision_unappealable' => true,
        'submission_methods' => [
            'email',
            'physical',
            'online',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'confirm_appointment' => true,
        'send_results' => true,
        'send_reminders' => true,
        'reminder_days_before' => [7, 1],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Storage
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'documents_path' => 'exam_documents',
        'results_path' => 'exam_results',
        'certificates_path' => 'exam_certificates',
        'disk' => 'local', // Private storage for sensitive documents
    ],

    /*
    |--------------------------------------------------------------------------
    | Eligibility Rules
    |--------------------------------------------------------------------------
    */
    'eligibility' => [
        'requires_license' => true,
        'requires_mozambican_nationality_or_residence' => true,
        'requires_no_professional_card' => true,
        'mandatory_start_date' => '2016-06-01',
    ],

    /*
    |--------------------------------------------------------------------------
    | Result Invalidation Rules
    |--------------------------------------------------------------------------
    */
    'invalidation' => [
        'fraud_detection' => true,
        'fraudulent_license_communication' => true,
        'auto_cancel_registration' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Exam Instructions
    |--------------------------------------------------------------------------
    */
    'instructions' => [
        'arrival_time_minutes_before' => 30,
        'id_required' => true,
        'electronic_devices_prohibited' => true,
        'attendance_sheet_required' => true,
    ],
];
