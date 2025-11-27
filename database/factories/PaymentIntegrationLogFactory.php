<?php

namespace Database\Factories;

use App\Models\PaymentIntegration;
use App\Models\PaymentIntegrationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentIntegrationLogFactory extends Factory
{
    protected $model = PaymentIntegrationLog::class;

    public function definition()
    {
        return [
            'payment_integration_id' => PaymentIntegration::factory(),
            'message' => $this->faker->sentence(),
            'level' => $this->faker->randomElement(['info', 'warning', 'error']),
            'created_at' => $this->faker->dateTime(),
        ];
    }
}
