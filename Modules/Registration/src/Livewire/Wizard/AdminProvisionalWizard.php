<?php

namespace Modules\Registration\Livewire\Wizard;

use Modules\Registration\Livewire\Wizard\Concerns\AdminWizardRender;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Provisional\AcademicProfessionalStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Provisional\ChooseSubtypeStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Provisional\ContactInfoStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Provisional\IdentityAddressStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Provisional\PersonalInfoStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Provisional\ReviewSubmitStep;
use Modules\Registration\Livewire\Wizard\Steps\Admin\Provisional\UploadDocumentsStep;
use Spatie\LivewireWizard\Components\WizardComponent;

class AdminProvisionalWizard extends WizardComponent
{
    use AdminWizardRender;
    public ?int $editingRegistrationId = null;

    public function mount(?int $registrationId = null): void
    {
        $this->editingRegistrationId = $registrationId;
    }

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
        $state = [];

        // Store internal flags under the first step's state bucket
        $firstStepAlias = app('livewire.factory')->resolveComponentName(ChooseSubtypeStep::class);
        $state[$firstStepAlias] = [
            'internal_enabled' => true,
            'editing_registration_id' => $this->editingRegistrationId,
        ];

        return $state;
    }
}
