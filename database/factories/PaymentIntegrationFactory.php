<?php

namespace Database\Factories;

use App\Models\PaymentIntegration;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentIntegrationFactory extends Factory
{
    protected $model = PaymentIntegration::class;

    public function definition()
    {
        return [
            'provider' => $this->faker->word(),
            'config' => [],
            'is_active' => $this->faker->boolean(),
        ];
    }
}
