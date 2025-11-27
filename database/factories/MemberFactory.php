<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition()
    {
        return [
            'person_id' => Person::factory(),
            'member_number' => $this->faker->unique()->numerify('MEM#####'),
            'registration_number' => $this->faker->unique()->numerify('REG#####'),
            'registration_date' => $this->faker->date(),
            'expiry_date' => $this->faker->dateTimeBetween('+1 year', '+5 years')->format('Y-m-d'),
            'professional_category' => $this->faker->randomElement(['Doctor', 'Nurse', 'Technician', 'Administrator']),
            'specialty' => $this->faker->randomElement(['Internal Medicine', 'Surgery', 'Pediatrics', 'Emergency Medicine']),
            'sub_specialty' => $this->faker->optional()->word(),
            'workplace' => $this->faker->company(),
            'workplace_address' => $this->faker->address(),
            'workplace_phone' => $this->faker->phoneNumber(),
            'workplace_email' => $this->faker->companyEmail(),
            'academic_degree' => $this->faker->randomElement(['Bachelor', 'Master', 'PhD', 'MD']),
            'university' => $this->faker->company(),
            'school_faculty' => $this->faker->word(),
            'other_degrees' => $this->faker->optional()->sentence(),
            'literary_qualifications' => $this->faker->optional()->paragraph(),
            'professional_qualifications' => $this->faker->optional()->paragraph(),
            'academic_registration_number' => $this->faker->optional()->numerify('ACAD#####'),
            'degree_type' => $this->faker->randomElement(['Bachelor', 'Master', 'PhD', 'Certificate']),
            'graduation_year' => $this->faker->numberBetween(1990, 2023),
            'academic_merit' => $this->faker->optional()->randomElement(['Summa Cum Laude', 'Magna Cum Laude', 'Cum Laude']),
            'graduation_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'inactivation_reason' => $this->faker->optional()->sentence(),
            'dues_paid' => $this->faker->boolean(),
            'dues_paid_until' => $this->faker->optional()->date(),
            'qr_code' => $this->faker->optional()->uuid(),
            'status_history' => $this->faker->optional() ? json_encode($this->faker->randomElements(['active', 'inactive', 'pending'], 2)) : null,
            'years_of_experience' => $this->faker->numberBetween(0, 40),
            'previous_license_number' => $this->faker->optional()->numerify('LIC#####'),
            'detailed_experience' => $this->faker->optional()->paragraph(),
            'current_position' => $this->faker->jobTitle(),
            'work_start_date' => $this->faker->optional()->date(),
            'work_end_date' => $this->faker->optional()->date(),
            'service_institution' => $this->faker->optional()->company(),
            'service_sector' => $this->faker->optional()->randomElement(['Public', 'Private', 'NGO']),
            'application_date' => $this->faker->optional()->date(),
            'application_signature' => $this->faker->optional()->uuid(),
            'entry_date' => $this->faker->optional()->date(),
            'entry_category' => $this->faker->optional()->randomElement(['New', 'Transfer', 'Reinstatement']),
            'professional_reference_1_name' => $this->faker->optional()->name(),
            'professional_reference_1_phone' => $this->faker->optional()->phoneNumber(),
            'professional_reference_1_email' => $this->faker->optional()->email(),
            'professional_reference_2_name' => $this->faker->optional()->name(),
            'professional_reference_2_phone' => $this->faker->optional()->phoneNumber(),
            'professional_reference_2_email' => $this->faker->optional()->email(),
            'professional_affiliations' => $this->faker->optional()->paragraph(),
            'languages_spoken' => $this->faker->optional() ? json_encode($this->faker->randomElements(['Portuguese', 'English', 'French', 'Spanish'], 2)) : null,
            'research_interests' => $this->faker->optional()->paragraph(),
            'publications' => $this->faker->optional()->paragraph(),
            'terms_accepted' => $this->faker->boolean(),
            'data_consent' => $this->faker->boolean(),
            'truth_declaration' => $this->faker->boolean(),
            'terms_accepted_date' => $this->faker->optional()->dateTime(),
            'notes' => $this->faker->optional()->sentence(),
            'profile_photo_path' => $this->faker->optional()->imageUrl(),
        ];
    }
}
