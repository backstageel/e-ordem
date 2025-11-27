<?php

namespace Modules\Registration\Livewire\Wizard\Concerns;

trait CustomWizardRender
{
    public function render()
    {
        $currentStepState = $this->getCurrentStepState();

        return view('livewire-wizard::wizard', [
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
     */
    public function goToNextStep(): void
    {
        if (!$this->currentStepName) {
            return;
        }

        $currentStepState = $this->getCurrentStepState();
        $this->nextStep($currentStepState);
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

