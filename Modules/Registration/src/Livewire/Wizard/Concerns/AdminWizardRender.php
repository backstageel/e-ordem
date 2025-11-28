<?php

namespace Modules\Registration\Livewire\Wizard\Concerns;

use Livewire\Attributes\On;

trait AdminWizardRender
{
    public function render()
    {
        $currentStepState = $this->getCurrentStepState();

        return view('registration::livewire-wizard.admin-wizard', [
            'currentStepState' => $currentStepState,
            'currentStepName' => $this->currentStepName,
        ]);
    }

    /**
     * Navigate to previous step without requiring currentStepState parameter.
     */
    public function goToPreviousStep(): void
    {
        if (!$this->currentStepName) {
            return;
        }

        $currentStepState = $this->getCurrentStepState();
        $this->previousStep($currentStepState);
    }

    /**
     * Navigate to next step without requiring currentStepState parameter.
     * This will trigger the step's saveAndNext or continue method if available.
     */
    public function goToNextStep(): void
    {
        if (!$this->currentStepName) {
            return;
        }

        // Dispatch event to current step to call saveAndNext or continue
        // The step will handle validation and call nextStep() internally
        $this->dispatch('wizard-next-step')->to($this->currentStepName);
    }

    /**
     * Navigate to a specific step without requiring currentStepState parameter.
     */
    public function goToStep(string $stepName): void
    {
        if (!$this->currentStepName) {
            return;
        }

        $currentStepState = $this->getCurrentStepState();
        $this->showStep($stepName, $currentStepState);
    }

}

