<?php

namespace Database\Factories;

use App\Models\MedicalSpeciality;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalSpecialityFactory extends Factory
{
    protected $model = MedicalSpeciality::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'code' => strtoupper($this->faker->unique()->bothify('???')),
            'description' => $this->faker->optional()->sentence(),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}










