<?php

namespace Modules\Exam\Database\Factories;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'exam_date' => $this->faker->date(),
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'allow_consultation' => $this->faker->boolean(),
            'is_mandatory' => $this->faker->boolean(),
            'immediate_result' => $this->faker->boolean(),
            'minimum_grade' => $this->faker->randomFloat(1, 0, 10),
            'primary_evaluator_id' => User::factory(),
            'secondary_evaluator_id' => User::factory(),
        ];
    }
}
