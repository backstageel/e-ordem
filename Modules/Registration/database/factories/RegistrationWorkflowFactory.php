<?php

namespace Modules\Registration\Database\Factories;

use App\Enums\WorkflowStatus;
use App\Enums\WorkflowStep;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationWorkflow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Registration\Models\RegistrationWorkflow>
 */
class RegistrationWorkflowFactory extends Factory
{
    protected $model = RegistrationWorkflow::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'current_step' => $this->faker->randomElement(WorkflowStep::cases())->value,
            'status' => $this->faker->randomElement(WorkflowStatus::cases())->value,
            'assigned_to' => User::factory(),
            'started_at' => $this->faker->optional()->dateTime(),
            'completed_at' => $this->faker->optional()->dateTime(),
            'notes' => $this->faker->optional()->sentence(),
            'decisions' => $this->faker->optional()->randomElements(['approved', 'rejected', 'pending'], 2),
            'workflow_data' => $this->faker->optional()->randomElements(['data1', 'data2', 'data3'], 2),
        ];
    }
}
