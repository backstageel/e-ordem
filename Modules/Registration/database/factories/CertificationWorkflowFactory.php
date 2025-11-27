<?php

namespace Modules\Registration\Database\Factories;

use Modules\Registration\Models\CertificationWorkflow;
use Modules\Registration\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Registration\Models\CertificationWorkflow>
 */
class CertificationWorkflowFactory extends Factory
{
    protected $model = CertificationWorkflow::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'current_step' => $this->faker->numberBetween(1, 9),
            'assigned_to' => User::factory(),
            'started_at' => $this->faker->optional()->dateTime(),
            'completed_at' => $this->faker->optional()->dateTime(),
            'step_data' => $this->faker->optional()->randomElements(['data1', 'data2'], 2),
            'decisions' => $this->faker->optional()->randomElements(['approved', 'rejected', 'pending'], 2),
            'history' => $this->faker->optional()->randomElements(['entry1', 'entry2'], 2),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
