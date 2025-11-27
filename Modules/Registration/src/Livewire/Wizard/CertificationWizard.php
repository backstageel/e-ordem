<?php

namespace Modules\Registration\Livewire\Wizard;

use Modules\Registration\Livewire\Wizard\Steps\Certification\AcademicProfessionalStep;
use Modules\Registration\Livewire\Wizard\Steps\Certification\ChooseCategoryStep;
use Modules\Registration\Livewire\Wizard\Steps\Certification\ContactInfoStep;
use Modules\Registration\Livewire\Wizard\Steps\Certification\IdentityAddressStep;
use Modules\Registration\Livewire\Wizard\Steps\Certification\PersonalInfoStep;
use Modules\Registration\Livewire\Wizard\Steps\Certification\ReviewSubmitStep;
use Modules\Registration\Livewire\Wizard\Steps\Certification\UploadDocumentsStep;
use Spatie\LivewireWizard\Components\WizardComponent;

class CertificationWizard extends WizardComponent
{
    public function steps(): array
    {
        return [
            ChooseCategoryStep::class,
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
