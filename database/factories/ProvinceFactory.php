<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinceFactory extends Factory
{
    protected $model = Province::class;

    public function definition()
    {
        return [
            'name' => $this->faker->state(),
            'code' => strtoupper($this->faker->unique()->bothify('??')),
            'country_id' => Country::factory(),
        ];
    }
}
