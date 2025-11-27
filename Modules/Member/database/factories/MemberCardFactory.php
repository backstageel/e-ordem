<?php

namespace Modules\Member\Database\Factories;

use App\Models\MemberCard;
use App\Models\Member;
use App\Models\CardType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberCardFactory extends Factory
{
    protected $model = MemberCard::class;

    public function definition()
    {
        $cardType = CardType::firstOrCreate(
            ['name' => 'Full Member Card'],
            [
                'description' => 'Card for full members',
                'color_code' => '#4CAF50',
                'validity_period_days' => 730,
                'fee' => 500.00,
                'is_active' => true,
            ]
        );

        return [
            'member_id' => Member::factory(),
            'card_type_id' => $cardType->id,
            'card_number' => 'ORMM-'.str_pad((string) $this->faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT).'-'.date('Ymd'),
            'status' => 'active',
            'issue_date' => now(),
            'expiry_date' => now()->addYear(),
            'qr_code' => 'member-cards/qr-'.uniqid().'.png',
            'is_physical' => true,
            'is_digital' => true,
            'notes' => null,
        ];
    }
}
