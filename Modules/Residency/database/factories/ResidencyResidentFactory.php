<?php

namespace Modules\Residency\Database\Factories;

use App\Models\Member;
use App\Models\ResidencyProgram;
use App\Models\ResidencyResident;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidencyResidentFactory extends Factory
{
    protected $model = ResidencyResident::class;

    public function definition()
    {
        return [
            'member_id' => Member::factory(),
            'residency_program_id' => ResidencyProgram::factory(),
            'start_date' => $this->faker->date(),
            'expected_completion_date' => $this->faker->dateTimeBetween('+1 year', '+5 years')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['approved', 'in_progress', 'completed', 'cancelled']),
        ];
    }
}
