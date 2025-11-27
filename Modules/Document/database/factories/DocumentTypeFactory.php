<?php

namespace Modules\Document\Database\Factories;

use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTypeFactory extends Factory
{
    protected $model = DocumentType::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->bothify('DOC_########'),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'is_required' => $this->faker->boolean(),
            'requires_translation' => $this->faker->boolean(),
            'requires_validation' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
