<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Admin\Effective;

use Modules\Registration\Models\RegistrationType;
use Spatie\LivewireWizard\Components\StepComponent;

class ReviewSubmitStep extends StepComponent
{
    public function summary(): array
    {
        $verifyState = (array) ($this->state()->forStepClass(VerifyEligibilityStep::class) ?? []);
        $grade = (string) ($this->state()->forStepClass(SelectGradeStep::class)['grade'] ?? '');
        $registrationType = $grade ? RegistrationType::where('grade', $grade)->first() : null;
        $uploads = (array) ($this->state()->forStepClass(UploadDocumentsStep::class)['uploads'] ?? []);

        $examResult = \App\Models\ExamResult::find($verifyState['exam_result_id'] ?? null);

        return [
            'grade' => $grade,
            'grade_name' => $registrationType?->name,
            'type' => $registrationType?->name,
            'exam_result' => $examResult,
            'registration_number' => $verifyState['registration_number'] ?? null,
            'uploads' => $uploads,
            'fee' => $registrationType?->fee ?? 0,
        ];
    }

    public function submit(): mixed
    {
        $verifyState = (array) $this->state()->forStepClass(VerifyEligibilityStep::class);
        $grade = (string) ($this->state()->forStepClass(SelectGradeStep::class)['grade'] ?? '');
        $registrationType = $grade ? RegistrationType::where('grade', $grade)->first() : null;

        if (! $registrationType) {
            $this->addError('submit', 'Tipo de inscrição não encontrado.');

            return null;
        }

        $uploads = (array) ($this->state()->forStepClass(UploadDocumentsStep::class)['uploads'] ?? []);

        // TODO: Create EffectiveRegistrationAction to handle effective registration creation
        // For now, redirect to index page
        // $registration = $effectiveRegistrationAction->execute(...);

        session(['registration_number' => 'EFET-'.now()->year.'-0001']); // Temporary

        return redirect()->route('admin.registrations.index')
            ->with('success', 'Inscrição efetiva criada com sucesso!');
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.admin.effective.review-submit', [
            'summary' => $this->summary(),
        ]);
    }
}
