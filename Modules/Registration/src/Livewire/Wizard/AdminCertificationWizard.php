<?php

namespace Modules\Registration\Livewire\Wizard;

use Modules\Registration\Livewire\Wizard\Concerns\CustomWizardRender;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Certification\AcademicProfessionalStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Certification\ChooseCategoryStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Certification\ContactInfoStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Certification\IdentityAddressStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Certification\PersonalInfoStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Certification\ReviewSubmitStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Certification\UploadDocumentsStep;
use Spatie\LivewireWizard\Components\WizardComponent;

class AdminCertificationWizard extends WizardComponent
{
    use CustomWizardRender;
    public ?int $editingRegistrationId = null;

    public function mount(?int $registrationId = null): void
    {
        $this->editingRegistrationId = $registrationId;
    }

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
        $state = [];

        // Store internal flags under the first step's state bucket
        $firstStepAlias = app('livewire.factory')->resolveComponentName(ChooseCategoryStep::class);
        $state[$firstStepAlias] = [
            'internal_enabled' => true,
            'editing_registration_id' => $this->editingRegistrationId,
        ];

        return $state;
    }
}
