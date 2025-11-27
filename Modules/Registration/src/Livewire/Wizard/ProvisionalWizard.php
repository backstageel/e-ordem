<?php

namespace Modules\Registration\Livewire\Wizard;

use Modules\Registration\Livewire\Wizard\Steps\Provisional\AcademicProfessionalStep;
use Modules\Registration\Livewire\Wizard\Steps\Provisional\ChooseSubtypeStep;
use Modules\Registration\Livewire\Wizard\Steps\Provisional\ContactInfoStep;
use Modules\Registration\Livewire\Wizard\Steps\Provisional\IdentityAddressStep;
use Modules\Registration\Livewire\Wizard\Steps\Provisional\PersonalInfoStep;
use Modules\Registration\Livewire\Wizard\Steps\Provisional\ReviewSubmitStep;
use Modules\Registration\Livewire\Wizard\Steps\Provisional\UploadDocumentsStep;
use Spatie\LivewireWizard\Components\WizardComponent;

class ProvisionalWizard extends WizardComponent
{
    public function steps(): array
    {
        return [
            ChooseSubtypeStep::class,
            ContactInfoStep::class,
            PersonalInfoStep::class,
            IdentityAddressStep::class,
            AcademicProfessionalStep::class,
            UploadDocumentsStep::class,
            ReviewSubmitStep::class,
        ];
    }

    public function initialState(): ?array
    {
        return null;
    }
}
