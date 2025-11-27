<?php

namespace Modules\Residency\Tests\Feature;

use App\Models\Member;
use App\Models\ResidencyApplication;
use App\Models\ResidencyEvaluation;
use App\Models\ResidencyLocation;
use App\Models\ResidencyProgram;
use App\Models\ResidencyProgramLocation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

it('can create a residency program', function () {
    $program = ResidencyProgram::create([
        'name' => 'Internal Medicine Residency',
        'description' => 'Comprehensive training in internal medicine',
        'specialty' => 'Internal Medicine',
        'duration_months' => 36,
        'fee' => 5000.00,
        'max_participants' => 20,
        'is_active' => true,
    ]);

    expect($program->name)->toBe('Internal Medicine Residency');
    expect($program->specialty)->toBe('Internal Medicine');
    expect($program->duration_months)->toBe(36);
    expect($program->fee)->toBe('5000.00');
    expect($program->is_active)->toBeTrue();
});

it('can create a residency location', function () {
    $location = ResidencyLocation::create([
        'name' => 'Central Hospital',
        'description' => 'Main teaching hospital',
        'address' => '123 Main Street',
        'city' => 'Maputo',
        'province' => 'Maputo',
        'country_id' => 1,
        'phone_number' => '+258 21 123456',
        'email' => 'info@centralhospital.co.mz',
        'capacity' => 50,
        'is_active' => true,
    ]);

    expect($location->name)->toBe('Central Hospital');
    expect($location->city)->toBe('Maputo');
    expect($location->capacity)->toBe(50);
    expect($location->is_active)->toBeTrue();
});

it('can create a residency application', function () {
    $member = Member::factory()->create();
    $program = ResidencyProgram::factory()->create();

    $application = ResidencyApplication::create([
        'member_id' => $member->id,
        'residency_program_id' => $program->id,
        'status' => 'pending',
        'application_date' => now(),
    ]);

    expect($application->member_id)->toBe($member->id);
    expect($application->residency_program_id)->toBe($program->id);
    expect($application->status)->toBe('pending');
});

it('can create a residency evaluation', function () {
    $member = Member::factory()->create();
    $program = ResidencyProgram::factory()->create();
    $user = User::factory()->create();

    $application = ResidencyApplication::create([
        'member_id' => $member->id,
        'residency_program_id' => $program->id,
        'status' => 'in_progress',
        'application_date' => now(),
    ]);

    $evaluation = ResidencyEvaluation::create([
        'residency_application_id' => $application->id,
        'evaluator_id' => $user->id,
        'evaluation_date' => now(),
        'period' => 'Month 1',
        'score' => 15.5,
        'grade' => 'B+',
        'comments' => 'Good performance',
        'is_satisfactory' => true,
    ]);

    expect($evaluation->residency_application_id)->toBe($application->id);
    expect($evaluation->evaluator_id)->toBe($user->id);
    expect($evaluation->score)->toBe('15.50');
    expect($evaluation->is_satisfactory)->toBeTrue();
});

it('can create a residency program location', function () {
    $program = ResidencyProgram::factory()->create();
    $location = ResidencyLocation::factory()->create();

    $programLocation = ResidencyProgramLocation::create([
        'residency_program_id' => $program->id,
        'residency_location_id' => $location->id,
        'available_slots' => 10,
        'start_date' => now(),
        'end_date' => now()->addMonths(12),
        'is_active' => true,
    ]);

    expect($programLocation->residency_program_id)->toBe($program->id);
    expect($programLocation->residency_location_id)->toBe($location->id);
    expect($programLocation->available_slots)->toBe(10);
    expect($programLocation->is_active)->toBeTrue();
});

it('can establish relationships between models', function () {
    $member = Member::factory()->create();
    $program = ResidencyProgram::factory()->create();
    $location = ResidencyLocation::factory()->create();
    $user = User::factory()->create();

    $application = ResidencyApplication::create([
        'member_id' => $member->id,
        'residency_program_id' => $program->id,
        'residency_location_id' => $location->id,
        'status' => 'approved',
        'application_date' => now(),
        'approved_by' => $user->id,
    ]);

    $evaluation = ResidencyEvaluation::create([
        'residency_application_id' => $application->id,
        'evaluator_id' => $user->id,
        'evaluation_date' => now(),
        'period' => 'Month 1',
        'score' => 16.0,
        'is_satisfactory' => true,
    ]);

    // Test relationships
    expect($application->program)->toBeInstanceOf(ResidencyProgram::class);
    expect($application->member)->toBeInstanceOf(Member::class);
    expect($application->location)->toBeInstanceOf(ResidencyLocation::class);
    expect($application->approvedBy)->toBeInstanceOf(User::class);
    expect($application->evaluations)->toHaveCount(1);

    expect($evaluation->application)->toBeInstanceOf(ResidencyApplication::class);
    expect($evaluation->evaluator)->toBeInstanceOf(User::class);

    expect($program->applications)->toHaveCount(1);
    expect($program->evaluations)->toHaveCount(1);
});

it('can use soft deletes on programs and locations', function () {
    $program = ResidencyProgram::factory()->create();
    $location = ResidencyLocation::factory()->create();

    expect(ResidencyProgram::count())->toBe(1);
    expect(ResidencyLocation::count())->toBe(1);

    $program->delete();
    $location->delete();

    expect(ResidencyProgram::count())->toBe(0);
    expect(ResidencyLocation::count())->toBe(0);
    expect(ResidencyProgram::withTrashed()->count())->toBe(1);
    expect(ResidencyLocation::withTrashed()->count())->toBe(1);
});
