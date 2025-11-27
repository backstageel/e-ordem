<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('ADM-001-01: Login com Credenciais Válidas', function () {
    // Criar role de admin se não existir
    $adminRole = Role::firstOrCreate(
        ['name' => 'admin'],
        ['guard_name' => 'web']
    );

    // Criar usuário administrador
    $admin = User::factory()->create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);

    // Atribuir role de admin
    $admin->assignRole('admin');

    // Visitar página de login
    $page = visit('/login');

    // Verificar que estamos na página de login
    $page->assertSee('Ordem dos Médicos')
        ->assertSee('Sistema de Gestão');

    // Preencher formulário de login
    $page->fill('email', 'admin@test.com')
        ->fill('password', 'password')
        ->click('Entrar');

    // Verificar redirecionamento para dashboard administrativo
    $page->assertPathIs('/admin/dashboard');

    // Verificar exibição do nome do usuário no canto superior direito
    $page->assertSee('Admin Test');

    // Verificar menu administrativo visível
    $page->assertSee('Dashboard')
        ->assertNoJavascriptErrors();
});
