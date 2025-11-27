<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition()
    {
        return [
            'user_id' => null, // Set to User::factory() if needed
            'civility' => $this->faker->optional(0.7)->randomElement(['Mr', 'Mrs', 'Ms', 'Dr']),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->firstName(),
            'last_name' => $this->faker->lastName(),
            'name' => $this->faker->optional()->name(),
            'father_name' => $this->faker->optional()->name('male'),
            'mother_name' => $this->faker->optional()->name('female'),
            'gender_id' => null, // Set to Gender::factory() if needed
            'marital_status_id' => null, // Set to CivilState::factory() if needed
            'birth_country_id' => null, // Set to Country::factory() if needed
            'birth_province_id' => null, // Set to Province::factory() if needed
            'birth_district_id' => null, // Set to District::factory() if needed
            'birth_date' => $this->faker->optional()->date(),
            'identity_document_id' => null, // Set to IdentityDocument::factory() if needed
            'identity_document_number' => $this->faker->unique()->numerify('ID#####'),
            'nationality_id' => null, // Set to Country::factory() if needed
            'identity_document_issue_date' => $this->faker->optional()->date(),
            'identity_document_issue_place' => $this->faker->optional()->city(),
            'identity_document_expiry_date' => $this->faker->optional()->date(),
            'has_disability' => $this->faker->boolean(),
            'disability_description' => $this->faker->optional()->sentence(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => $this->faker->optional()->phoneNumber(),
            'fax' => $this->faker->optional()->phoneNumber(),
            'living_address' => $this->faker->optional()->address(),
            'profile_picture_url' => $this->faker->optional()->imageUrl(),
            'notes' => $this->faker->optional()->text(),
            'website' => $this->faker->optional()->url(),
            'linkedin' => $this->faker->optional()->url(),
        ];
    }
}
