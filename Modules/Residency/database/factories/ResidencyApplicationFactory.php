<?php

namespace Modules\Residency\Database\Factories;

use App\Models\Member;
use App\Models\ResidencyApplication;
use App\Models\ResidencyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidencyApplicationFactory extends Factory
{
    protected $model = ResidencyApplication::class;

    public function definition()
    {
        return [
            'member_id' => Member::factory(),
            'residency_program_id' => ResidencyProgram::factory(),
            'application_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'in_progress', 'completed', 'cancelled']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
