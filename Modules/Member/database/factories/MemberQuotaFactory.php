<?php

namespace Modules\Member\Database\Factories;

use App\Models\Member;
use App\Models\MemberQuota;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberQuota>
 */
class MemberQuotaFactory extends Factory
{
    protected $model = MemberQuota::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = $this->faker->numberBetween(2020, now()->year);
        $month = $this->faker->numberBetween(1, 12);
        $dueDate = \Carbon\Carbon::create($year, $month, 15);
        $status = $this->faker->randomElement([
            MemberQuota::STATUS_PENDING,
            MemberQuota::STATUS_PAID,
            MemberQuota::STATUS_OVERDUE,
        ]);

        return [
            'member_id' => Member::factory(),
            'year' => $year,
            'month' => $month,
            'amount' => config('members.quota.default_amount', 4000.00),
            'due_date' => $dueDate,
            'payment_date' => $status === MemberQuota::STATUS_PAID ? $dueDate->copy()->addDays($this->faker->numberBetween(0, 30)) : null,
            'status' => $status,
            'payment_id' => $status === MemberQuota::STATUS_PAID ? Payment::factory() : null,
            'penalty_amount' => $status === MemberQuota::STATUS_OVERDUE ? $this->faker->randomFloat(2, 0, 2000) : 0,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the quota is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MemberQuota::STATUS_PENDING,
            'payment_date' => null,
            'payment_id' => null,
            'penalty_amount' => 0,
        ]);
    }

    /**
     * Indicate that the quota is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MemberQuota::STATUS_PAID,
            'payment_date' => \Carbon\Carbon::parse($attributes['due_date'])->addDays($this->faker->numberBetween(0, 30)),
            'payment_id' => Payment::factory(),
            'penalty_amount' => 0,
        ]);
    }

    /**
     * Indicate that the quota is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays($this->faker->numberBetween(1, 90)),
            'payment_date' => null,
            'payment_id' => null,
            'penalty_amount' => $attributes['amount'] * config('members.quota.penalty_percentage', 0.5),
        ]);
    }
}
