<?php

namespace Modules\Exam\Database\Factories;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamApplicationFactory extends Factory
{
    protected $model = ExamApplication::class;

    public function definition()
    {
        return [
            'exam_id' => Exam::factory(),
            'user_id' => User::factory(),
            'preferred_date' => $this->faker->date(),
            'terms_accepted' => $this->faker->boolean(),
            'is_confirmed' => $this->faker->boolean(),
            'is_present' => $this->faker->boolean(),
            'status' => $this->faker->randomElement(['draft', 'submitted', 'in_review', 'approved', 'rejected', 'documents_pending']),
        ];
    }
}
