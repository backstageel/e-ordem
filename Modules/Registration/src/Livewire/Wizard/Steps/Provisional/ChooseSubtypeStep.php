<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Provisional;

use App\Enums\RegistrationSubtype;
use Modules\Registration\Models\RegistrationType;
use Spatie\LivewireWizard\Components\StepComponent;

class ChooseSubtypeStep extends StepComponent
{
    public int $subtype = 0;

    public function mount(): void
    {
        $seed = $this->state()->forStepClass(self::class);
        $this->subtype = (int) ($seed['subtype'] ?? 0);
    }

    public function getSubtypesProperty(): array
    {
        $subtypes = [];
        foreach (RegistrationSubtype::cases() as $subtype) {
            $subtypes[$subtype->value] = [
                'value' => $subtype->value,
                'label' => $subtype->label(),
                'duration_days' => $subtype->durationDays(),
                'is_renewable' => $subtype->isRenewable(),
                'max_renewals' => $subtype->maxRenewals(),
                'is_exempt' => $subtype->isExemptFromCommonRequirements(),
            ];
        }

        return $subtypes;
    }

    public function selectSubtype(int $subtype): void
    {
        // Set subtype first
        $this->subtype = $subtype;

        // Clear any previous errors
        $this->resetErrorBag('subtype');

        // Get valid subtype values
        $validSubtypes = array_keys($this->subtypes);

        // Validate the selection
        if (! in_array($subtype, $validSubtypes, true)) {
            $this->addError('subtype', 'Subtipo inválido selecionado.');
            $this->subtype = 0;

            return;
        }

        // Get registration type by subtype
        $registrationType = RegistrationType::where('subtype_number', $subtype)->first();

        if (! $registrationType) {
            $this->addError('subtype', 'Tipo de inscrição não encontrado para o subtipo selecionado.');
            $this->subtype = 0; // Reset on error

            return;
        }

        // Don't advance automatically - user must click "Continuar" button
        // State is automatically saved by LivewireWizard
    }

    public function continue(): void
    {
        if ($this->subtype <= 0) {
            $this->addError('subtype', 'Por favor, selecione um subtipo antes de continuar.');

            return;
        }

        // Re-validate and advance (state should already be saved from selectSubtype)
        $validSubtypes = array_keys($this->subtypes);
        if (! in_array($this->subtype, $validSubtypes, true)) {
            $this->addError('subtype', 'Subtipo inválido. Por favor, selecione novamente.');
            $this->subtype = 0;

            return;
        }

        // Get registration type by subtype
        $registrationType = RegistrationType::where('subtype_number', $this->subtype)->first();

        if (! $registrationType) {
            $this->addError('subtype', 'Tipo de inscrição não encontrado para o subtipo selecionado.');

            return;
        }

        // Advance to next step
        $this->nextStep();
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.provisional.choose-subtype');
    }
}
