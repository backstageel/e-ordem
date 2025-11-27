<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

it('displays registration type selection page for guests', function () {
    $response = $this->get(route('guest.registrations.type-selection'));

    $response->assertStatus(200);
    $response->assertViewIs('registration::guest.registrations.type-selection');
    $response->assertSee('Pré-Inscrição para Certificação');
    $response->assertSee('Inscrição Provisória');
    $response->assertSee('Inscrição Efetiva');
});
