<?php

namespace Modules\Member\Tests\Feature\Member;

use App\Actions\Member\CreateMemberAction;
use App\Actions\Member\GenerateMemberCardAction;
use App\Actions\Member\ReactivateMemberAction;
use App\Actions\Member\SuspendMemberAction;
use App\Actions\Member\UpdateMemberAction;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberQuota;
use App\Models\MemberStatusHistory;
use App\Models\MedicalSpeciality;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'member']);

    // Ensure card type exists for card generation tests
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

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->actingAs($this->user);

    Notification::fake();
});

describe('CreateMemberAction', function () {
    it('can create a new member with person data', function () {
        $action = new CreateMemberAction;
        $person = Person::factory()->make();

        $member = $action->execute([
            'person' => [
                'email' => $person->email,
                'phone' => $person->phone,
                'first_name' => $person->first_name,
                'last_name' => $person->last_name,
                'gender_id' => $person->gender_id,
                'birth_date' => $person->birth_date,
                'nationality_id' => $person->nationality_id,
                'identity_document_number' => $person->identity_document_number,
                'living_address' => $person->living_address,
            ],
            'professional_category' => 'Doctor',
            'specialty' => 'Cardiology',
            'status' => Member::STATUS_ACTIVE,
        ]);

        expect($member)->toBeInstanceOf(Member::class);
        expect($member->person)->toBeInstanceOf(Person::class);
        expect($member->status)->toBe(Member::STATUS_ACTIVE);
        expect($member->member_number)->toStartWith('MEM');

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        $this->assertDatabaseHas('people', [
            'email' => $person->email,
            'first_name' => $person->first_name,
        ]);

        $this->assertDatabaseHas('member_status_histories', [
            'member_id' => $member->id,
            'new_status' => Member::STATUS_ACTIVE,
        ]);
    });

    it('generates initial quotas for active members', function () {
        $action = new CreateMemberAction;
        $person = Person::factory()->make();

        $member = $action->execute([
            'person' => [
                'email' => $person->email,
                'phone' => $person->phone,
                'first_name' => $person->first_name,
                'last_name' => $person->last_name,
                'gender_id' => $person->gender_id,
                'birth_date' => $person->birth_date,
                'nationality_id' => $person->nationality_id,
                'identity_document_number' => $person->identity_document_number,
                'living_address' => $person->living_address,
            ],
            'professional_category' => 'Doctor',
            'status' => Member::STATUS_ACTIVE,
        ]);

        $currentYear = now()->year;
        $currentMonth = now()->month;

        $this->assertDatabaseHas('member_quotas', [
            'member_id' => $member->id,
            'year' => $currentYear,
            'month' => $currentMonth,
            'status' => MemberQuota::STATUS_PENDING,
        ]);
    });

    it('does not generate quotas for inactive members', function () {
        $action = new CreateMemberAction;
        $person = Person::factory()->make();

        $member = $action->execute([
            'person' => [
                'email' => $person->email,
                'phone' => $person->phone,
                'first_name' => $person->first_name,
                'last_name' => $person->last_name,
                'gender_id' => $person->gender_id,
                'birth_date' => $person->birth_date,
                'nationality_id' => $person->nationality_id,
                'identity_document_number' => $person->identity_document_number,
                'living_address' => $person->living_address,
            ],
            'professional_category' => 'Doctor',
            'status' => Member::STATUS_INACTIVE,
        ]);

        $quotaCount = MemberQuota::where('member_id', $member->id)->count();
        expect($quotaCount)->toBe(0);
    });

    it('generates unique member numbers', function () {
        $action = new CreateMemberAction;
        $person1 = Person::factory()->make(['email' => 'test1@example.com']);
        $person2 = Person::factory()->make(['email' => 'test2@example.com']);

        $member1 = $action->execute([
            'person' => [
                'email' => $person1->email,
                'phone' => $person1->phone,
                'first_name' => $person1->first_name,
                'last_name' => $person1->last_name,
                'gender_id' => $person1->gender_id,
                'birth_date' => $person1->birth_date,
                'nationality_id' => $person1->nationality_id,
                'identity_document_number' => $person1->identity_document_number,
                'living_address' => $person1->living_address,
            ],
            'professional_category' => 'Doctor',
            'status' => Member::STATUS_ACTIVE,
        ]);

        $member2 = $action->execute([
            'person' => [
                'email' => $person2->email,
                'phone' => $person2->phone,
                'first_name' => $person2->first_name,
                'last_name' => $person2->last_name,
                'gender_id' => $person2->gender_id,
                'birth_date' => $person2->birth_date,
                'nationality_id' => $person2->nationality_id,
                'identity_document_number' => $person2->identity_document_number,
                'living_address' => $person2->living_address,
            ],
            'professional_category' => 'Doctor',
            'status' => Member::STATUS_ACTIVE,
        ]);

        expect($member1->member_number)->not->toBe($member2->member_number);
    });
});

