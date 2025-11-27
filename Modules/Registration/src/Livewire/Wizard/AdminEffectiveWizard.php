<?php

namespace Modules\Registration\Livewire\Wizard;

use Modules\Registration\Livewire\Wizard\Steps\Admin\Effective\ReviewSubmitStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Effective\SelectGradeStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Effective\UploadDocumentsStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Effective\VerifyEligibilityStep;
use Spatie\LivewireWizard\Components\WizardComponent;

class AdminEffectiveWizard extends WizardComponent
{
    public ?int $editingRegistrationId = null;

    public function mount(?int $registrationId = null): void
    {
        $this->editingRegistrationId = $registrationId;
    }

    public function steps(): array
    {
        return [
            VerifyEligibilityStep::class,
            SelectGradeStep::class,
            UploadDocumentsStep::class,
            ReviewSubmitStep::class,
        ];
    }

    public function initialState(): ?array
    {
        $state = [];

        // Store internal flags under the first step's state bucket
        $firstStepAlias = app('livewire.factory')->resolveComponentName(VerifyEligibilityStep::class);
        $state[$firstStepAlias] = [
            'internal_enabled' => true,
            'editing_registration_id' => $this->editingRegistrationId,
        ];

        return $state;
    }
}
