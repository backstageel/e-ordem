<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $this->adminUser = User::factory()->create();
    $this->adminUser->assignRole('admin');
    $this->actingAs($this->adminUser);
});

it('displays registration type selection page for admin', function () {
    $response = $this->get(route('admin.registrations.type-selection'));

    $response->assertStatus(200);
    $response->assertViewIs('registration::admin.registrations.type-selection');
    $response->assertSee('Pré-Inscrição para Certificação');
    $response->assertSee('Inscrição Provisória');
    $response->assertSee('Inscrição Efetiva');
});