describe('UpdateMemberAction', function () {
    it('can update member data', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id, 'status' => Member::STATUS_ACTIVE]);

        $action = new UpdateMemberAction;

        $updatedMember = $action->execute($member, [
            'professional_category' => 'Updated Category',
            'specialty' => 'Updated Specialty',
        ], 'Test update');

        expect($updatedMember->professional_category)->toBe('Updated Category');
        expect($updatedMember->specialty)->toBe('Updated Specialty');

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'professional_category' => 'Updated Category',
            'specialty' => 'Updated Specialty',
        ]);
    });

    it('can update person data through member', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);

        $action = new UpdateMemberAction;

        $updatedMember = $action->execute($member, [
            'person' => [
                'first_name' => 'Updated First Name',
                'last_name' => 'Updated Last Name',
            ],
        ]);

        expect($updatedMember->person->first_name)->toBe('Updated First Name');
        expect($updatedMember->person->last_name)->toBe('Updated Last Name');

        $this->assertDatabaseHas('people', [
            'id' => $person->id,
            'first_name' => 'Updated First Name',
            'last_name' => 'Updated Last Name',
        ]);
    });

    it('creates status history when status changes', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        $action = new UpdateMemberAction;

        $action->execute($member, [
            'status' => Member::STATUS_SUSPENDED,
        ], 'Status update test');

        $this->assertDatabaseHas('member_status_histories', [
            'member_id' => $member->id,
            'previous_status' => Member::STATUS_ACTIVE,
            'new_status' => Member::STATUS_SUSPENDED,
        ]);
    });

    it('does not create status history when status does not change', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        $initialHistoryCount = MemberStatusHistory::where('member_id', $member->id)->count();

        $action = new UpdateMemberAction;

        $action->execute($member, [
            'professional_category' => 'Updated Category',
        ]);

        $finalHistoryCount = MemberStatusHistory::where('member_id', $member->id)->count();
        expect($finalHistoryCount)->toBe($initialHistoryCount);
    });
});

describe('SuspendMemberAction', function () {
    it('can suspend a member', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        $action = new SuspendMemberAction;

        $action->execute($member, 'Test suspension reason', $this->user->id);

        $member->refresh();
        expect($member->status)->toBe(Member::STATUS_SUSPENDED);

        $this->assertDatabaseHas('member_status_histories', [
            'member_id' => $member->id,
            'new_status' => Member::STATUS_SUSPENDED,
            'reason' => 'Test suspension reason',
        ]);
    });

    it('revokes active cards when suspending member', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        $card = MemberCard::factory()->create([
            'member_id' => $member->id,
            'status' => 'active',
        ]);

        $action = new SuspendMemberAction;

        $action->execute($member, 'Test suspension', $this->user->id);

        $card->refresh();
        expect($card->status)->toBe('revoked');
    });

    it('sends suspension notification if user exists', function () {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        $action = new SuspendMemberAction;

        $action->execute($member, 'Test suspension', $this->user->id);

        Notification::assertSentTo($user, \App\Notifications\Member\SuspensionNotification::class);
    });
});

describe('ReactivateMemberAction', function () {
    it('can reactivate a suspended member', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_SUSPENDED,
        ]);

        $action = new ReactivateMemberAction;

        $action->execute($member, 'Test reactivation reason', $this->user->id);

        $member->refresh();
        expect($member->status)->toBe(Member::STATUS_ACTIVE);

        $this->assertDatabaseHas('member_status_histories', [
            'member_id' => $member->id,
            'previous_status' => Member::STATUS_SUSPENDED,
            'new_status' => Member::STATUS_ACTIVE,
            'reason' => 'Test reactivation reason',
        ]);
    });

    it('sends reactivation notification if user exists', function () {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_SUSPENDED,
        ]);

        $action = new ReactivateMemberAction;

        $action->execute($member, 'Test reactivation', $this->user->id);

        Notification::assertSentTo($user, \App\Notifications\Member\ReactivationNotification::class);
    });
});

describe('GenerateMemberCardAction', function () {
    it('can generate a card for an active member with regular quotas', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
            'dues_paid' => true, // Ensure regular quotas
            'dues_paid_until' => now()->addYear(),
        ]);

        // Ensure member has regular quotas (none overdue)
        expect($member->isQuotaRegular())->toBeTrue();

        $action = new GenerateMemberCardAction;

        $card = $action->execute($member);

        expect($card)->toBeInstanceOf(MemberCard::class);
        expect($card->member_id)->toBe($member->id);
        expect($card->card_number)->toStartWith('ORMM-');
        expect($card->status)->toBe('active');

        $this->assertDatabaseHas('cards', [
            'member_id' => $member->id,
            'status' => 'active',
        ]);
    });

    it('throws exception when member cannot generate card', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_SUSPENDED, // Suspended member cannot generate card
        ]);

        $action = new GenerateMemberCardAction;

        expect(fn () => $action->execute($member))
            ->toThrow(\Exception::class, 'Membro não pode gerar cartão');
    });
});

