<?php

namespace Modules\Member\Database\Factories;

use App\Models\Member;
use App\Models\MemberStatusHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberStatusHistory>
 */
class MemberStatusHistoryFactory extends Factory
{
    protected $model = MemberStatusHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            Member::STATUS_ACTIVE,
            Member::STATUS_SUSPENDED,
            Member::STATUS_INACTIVE,
            Member::STATUS_IRREGULAR,
            Member::STATUS_CANCELED,
        ];

        $previousStatus = $this->faker->randomElement([null, ...$statuses]);
        $newStatus = $this->faker->randomElement($statuses);

        return [
            'member_id' => Member::factory(),
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'changed_by' => User::factory(),
            'reason' => $this->faker->optional()->randomElement([
                'Suspensão automática por inadimplência',
                'Reativação após pagamento de quotas',
                'Mudança de status administrativa',
                'Cancelamento de inscrição',
                'Regularização de situação',
            ]),
            'notes' => $this->faker->optional()->sentence(),
            'effective_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the status change was to active.
     */
    public function toActive(): static
    {
        return $this->state(fn (array $attributes) => [
            'new_status' => Member::STATUS_ACTIVE,
            'reason' => 'Reativação de membro',
        ]);
    }

    /**
     * Indicate that the status change was to suspended.
     */
    public function toSuspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'new_status' => Member::STATUS_SUSPENDED,
            'reason' => 'Suspensão automática por inadimplência',
        ]);
    }
}
