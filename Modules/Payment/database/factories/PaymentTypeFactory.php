<?php

namespace Modules\Payment\Database\Factories;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTypeFactory extends Factory
{
    protected $model = PaymentType::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->bothify('pt_########'),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'default_amount' => $this->faker->randomFloat(2, 0, 10000),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
