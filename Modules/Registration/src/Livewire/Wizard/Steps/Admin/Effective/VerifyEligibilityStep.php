<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Admin\Effective;

use App\Models\ExamResult;
use Spatie\LivewireWizard\Components\StepComponent;

class VerifyEligibilityStep extends StepComponent
{
    public string $registration_number = '';

    public string $exam_result_id = '';

    public ?ExamResult $examResult = null;

    public bool $eligible = false;

    public function mount(): void
    {
        $seed = $this->state()->forStepClass(self::class);
        $this->registration_number = (string) ($seed['registration_number'] ?? '');
        $this->exam_result_id = (string) ($seed['exam_result_id'] ?? '');
    }

    public function verify(): void
    {
        $this->validate([
            'registration_number' => ['required', 'string', 'max:255'],
            'exam_result_id' => ['required', 'string', 'max:255'],
        ]);

        // Verify exam result exists and is approved
        $this->examResult = ExamResult::where('id', $this->exam_result_id)
            ->where('registration_number', $this->registration_number)
            ->where('status', 'approved')
            ->first();

        if (! $this->examResult) {
            $this->addError('exam_result_id', 'Resultado do exame não encontrado ou não aprovado.');
            $this->eligible = false;

            return;
        }

        // Check if already registered
        $existing = \Modules\Registration\Models\Registration::where('member_id', $this->examResult->member_id)
            ->where('type', 'effective')
            ->where('status', '!=', \App\Enums\RegistrationStatus::REJECTED)
            ->first();

        if ($existing) {
            $this->addError('registration_number', 'Já possui uma inscrição efetiva.');
            $this->eligible = false;

            return;
        }

        $this->eligible = true;
        $this->nextStep();
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.admin.effective.verify-eligibility');
    }
}
