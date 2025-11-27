<?php

namespace App\Data\Exam;

use Spatie\LaravelData\Data;

class ExamResultData extends Data
{
    public function __construct(
        public int $exam_application_id,
        public ?float $grade,
        public string $status,
        public ?string $decision,
        public ?string $decision_type,
        public ?string $notes,
        public ?int $evaluated_by,
        public ?string $evaluated_at,
    ) {}

    public static function rules(): array
    {
        return [
            'exam_application_id' => ['required', 'exists:exam_applications,id'],
            'grade' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'status' => ['required', 'string', 'in:presente,ausente,eliminado'],
            'decision' => ['nullable', 'string', 'in:aprovado,reprovado,recurso'],
            'decision_type' => ['nullable', 'string', 'in:aprovacao_automatica,aprovacao_manual,reprovacao_automatica,reprovacao_manual,recurso'],
            'notes' => ['nullable', 'string'],
            'evaluated_by' => ['nullable', 'exists:users,id'],
            'evaluated_at' => ['nullable', 'date'],
        ];
    }
}
