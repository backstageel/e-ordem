<?php

use App\Models\Member;
use App\Models\Payment;
use Modules\Registration\Models\Registration;
use App\Models\User;
use Database\Seeders\AdminPermissionsSeeder;

beforeEach(function () {
    $this->seed(AdminPermissionsSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
    $this->actingAs($this->admin);
});

it('can access reports dashboard', function () {
    $response = $this->get(route('admin.reports.index'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.index');
    $response->assertSee('Relatórios');
});

it('can generate operational reports', function () {
    // Create test data
    $member = Member::factory()->create();
    $registration = Registration::factory()->create();

    $response = $this->get(route('admin.reports.operational', ['type' => 'members']));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.operational');
    $response->assertSee('Relatório: Members');
});

it('can generate financial reports', function () {
    // Create test data
    $member = Member::factory()->create();
    $payment = Payment::factory()->create(['member_id' => $member->id]);

    $response = $this->get(route('admin.reports.financial', ['type' => 'payments']));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.financial');
    $response->assertSee('Relatório Financeiro: Payments');
});

it('can generate custom reports', function () {
    $response = $this->get(route('admin.reports.custom'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.custom');
    $response->assertSee('Relatório Personalizado');
});

it('can filter operational reports by date range', function () {
    $startDate = now()->subDays(30)->format('Y-m-d');
    $endDate = now()->format('Y-m-d');

    $response = $this->get(route('admin.reports.operational', [
        'type' => 'members',
        'start_date' => $startDate,
        'end_date' => $endDate,
    ]));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.operational');
});

it('can filter financial reports by date range', function () {
    $startDate = now()->subDays(30)->format('Y-m-d');
    $endDate = now()->format('Y-m-d');

    $response = $this->get(route('admin.reports.financial', [
        'type' => 'payments',
        'start_date' => $startDate,
        'end_date' => $endDate,
    ]));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.financial');
});

it('can export operational reports as PDF', function () {
    $member = Member::factory()->create();

    $response = $this->get(route('admin.reports.operational', [
        'type' => 'members',
        'format' => 'pdf',
    ]));

    $response->assertSuccessful();
    $response->assertHeader('Content-Type', 'application/pdf');
});

it('can export operational reports as Excel', function () {
    $member = Member::factory()->create();

    $response = $this->get(route('admin.reports.operational', [
        'type' => 'members',
        'format' => 'excel',
    ]));

    $response->assertSuccessful();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

it('can export financial reports as PDF', function () {
    $member = Member::factory()->create();
    $payment = Payment::factory()->create(['member_id' => $member->id]);

    $response = $this->get(route('admin.reports.financial', [
        'type' => 'payments',
        'format' => 'pdf',
    ]));

    $response->assertSuccessful();
    $response->assertHeader('Content-Type', 'application/pdf');
});

it('can export financial reports as Excel', function () {
    $member = Member::factory()->create();
    $payment = Payment::factory()->create(['member_id' => $member->id]);

    $response = $this->get(route('admin.reports.financial', [
        'type' => 'payments',
        'format' => 'excel',
    ]));

    $response->assertSuccessful();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

it('can get report statistics', function () {
    $response = $this->get(route('admin.reports.statistics'));

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'members' => ['total', 'new', 'active'],
        'registrations' => ['total', 'new', 'approved'],
        'payments' => ['total', 'paid', 'pending', 'overdue', 'total_amount'],
        'exams' => ['total', 'scheduled', 'completed'],
        'programs' => ['total', 'active', 'applications'],
    ]);
});

it('can generate different types of operational reports', function () {
    $types = ['members', 'registrations', 'programs', 'applications'];

    foreach ($types as $type) {
        $response = $this->get(route('admin.reports.operational', ['type' => $type]));
        $response->assertSuccessful();
        $response->assertViewIs('admin.reports.operational');
    }
});

it('can generate different types of financial reports', function () {
    $types = ['payments', 'revenue', 'pending', 'overdue'];

    foreach ($types as $type) {
        $response = $this->get(route('admin.reports.financial', ['type' => $type]));
        $response->assertSuccessful();
        $response->assertViewIs('admin.reports.financial');
    }
});

it('generates reports without manual audit logging', function () {
    $member = Member::factory()->create();

    $response = $this->get(route('admin.reports.operational', ['type' => 'members']));

    $response->assertSuccessful();
    
    // Verify the report was generated successfully
    $response->assertViewIs('admin.reports.operational');
    $response->assertViewHas('data');
});

it('enforces admin middleware on report routes', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('admin.reports.index'));

    $response->assertRedirect();
});

it('allows admin users to access report routes', function () {
    $response = $this->get(route('admin.reports.index'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.index');
});

it('handles empty data gracefully in operational reports', function () {
    $response = $this->get(route('admin.reports.operational', [
        'type' => 'members',
        'start_date' => '2020-01-01',
        'end_date' => '2020-01-02',
    ]));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.operational');
    $response->assertSee('Nenhum registro encontrado');
});

it('handles empty data gracefully in financial reports', function () {
    $response = $this->get(route('admin.reports.financial', [
        'type' => 'payments',
        'start_date' => '2020-01-01',
        'end_date' => '2020-01-02',
    ]));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.financial');
    $response->assertSee('Nenhum pagamento encontrado');
});

it('can generate custom reports with filters', function () {
    $response = $this->get(route('admin.reports.custom', [
        'type' => 'members',
        'start_date' => now()->subDays(30)->format('Y-m-d'),
        'end_date' => now()->format('Y-m-d'),
    ]));

    $response->assertSuccessful();
    $response->assertViewIs('admin.reports.custom');
});
