<?php

namespace App\Data\Exam;

use Spatie\LaravelData\Data;

class ExamApplicationData extends Data
{
    public function __construct(
        public int $exam_id,
        public int $user_id,
        public string $exam_type,
        public string $specialty,
        public ?string $other_specialty,
        public ?string $preferred_date,
        public ?string $preferred_location,
        public ?string $cv_path,
        public ?string $payment_proof_path,
        public ?string $recommendation_letter_path,
        public ?string $additional_documents_path,
        public ?string $experience_summary,
        public ?string $experience_years,
        public ?string $current_institution,
        public ?string $special_needs,
        public ?string $observations,
        public bool $terms_accepted,
    ) {}

    public static function rules(): array
    {
        return [
            'exam_id' => ['required', 'exists:exams,id'],
            'user_id' => ['required', 'exists:users,id'],
            'exam_type' => ['required', 'string', 'in:certificacao,especialidade,revalidacao,recertificacao'],
            'specialty' => ['required', 'string', 'max:255'],
            'other_specialty' => ['nullable', 'string', 'max:255'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_location' => ['nullable', 'string', 'max:255'],
            'cv_path' => ['nullable', 'string'],
            'payment_proof_path' => ['nullable', 'string'],
            'recommendation_letter_path' => ['nullable', 'string'],
            'additional_documents_path' => ['nullable', 'string'],
            'experience_summary' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'string', 'in:menos_1,1_3,3_5,5_10,mais_10'],
            'current_institution' => ['nullable', 'string', 'max:255'],
            'special_needs' => ['nullable', 'string'],
            'observations' => ['nullable', 'string'],
            'terms_accepted' => ['required', 'boolean', 'accepted'],
        ];
    }
}
