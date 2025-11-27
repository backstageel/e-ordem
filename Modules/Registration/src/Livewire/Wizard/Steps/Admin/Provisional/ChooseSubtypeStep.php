<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Admin\Provisional;

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
        $this->subtype = $subtype;
        $this->validate([
            'subtype' => ['required', 'integer', 'in:'.implode(',', array_keys($this->subtypes))],
        ]);

        // Get registration type by subtype
        $registrationType = RegistrationType::where('subtype_number', $subtype)->first();

        if (! $registrationType) {
            $this->addError('subtype', 'Tipo de inscrição não encontrado para o subtipo selecionado.');

            return;
        }

        $this->nextStep();
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.admin.provisional.choose-subtype');
    }
}
