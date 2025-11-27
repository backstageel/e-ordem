<?php

use App\Enums\WorkflowStatus;
use App\Enums\WorkflowStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use Modules\Registration\Models\RegistrationWorkflow;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->registrationType = RegistrationType::factory()->create([
        'workflow_steps' => [1, 2, 3, 4, 5],
    ]);

    $this->registration = Registration::factory()->create([
        'registration_type_id' => $this->registrationType->id,
    ]);

    $this->workflow = RegistrationWorkflow::factory()->create([
        'registration_id' => $this->registration->id,
        'current_step' => WorkflowStep::SUBMITTED,
        'status' => WorkflowStatus::PENDING,
    ]);
});

describe('Relationships', function () {
    it('belongs to a registration', function () {
        expect($this->workflow->registration)->toBeInstanceOf(Registration::class);
        expect($this->workflow->registration_id)->toBe($this->registration->id);
    });

    it('belongs to assigned user', function () {
        $user = User::factory()->create();
        $this->workflow->update(['assigned_to' => $user->id]);

        expect($this->workflow->assignedTo)->toBeInstanceOf(User::class);
        expect($this->workflow->assigned_to)->toBe($user->id);
    });
});

describe('Status Checks', function () {
    it('checks if workflow is pending', function () {
        $this->workflow->update(['status' => WorkflowStatus::PENDING]);
        expect($this->workflow->isPending())->toBeTrue();
    });

    it('checks if workflow is in progress', function () {
        $this->workflow->update(['status' => WorkflowStatus::IN_PROGRESS]);
        expect($this->workflow->isInProgress())->toBeTrue();
    });

    it('checks if workflow is completed', function () {
        $this->workflow->update(['status' => WorkflowStatus::COMPLETED]);
        expect($this->workflow->isCompleted())->toBeTrue();
    });

    it('checks if workflow is cancelled', function () {
        $this->workflow->update(['status' => WorkflowStatus::CANCELLED]);
        expect($this->workflow->isCancelled())->toBeTrue();
    });
});

describe('Scopes', function () {
    it('filters pending workflows', function () {
        RegistrationWorkflow::factory()->create(['status' => WorkflowStatus::PENDING]);
        RegistrationWorkflow::factory()->create(['status' => WorkflowStatus::IN_PROGRESS]);

        expect(RegistrationWorkflow::pending()->count())->toBe(1);
    });

    it('filters in progress workflows', function () {
        RegistrationWorkflow::factory()->create(['status' => WorkflowStatus::IN_PROGRESS]);
        RegistrationWorkflow::factory()->create(['status' => WorkflowStatus::PENDING]);

        expect(RegistrationWorkflow::inProgress()->count())->toBe(1);
    });

    it('filters completed workflows', function () {
        RegistrationWorkflow::factory()->create(['status' => WorkflowStatus::COMPLETED]);
        RegistrationWorkflow::factory()->create(['status' => WorkflowStatus::PENDING]);

        expect(RegistrationWorkflow::completed()->count())->toBe(1);
    });

    it('filters cancelled workflows', function () {
        RegistrationWorkflow::factory()->create(['status' => WorkflowStatus::CANCELLED]);
        RegistrationWorkflow::factory()->create(['status' => WorkflowStatus::PENDING]);

        expect(RegistrationWorkflow::cancelled()->count())->toBe(1);
    });

    it('filters workflows assigned to user', function () {
        $user = User::factory()->create();
        RegistrationWorkflow::factory()->create(['assigned_to' => $user->id]);
        RegistrationWorkflow::factory()->create(['assigned_to' => null]);

        expect(RegistrationWorkflow::assignedTo($user->id)->count())->toBe(1);
    });
});

describe('Workflow Actions', function () {
    it('moves to next step', function () {
        $this->workflow->update(['current_step' => WorkflowStep::SUBMITTED]);
        $this->workflow->moveToNextStep();

        expect($this->workflow->current_step)->not->toBe(WorkflowStep::SUBMITTED);
    });

    it('completes workflow', function () {
        $this->workflow->complete();

        expect($this->workflow->status)->toBe(WorkflowStatus::COMPLETED);
        expect($this->workflow->completed_at)->not->toBeNull();
        expect($this->workflow->current_step)->toBe(WorkflowStep::COMPLETED);
    });

    it('cancels workflow with reason', function () {
        $this->workflow->cancel('Test reason');

        expect($this->workflow->status)->toBe(WorkflowStatus::CANCELLED);
        expect($this->workflow->completed_at)->not->toBeNull();
        expect($this->workflow->notes)->toContain('Test reason');
    });

    it('assigns workflow to user', function () {
        $user = User::factory()->create();
        $this->workflow->assignTo($user);

        expect($this->workflow->assigned_to)->toBe($user->id);
        expect($this->workflow->status)->toBe(WorkflowStatus::IN_PROGRESS);
    });

    it('adds decision to workflow', function () {
        $this->workflow->addDecision('step1', 'approved', 'Test notes');

        expect($this->workflow->decisions)->toHaveCount(1);
        expect($this->workflow->decisions[0]['decision'])->toBe('approved');
    });
});

describe('Workflow Steps', function () {
    it('gets workflow steps from registration type', function () {
        $steps = $this->workflow->getWorkflowSteps();
        expect($steps)->toBeArray();
    });
});

describe('Duration', function () {
    it('calculates workflow duration in days', function () {
        $this->workflow->update([
            'started_at' => now()->subDays(5),
            'completed_at' => now(),
        ]);

        expect($this->workflow->getDuration())->toBe(5);
    });

    it('calculates workflow duration in hours', function () {
        $this->workflow->update([
            'started_at' => now()->subHours(10),
            'completed_at' => now(),
        ]);

        expect($this->workflow->getDurationInHours())->toBe(10);
    });

    it('returns null if workflow not completed', function () {
        $this->workflow->update([
            'started_at' => now()->subDays(5),
            'completed_at' => null,
        ]);

        expect($this->workflow->getDuration())->toBeNull();
    });
});

describe('Labels', function () {
    it('gets status label', function () {
        expect($this->workflow->getStatusLabel())->toBeString();
    });

    it('gets status badge color', function () {
        expect($this->workflow->getStatusBadgeColor())->toBeString();
    });

    it('gets current step label', function () {
        expect($this->workflow->getCurrentStepLabel())->toBeString();
    });
});
