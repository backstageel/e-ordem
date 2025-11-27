<?php

namespace Modules\Member\Tests\Unit\Member;

use App\Models\Member;
use App\Models\MemberCard;
use App\Models\Person;

beforeEach(function () {
    // Ensure card type exists for card tests
    \App\Models\CardType::firstOrCreate(
        ['name' => 'Full Member Card'],
        [
            'description' => 'Card for full members',
            'color_code' => '#4CAF50',
            'validity_period_days' => 730,
            'fee' => 500.00,
            'is_active' => true,
        ]
    );

    $this->person = Person::factory()->create();
    $this->member = Member::factory()->create(['person_id' => $this->person->id]);
});

describe('MemberCard Model', function () {
    it('belongs to a member', function () {
        $card = MemberCard::factory()->create(['member_id' => $this->member->id]);

        expect($card->member)->toBeInstanceOf(Member::class);
        expect($card->member_id)->toBe($this->member->id);
    });

    it('can check if card is active', function () {
        $activeCard = MemberCard::factory()->create([
            'member_id' => $this->member->id,
            'status' => 'active',
            'expiry_date' => now()->addYear(),
        ]);

        expect($activeCard->isActive())->toBeTrue();

        $inactiveCard = MemberCard::factory()->create([
            'member_id' => $this->member->id,
            'status' => 'revoked',
        ]);

        expect($inactiveCard->isActive())->toBeFalse();
    });

    it('can check if card is expired', function () {
        $expiredCard = MemberCard::factory()->create([
            'member_id' => $this->member->id,
            'expiry_date' => now()->subDays(10),
        ]);

        expect($expiredCard->isExpired())->toBeTrue();

        $validCard = MemberCard::factory()->create([
            'member_id' => $this->member->id,
            'expiry_date' => now()->addYear(),
        ]);

        expect($validCard->isExpired())->toBeFalse();
    });

    it('can check if card is revoked', function () {
        $revokedCard = MemberCard::factory()->create([
            'member_id' => $this->member->id,
            'status' => 'revoked',
        ]);

        expect($revokedCard->isRevoked())->toBeTrue();

        $activeCard = MemberCard::factory()->create([
            'member_id' => $this->member->id,
            'status' => 'active',
        ]);

        expect($activeCard->isRevoked())->toBeFalse();
    });

    it('has qr_code_path attribute accessor', function () {
        $card = MemberCard::factory()->create([
            'member_id' => $this->member->id,
            'qr_code' => 'path/to/qr.png',
        ]);

        expect($card->qr_code_path)->toBe('path/to/qr.png');
    });

    it('can set qr_code_path attribute', function () {
        $card = MemberCard::factory()->create(['member_id' => $this->member->id]);
        $card->qr_code_path = 'new/path/qr.png';

        expect($card->qr_code)->toBe('new/path/qr.png');
    });

    it('has qr_code_url accessor', function () {
        $card = MemberCard::factory()->create([
            'member_id' => $this->member->id,
            'qr_code' => 'member-cards/qr-123.png',
        ]);

        expect($card->qr_code_url)->toContain('member-cards/qr-123.png');
    });
});

