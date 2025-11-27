<?php

namespace Modules\Residency\Database\Factories;

use App\Models\ResidencyProgram;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResidencyProgram>
 */
class ResidencyProgramFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResidencyProgram::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialties = [
            'Internal Medicine',
            'Pediatrics',
            'Surgery',
            'Obstetrics and Gynecology',
            'Emergency Medicine',
            'Radiology',
            'Anesthesiology',
            'Psychiatry',
            'Dermatology',
            'Ophthalmology',
        ];

        return [
            'name' => $this->faker->randomElement($specialties).' Residency Program',
            'description' => $this->faker->paragraph(3),
            'specialty' => $this->faker->randomElement($specialties),
            'duration_months' => $this->faker->randomElement([24, 36, 48, 60]),
            'fee' => $this->faker->randomFloat(2, 1000, 10000),
            'max_participants' => $this->faker->numberBetween(5, 50),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'coordinator_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the program is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the program is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
