<?php

namespace Database\Factories;

use App\Models\CivilState;
use Illuminate\Database\Eloquent\Factories\Factory;

class CivilStateFactory extends Factory
{
    protected $model = CivilState::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
        ];
    }
}
