<?php

namespace Modules\Document\Database\Factories;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Member;
use App\Models\Person;
use Modules\Registration\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        return [
            'person_id' => Person::factory(), // REQUIRED
            'member_id' => null, // Optional - can be set via state
            'registration_id' => null, // Optional - can be set via state
            'document_type_id' => DocumentType::factory(),
            'file_path' => $this->faker->filePath(),
            'original_filename' => $this->faker->word().'.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => $this->faker->numberBetween(10000, 1000000),
            'status' => $this->faker->randomElement(['pending', 'validated', 'rejected']),
            'submission_date' => $this->faker->date(),
            'validation_date' => $this->faker->optional()->date(),
            'expiry_date' => $this->faker->optional()->date(),
            'rejection_reason' => $this->faker->optional()->sentence(),
            'notes' => $this->faker->optional()->sentence(),
            'validated_by' => User::factory(),
            'has_translation' => $this->faker->boolean(),
            'translation_file_path' => $this->faker->optional()->filePath(),
        ];
    }

    /**
     * Set the member for this document.
     */
    public function forMember(?Member $member = null): static
    {
        return $this->state(function (array $attributes) use ($member) {
            $member = $member ?? Member::factory()->create();

            return [
                'person_id' => $member->person_id,
                'member_id' => $member->id,
            ];
        });
    }

    /**
     * Set the registration for this document.
     */
    public function forRegistration(?Registration $registration = null): static
    {
        return $this->state(function (array $attributes) use ($registration) {
            $registration = $registration ?? Registration::factory()->create();

            return [
                'person_id' => $registration->person_id,
                'registration_id' => $registration->id,
            ];
        });
    }

    /**
     * Set both member and registration for this document.
     */
    public function forMemberAndRegistration(?Member $member = null, ?Registration $registration = null): static
    {
        return $this->state(function (array $attributes) use ($member, $registration) {
            $member = $member ?? Member::factory()->create();
            $registration = $registration ?? Registration::factory()->create();

            // Ensure both belong to the same person
            if ($member->person_id !== $registration->person_id) {
                $registration->person_id = $member->person_id;
                $registration->save();
            }

            return [
                'person_id' => $member->person_id,
                'member_id' => $member->id,
                'registration_id' => $registration->id,
            ];
        });
    }
}
