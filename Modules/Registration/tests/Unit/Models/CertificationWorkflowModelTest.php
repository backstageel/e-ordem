<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Models\CertificationWorkflow;
use Modules\Registration\Models\Registration;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->registration = Registration::factory()->create([
        'type' => 'certification',
    ]);

    $this->workflow = CertificationWorkflow::factory()->create([
        'registration_id' => $this->registration->id,
        'current_step' => 1,
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

describe('Steps', function () {
    it('gets all workflow steps', function () {
        $steps = CertificationWorkflow::getSteps();
        expect($steps)->toHaveCount(9);
        expect($steps[1])->toBe('Submissão Online de Documentos');
    });

    it('gets current step label', function () {
        $this->workflow->update(['current_step' => 3]);
        expect($this->workflow->getCurrentStepLabel())->toBe('Convocação para Exame');
    });

    it('moves to next step', function () {
        $this->workflow->update(['current_step' => 1]);
        $this->workflow->moveToNextStep();

        expect($this->workflow->current_step)->toBe(2);
    });

    it('does not move beyond step 9', function () {
        $this->workflow->update(['current_step' => 9]);
        $this->workflow->moveToNextStep();

        expect($this->workflow->current_step)->toBe(9);
    });

    it('moves to specific step', function () {
        $this->workflow->moveToStep(5);

        expect($this->workflow->current_step)->toBe(5);
    });

    it('does not move to invalid step', function () {
        $this->workflow->update(['current_step' => 3]);
        $this->workflow->moveToStep(99);

        expect($this->workflow->current_step)->toBe(3);
    });
});

describe('Completion', function () {
    it('completes workflow', function () {
        $this->workflow->complete();

        expect($this->workflow->current_step)->toBe(9);
        expect($this->workflow->completed_at)->not->toBeNull();
    });

    it('checks if workflow is completed', function () {
        $this->workflow->update([
            'current_step' => 9,
            'completed_at' => now(),
        ]);

        expect($this->workflow->isCompleted())->toBeTrue();
    });

    it('checks if step is completed', function () {
        $this->workflow->update(['current_step' => 5]);

        expect($this->workflow->isStepCompleted(3))->toBeTrue();
        expect($this->workflow->isStepCompleted(5))->toBeFalse();
    });

    it('checks if workflow is at specific step', function () {
        $this->workflow->update(['current_step' => 5]);

        expect($this->workflow->isAtStep(5))->toBeTrue();
        expect($this->workflow->isAtStep(3))->toBeFalse();
    });
});

describe('Decisions and Data', function () {
    it('adds decision to workflow', function () {
        $this->workflow->addDecision(2, 'approved', 'Test notes', 1);

        expect($this->workflow->decisions)->toHaveCount(1);
        expect($this->workflow->decisions[0]['decision'])->toBe('approved');
    });

    it('sets step data', function () {
        $this->workflow->setStepData(3, ['exam_date' => '2025-01-15']);

        expect($this->workflow->getStepData(3))->toBe(['exam_date' => '2025-01-15']);
    });

    it('gets step data', function () {
        $this->workflow->setStepData(2, ['reviewed' => true]);
        $data = $this->workflow->getStepData(2);

        expect($data)->toBe(['reviewed' => true]);
    });

    it('returns null for non-existent step data', function () {
        expect($this->workflow->getStepData(99))->toBeNull();
    });
});

describe('History', function () {
    it('adds history entry when moving steps', function () {
        $this->workflow->update(['current_step' => 1]);
        $this->workflow->moveToNextStep();

        $history = $this->workflow->getHistory();
        expect($history)->toHaveCount(1);
        expect($history[0]['from_step'])->toBe(1);
        expect($history[0]['to_step'])->toBe(2);
    });

    it('gets workflow history', function () {
        $this->workflow->update(['current_step' => 1]);
        $this->workflow->moveToStep(5);

        $history = $this->workflow->getHistory();
        expect($history)->toHaveCount(1);
    });
});
