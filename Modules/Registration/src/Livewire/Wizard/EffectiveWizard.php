<?php

namespace Modules\Registration\Livewire\Wizard;

use Modules\Registration\Livewire\Wizard\Steps\Effective\ReviewSubmitStep;
use Modules\Registration\Livewire\Wizard\Steps\Effective\SelectGradeStep;
use Modules\Registration\Livewire\Wizard\Steps\Effective\UploadDocumentsStep;
use Modules\Registration\Livewire\Wizard\Steps\Effective\VerifyEligibilityStep;
use Spatie\LivewireWizard\Components\WizardComponent;

class EffectiveWizard extends WizardComponent
{
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
        return null;
    }
}
