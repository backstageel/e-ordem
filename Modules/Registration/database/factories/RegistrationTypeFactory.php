<?php

namespace Modules\Registration\Database\Factories;

use App\Enums\RegistrationCategory;
use App\Enums\RegistrationTypeCode;
use Modules\Registration\Models\RegistrationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationTypeFactory extends Factory
{
    protected $model = RegistrationType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'code' => $this->faker->randomElement(RegistrationTypeCode::cases())->value.'_'.uniqid(),
            'description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement(RegistrationCategory::cases())->value,
            'payment_type_code' => $this->faker->unique()->bothify('PT_########'),
            'fee' => $this->faker->randomFloat(2, 0, 10000),
            'validity_period_days' => $this->faker->numberBetween(30, 365),
            'renewable' => $this->faker->boolean(),
            'max_renewals' => $this->faker->numberBetween(0, 5),
            'required_documents' => json_encode(['identity_document', 'diploma']),
            'eligibility_criteria' => json_encode(['min_experience_years' => 2]),
            'workflow_steps' => json_encode(['initial_review', 'document_validation', 'final_approval']),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
