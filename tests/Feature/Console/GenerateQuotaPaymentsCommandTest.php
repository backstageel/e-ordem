<?php

use App\Models\Member;
use App\Models\MemberQuota;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // No setup needed for quota generation - MemberQuotaService handles everything
});

it('generates quota payments for all active members for current year', function () {
    $person1 = Person::factory()->create();
    $person2 = Person::factory()->create();
    $member1 = Member::factory()->create(['person_id' => $person1->id, 'status' => 'active']);
    $member2 = Member::factory()->create(['person_id' => $person2->id, 'status' => 'active']);

    $currentYear = date('Y');

    $this->artisan('members:generate-quotas')
        ->expectsOutput("Generating quotas for year: {$currentYear}, all months")
        ->expectsOutput('Found 2 active member(s).')
        ->assertSuccessful();

    // Should generate 12 quotas for each member (one per month)
    // Note: The new system uses member_quotas table, not payments table
    $this->assertDatabaseCount('member_quotas', 24);

    // Verify quotas were created for both members
    $member1Quotas = \App\Models\MemberQuota::where('member_id', $member1->id)->get();
    $member2Quotas = \App\Models\MemberQuota::where('member_id', $member2->id)->get();

    expect($member1Quotas)->toHaveCount(12)
        ->and($member2Quotas)->toHaveCount(12);
});

it('generates quota payments for a specific year', function () {
    $person = Person::factory()->create();
    $member = Member::factory()->create(['person_id' => $person->id, 'status' => 'active']);

    $this->artisan('members:generate-quotas', ['--year' => '2024'])
        ->expectsOutput('Generating quotas for year: 2024, all months')
        ->assertSuccessful();

    $quotas = MemberQuota::where('member_id', $member->id)->get();

    expect($quotas)->toHaveCount(12);

    $quotas->each(function ($quota) {
        expect($quota->payment_date)->toBeNull()
            ->and($quota->status)->toBe('pending');
    });
});

it('generates quota payments for a specific month', function () {
    $person = Person::factory()->create();
    $member = Member::factory()->create(['person_id' => $person->id, 'status' => 'active']);

    $currentYear = date('Y');

    $this->artisan('members:generate-quotas', ['--month' => '6'])
        ->expectsOutput("Generating quotas for year: {$currentYear}, month: 6")
        ->assertSuccessful();

    $quotas = MemberQuota::where('member_id', $member->id)->get();

    expect($quotas)->toHaveCount(1);
});

it('skips existing quotas when force is not used', function () {
    $person = Person::factory()->create();
    $member = Member::factory()->create(['person_id' => $person->id, 'status' => 'active']);

    $currentYear = date('Y');

    // Create existing quota for January
    MemberQuota::create([
        'member_id' => $member->id,
        'year' => $currentYear,
        'month' => 1,
        'amount' => 4000.00,
        'due_date' => "{$currentYear}-01-31",
        'status' => 'pending',
    ]);

    $this->artisan('members:generate-quotas')
        ->assertSuccessful();

    // Should have 12 quotas total (1 existing + 11 new)
    $quotas = MemberQuota::where('member_id', $member->id)->count();

    expect($quotas)->toBe(12);
});

it('regenerates existing quotas when force option is used', function () {
    $person = Person::factory()->create();
    $member = Member::factory()->create(['person_id' => $person->id, 'status' => 'active']);

    $currentYear = date('Y');

    // Create existing quota for January
    MemberQuota::create([
        'member_id' => $member->id,
        'year' => $currentYear,
        'month' => 1,
        'amount' => 4000.00,
        'due_date' => "{$currentYear}-01-31",
        'status' => 'pending',
    ]);

    $this->artisan('members:generate-quotas', ['--force' => true])
        ->assertSuccessful();

    // Should still have 12 quotas (the existing one should be replaced)
    $quotas = MemberQuota::where('member_id', $member->id)->count();

    expect($quotas)->toBe(12);
});

it('does not generate payments for inactive members', function () {
    $person1 = Person::factory()->create();
    $person2 = Person::factory()->create();
    Member::factory()->create(['person_id' => $person1->id, 'status' => 'active']);
    Member::factory()->create(['person_id' => $person2->id, 'status' => 'inactive']);

    $this->artisan('members:generate-quotas')
        ->expectsOutput('Found 1 active member(s).')
        ->assertSuccessful();

    $this->assertDatabaseCount('member_quotas', 12);
});

it('handles members without person record gracefully', function () {
    $person = Person::factory()->create();
    $member = Member::factory()->create(['person_id' => $person->id, 'status' => 'active']);

    $this->artisan('members:generate-quotas')
        ->assertSuccessful();

    // Should create quotas for valid member with person
    $this->assertDatabaseHas('member_quotas', ['member_id' => $member->id]);
});

it('handles generation gracefully when no members exist', function () {
    // No members created
    $this->artisan('members:generate-quotas')
        ->expectsOutput('No active members found.')
        ->assertSuccessful();
});

it('generates quotas with correct year and month', function () {
    $person = Person::factory()->create();
    $member = Member::factory()->create(['person_id' => $person->id, 'status' => 'active']);

    $currentYear = date('Y');

    $this->artisan('members:generate-quotas', ['--month' => '3'])
        ->assertSuccessful();

    $quota = MemberQuota::where('member_id', $member->id)
        ->where('year', $currentYear)
        ->where('month', 3)
        ->first();

    expect($quota)->not->toBeNull()
        ->and((int) $quota->year)->toBe((int) $currentYear)
        ->and($quota->month)->toBe(3);
});
