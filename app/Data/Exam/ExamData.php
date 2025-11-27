<?php

namespace App\Data\Exam;

use Spatie\LaravelData\Data;

class ExamData extends Data
{
    public function __construct(
        public string $name,
        public string $type,
        public ?string $level,
        public string $specialty,
        public ?string $description,
        public string $exam_date,
        public string $start_time,
        public string $end_time,
        public string $location,
        public ?string $address,
        public int $capacity,
        public float $minimum_grade,
        public ?int $questions_count,
        public ?int $time_limit,
        public ?int $attempts_allowed,
        public bool $allow_consultation,
        public bool $is_mandatory,
        public bool $immediate_result,
        public ?int $primary_evaluator_id,
        public ?int $secondary_evaluator_id,
        public ?string $notes,
        public ?string $status,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:teorico,pratico,oral,misto'],
            'level' => ['nullable', 'string', 'in:basico,intermediario,avancado'],
            'specialty' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'exam_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'location' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'minimum_grade' => ['required', 'numeric', 'min:0', 'max:20'],
            'questions_count' => ['nullable', 'integer', 'min:1'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'attempts_allowed' => ['nullable', 'integer', 'min:1'],
            'allow_consultation' => ['boolean'],
            'is_mandatory' => ['boolean'],
            'immediate_result' => ['boolean'],
            'primary_evaluator_id' => ['nullable', 'exists:users,id'],
            'secondary_evaluator_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:draft,scheduled,in_progress,completed,cancelled'],
        ];
    }
}
