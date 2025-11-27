<?php

namespace Modules\Member\Tests\Feature;

use App\Models\Member;
use App\Models\Person;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create roles
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'member']);
    Role::firstOrCreate(['name' => 'teacher']);
    Role::firstOrCreate(['name' => 'evaluator']);

    // Create a user for authentication
    $this->user = User::factory()->create();

    // Assign admin role to the user
    $this->user->assignRole('admin');
});

test('members index page can be rendered', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.members.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.members.index');
});

test('member create page can be rendered', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.members.create'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.members.create');
});

test('member can be created', function () {
    $personData = [
        'first_name' => $this->faker->firstName,
        'last_name' => $this->faker->lastName,
        'email' => $this->faker->unique()->safeEmail,
        'phone' => $this->faker->phoneNumber,
        'birth_date' => $this->faker->date,
        'gender_id' => 1, // Use ID instead of string
        // Use existing country from States (ID 148 = Moçambique)
        'nationality_id' => \App\Models\Country::where('iso', 'MOZ')->firstOrFail()->id,
        // Use existing identity document from States (ID 1 = 'BI')
        'identity_document_id' => \DB::table('identity_documents')->where('name', 'BI')->firstOrFail()->id,
        'identity_document_number' => $this->faker->unique()->numerify('########'),
        'living_address' => $this->faker->address,
    ];

    $memberData = [
        'professional_category' => $this->faker->jobTitle,
        'specialty' => $this->faker->word,
        'sub_specialty' => $this->faker->word,
        'workplace' => $this->faker->company,
        'workplace_address' => $this->faker->address,
        'workplace_phone' => $this->faker->phoneNumber,
        'workplace_email' => $this->faker->companyEmail,
        'academic_degree' => 'PhD',
        'university' => $this->faker->company,
        'graduation_date' => $this->faker->date,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.members.store'), array_merge($personData, $memberData));

    $response->assertRedirect();
    $this->assertDatabaseHas('people', [
        'first_name' => $personData['first_name'],
        'last_name' => $personData['last_name'],
        'email' => $personData['email'],
    ]);
    $this->assertDatabaseHas('members', [
        'professional_category' => $memberData['professional_category'],
        'specialty' => $memberData['specialty'],
    ]);
});

test('member show page can be rendered', function () {
    // Create a person and member
    $person = Person::factory()->create();
    $member = Member::factory()->create(['person_id' => $person->id]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.members.show', $member));

    $response->assertStatus(200);
    $response->assertViewIs('admin.members.show');
    $response->assertViewHas('member');
});

test('member edit page can be rendered', function () {
    // Create a person and member
    $person = Person::factory()->create();
    $member = Member::factory()->create(['person_id' => $person->id]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.members.edit', $member));

    $response->assertStatus(200);
    $response->assertViewIs('admin.members.edit');
    $response->assertViewHas('member');
});

test('member can be updated', function () {
    // Create a person and member
    $person = Person::factory()->create();
    $member = Member::factory()->create(['person_id' => $person->id]);

    $updatedData = [
        'first_name' => 'Updated First Name',
        'last_name' => 'Updated Last Name',
        'email' => $person->email, // Keep the same email to avoid unique constraint
        'phone' => $this->faker->phoneNumber,
        'birth_date' => $this->faker->date,
        'gender_id' => 2, // Use ID instead of string
        // Use existing country from States (ID 148 = Moçambique)
        'nationality_id' => \App\Models\Country::where('iso', 'MOZ')->firstOrFail()->id,
        // Use existing identity document from States (ID 1 = 'BI')
        'identity_document_id' => \DB::table('identity_documents')->where('name', 'BI')->firstOrFail()->id,
        'identity_document_number' => $person->identity_document_number, // Keep the same to avoid unique constraint
        'living_address' => $this->faker->address,
        'professional_category' => 'Updated Category',
        'specialty' => 'Updated Specialty',
        'sub_specialty' => 'Updated Sub-specialty',
        'workplace' => 'Updated Workplace',
        'workplace_address' => $this->faker->address,
        'workplace_phone' => $this->faker->phoneNumber,
        'workplace_email' => $this->faker->companyEmail,
        'academic_degree' => 'Masters',
        'university' => 'Updated University',
        'graduation_date' => $this->faker->date,
    ];

    $response = $this->actingAs($this->user)
        ->put(route('admin.members.update', $member), $updatedData);

    $response->assertRedirect();
    $this->assertDatabaseHas('people', [
        'id' => $person->id,
        'first_name' => 'Updated First Name',
        'last_name' => 'Updated Last Name',
    ]);
    $this->assertDatabaseHas('members', [
        'id' => $member->id,
        'professional_category' => 'Updated Category',
        'specialty' => 'Updated Specialty',
    ]);
});

test('member status can be updated', function () {
    // Create a person and member
    $person = Person::factory()->create();
    $member = Member::factory()->create([
        'person_id' => $person->id,
        'status' => 'active',
    ]);

    $response = $this->actingAs($this->user)
        ->patch(route('admin.members.status', $member), [
            'status' => 'suspended',
            'notes' => 'Suspended for testing',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('members', [
        'id' => $member->id,
        'status' => 'suspended',
        'notes' => 'Suspended for testing',
    ]);
});

test('member quota status can be updated', function () {
    // Create a person and member
    $person = Person::factory()->create();
    $member = Member::factory()->create([
        'person_id' => $person->id,
        'dues_paid' => false,
        'dues_paid_until' => null,
    ]);

    $dueDate = now()->addYear()->format('Y-m-d');

    $response = $this->actingAs($this->user)
        ->patch(route('admin.members.update-quota', $member), [
            'dues_paid' => true,
            'dues_paid_until' => $dueDate,
            'notes' => 'Dues paid for testing',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('members', [
        'id' => $member->id,
        'dues_paid' => true,
        'notes' => 'Dues paid for testing',
    ]);
});
