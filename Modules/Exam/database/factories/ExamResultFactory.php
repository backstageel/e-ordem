<?php

namespace Modules\Exam\Database\Factories;

use App\Models\ExamApplication;
use App\Models\ExamResult;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamResultFactory extends Factory
{
    protected $model = ExamResult::class;

    public function definition()
    {
        return [
            'exam_application_id' => ExamApplication::factory(),
            'grade' => $this->faker->randomFloat(1, 0, 20),
            'decision' => $this->faker->randomElement(['aprovado', 'reprovado']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
