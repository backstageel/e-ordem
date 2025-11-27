<?php

namespace Modules\Residency\Database\Factories;

use App\Models\ResidencyApplication;
use App\Models\ResidencyEvaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResidencyEvaluation>
 */
class ResidencyEvaluationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResidencyEvaluation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $periods = [
            '1st Semester',
            '2nd Semester',
            '1st Year',
            '2nd Year',
            '3rd Year',
            '4th Year',
            'Final Evaluation',
        ];

        $grades = ['A', 'B', 'C', 'D', 'F', 'Satisfactory', 'Unsatisfactory'];

        return [
            'residency_application_id' => ResidencyApplication::factory(),
            'evaluator_id' => User::factory(),
            'evaluation_date' => $this->faker->date(),
            'period' => $this->faker->randomElement($periods),
            'score' => $this->faker->randomFloat(1, 0, 20),
            'grade' => $this->faker->randomElement($grades),
            'comments' => $this->faker->optional()->paragraph(),
            'recommendations' => $this->faker->optional()->paragraph(),
            'is_satisfactory' => $this->faker->boolean(80), // 80% chance of being satisfactory
        ];
    }

    /**
     * Indicate that the evaluation is satisfactory.
     */
    public function satisfactory(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_satisfactory' => true,
        ]);
    }

    /**
     * Indicate that the evaluation is unsatisfactory.
     */
    public function unsatisfactory(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_satisfactory' => false,
        ]);
    }
}
