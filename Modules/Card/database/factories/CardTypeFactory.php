<?php

namespace Modules\Card\Database\Factories;

use App\Models\CardType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardTypeFactory extends Factory
{
    protected $model = CardType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->sentence(),
            'color_code' => $this->faker->hexColor(),
            'validity_period_days' => $this->faker->numberBetween(365, 730),
            'fee' => $this->faker->randomFloat(2, 300, 1000),
            'is_active' => true,
        ];
    }
}










