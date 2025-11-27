<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $member = Member::factory()->create();

        return [
            'member_id' => $member->id,
            'person_id' => $member->person_id,
            'payment_type_id' => PaymentType::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'reference_number' => 'PAY-'.$this->faker->unique()->randomNumber(6),
            'payment_date' => $this->faker->date(),
            'due_date' => $this->faker->optional()->date(),
            'amount' => $this->faker->randomFloat(2, 0, 10000),
            'recorded_by' => User::factory(),
            'payable_id' => $member->id,
            'payable_type' => 'App\\Models\\Member',
            'status' => \App\Enums\PaymentStatus::PENDING,
        ];
    }
}
