<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Effective;

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
        // ExamResult relates to Member through: ExamResult -> ExamApplication -> User -> Person -> Member
        $this->examResult = ExamResult::where('id', $this->exam_result_id)
            ->where('decision', 'aprovado')
            ->with(['application.user.person.member'])
            ->first();

        if (! $this->examResult) {
            $this->addError('exam_result_id', 'Resultado do exame não encontrado ou não aprovado.');
            $this->eligible = false;

            return;
        }

        // Get member through relationships
        $member = $this->examResult->application?->user?->person?->member;

        if (! $member) {
            $this->addError('exam_result_id', 'Membro não encontrado para este resultado de exame.');
            $this->eligible = false;

            return;
        }

        // Verify registration number matches
        if ($member->registration_number !== $this->registration_number) {
            $this->addError('registration_number', 'O número de inscrição não corresponde ao resultado do exame.');
            $this->eligible = false;

            return;
        }

        // Check if already registered
        $existing = \Modules\Registration\Models\Registration::where('member_id', $member->id)
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
        return view('registration::livewire.registration.wizard.steps.effective.verify-eligibility');
    }
}
