<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistrictFactory extends Factory
{
    protected $model = District::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'province_id' => Province::factory(),
            'country_id' => Country::factory(),
        ];
    }
}
