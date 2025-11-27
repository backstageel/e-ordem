<?php

use App\Enums\RegistrationStatus;
use App\Models\Member;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use Spatie\Permission\Models\Role;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'member']);

    $this->person = Person::factory()->create();
    $this->member = Member::factory()->create([
        'person_id' => $this->person->id,
    ]);

    $this->user = User::factory()->create([
        'person_id' => $this->person->id,
    ]);
    $this->user->assignRole('member');
    $this->actingAs($this->user);

    $this->registrationType = RegistrationType::factory()->create([
        'name' => 'Provisional Private',
        'category' => 'provisional',
    ]);

    $this->registration = Registration::factory()->create([
        'member_id' => $this->member->id,
        'person_id' => $this->person->id,
        'registration_type_id' => $this->registrationType->id,
        'status' => RegistrationStatus::APPROVED,
        'expiry_date' => now()->addDays(15),
    ]);
});

it('displays member registrations index', function () {
    $response = $this->get(route('member.registrations.index'));

    $response->assertStatus(200);
    $response->assertViewIs('registration::member.registrations.index');
    $response->assertViewHas('registrations');
    $response->assertViewHas('stats');
});

it('shows registration details', function () {
    $response = $this->get(route('member.registrations.show', $this->registration));

    $response->assertStatus(200);
    $response->assertViewIs('registration::member.registrations.show');
    $response->assertViewHas('registration');
});

it('prevents viewing other member registrations', function () {
    $otherMember = Member::factory()->create();
    $otherRegistration = Registration::factory()->create([
        'member_id' => $otherMember->id,
    ]);

    $response = $this->get(route('member.registrations.show', $otherRegistration));

    $response->assertForbidden();
});

it('shows renew form for renewable registration', function () {
    $response = $this->get(route('member.registrations.renew', $this->registration));

    $response->assertStatus(200);
    $response->assertViewIs('registration::member.registrations.renew');
    $response->assertViewHas('registration');
});

it('prevents renewing non-renewable registration', function () {
    $this->registration->update([
        'expiry_date' => now()->addMonths(6),
    ]);

    $response = $this->get(route('member.registrations.renew', $this->registration));

    $response->assertRedirect(route('member.registrations.index'));
    $response->assertSessionHas('error');
});

it('prevents renewing other member registrations', function () {
    $otherMember = Member::factory()->create();
    $otherRegistration = Registration::factory()->create([
        'member_id' => $otherMember->id,
        'expiry_date' => now()->addDays(15),
    ]);

    $response = $this->get(route('member.registrations.renew', $otherRegistration));

    $response->assertForbidden();
});

it('stores registration renewal', function () {
    $renewalType = RegistrationType::factory()->create([
        'name' => 'Renewal',
        'is_active' => true,
    ]);

    $response = $this->post(route('member.registrations.store-renewal', $this->registration), [
        'workplace' => 'New Workplace',
        'workplace_address' => 'New Address',
        'workplace_phone' => '+258849902058',
        'professional_activities' => 'Test activities',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('registrations', [
        'previous_registration_id' => $this->registration->id,
        'is_renewal' => true,
    ]);
});

it('validates renewal form data', function () {
    $response = $this->post(route('member.registrations.store-renewal', $this->registration), []);

    $response->assertSessionHasErrors(['workplace', 'workplace_address', 'professional_activities']);
});
