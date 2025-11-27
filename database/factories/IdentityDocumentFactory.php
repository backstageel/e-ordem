<?php

namespace Database\Factories;

use App\Models\IdentityDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdentityDocumentFactory extends Factory
{
    protected $model = IdentityDocument::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['BI', 'Passport', 'DIRE', 'Other']),
        ];
    }
}
