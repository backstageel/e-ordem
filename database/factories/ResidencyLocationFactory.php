<?php

namespace Database\Factories;

use App\Models\ResidencyLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResidencyLocation>
 */
class ResidencyLocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResidencyLocation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            'Maputo',
            'Beira',
            'Nampula',
            'Chimoio',
            'Quelimane',
            'Tete',
            'Pemba',
            'Xai-Xai',
            'Inhambane',
            'Lichinga',
        ];

        $provinces = [
            'Maputo',
            'Sofala',
            'Nampula',
            'Manica',
            'Zambézia',
            'Tete',
            'Cabo Delgado',
            'Gaza',
            'Inhambane',
            'Niassa',
        ];

        return [
            'name' => $this->faker->randomElement(['Hospital', 'Centro de Saúde', 'Clínica']).' '.$this->faker->company(),
            'description' => $this->faker->paragraph(2),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->randomElement($cities),
            'province' => $this->faker->randomElement($provinces),
            'postal_code' => $this->faker->postcode(),
            'country_id' => 1, // Mozambique
            'phone_number' => '+258 '.$this->faker->numerify('## ### ####'),
            'email' => $this->faker->companyEmail(),
            'website' => $this->faker->optional(0.7)->url(),
            'capacity' => $this->faker->numberBetween(10, 100),
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
        ];
    }

    /**
     * Indicate that the location is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the location is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
