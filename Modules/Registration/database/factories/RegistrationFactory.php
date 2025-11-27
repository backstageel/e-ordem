<?php

namespace Modules\Registration\Database\Factories;

use App\Enums\RegistrationPriority;
use App\Enums\RegistrationStatus;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition()
    {
        return [
            'person_id' => \App\Models\Person::factory(),
            'registration_type_id' => RegistrationType::inRandomOrder()->first()?->id ?? 1,
            'registration_number' => $this->faker->unique()->numerify('REG#####'),
            'status' => $this->faker->randomElement(RegistrationStatus::cases())->value,
            'priority_level' => $this->faker->randomElement(RegistrationPriority::cases())->value,
            'submission_date' => $this->faker->date(),
            'approval_date' => $this->faker->optional()->date(),
            'rejection_reason' => $this->faker->optional()->sentence(),
            'notes' => $this->faker->optional()->sentence(),
            'approved_by' => User::factory(),
            'is_paid' => $this->faker->boolean(),
            'payment_reference' => $this->faker->optional()->uuid(),
            'payment_date' => $this->faker->optional()->date(),
            'payment_amount' => $this->faker->optional()->randomFloat(2, 0, 10000),
            'documents_validated' => $this->faker->boolean(),
            'is_renewal' => $this->faker->boolean(),
            'previous_registration_id' => null, // Set to Registration::factory() if needed
        ];
    }
}
