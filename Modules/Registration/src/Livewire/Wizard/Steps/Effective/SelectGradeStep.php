<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Effective;

use Modules\Registration\Models\RegistrationType;
use Spatie\LivewireWizard\Components\StepComponent;

class SelectGradeStep extends StepComponent
{
    public string $grade = '';

    public function mount(): void
    {
        $seed = $this->state()->forStepClass(self::class);
        $this->grade = (string) ($seed['grade'] ?? '');

        // Auto-select grade based on exam result and experience
        if (empty($this->grade)) {
            $examResult = $this->getExamResult();
            if ($examResult) {
                // Determine grade based on specialty and experience
                // TODO: Implement grade determination logic
                $this->grade = 'B'; // Default for now
            }
        }
    }

    public function getGradesProperty(): array
    {
        return [
            'A' => [
                'code' => 'EFET-A',
                'name' => 'Grau A - Especialistas',
                'description' => 'Médicos especialistas nacionais',
                'subgrades' => ['A1', 'A2', 'A3'],
            ],
            'B' => [
                'code' => 'EFET-B',
                'name' => 'Grau B - Clínicos Gerais',
                'description' => 'Médicos de clínica geral nacionais',
                'subgrades' => ['B1', 'B2', 'B3', 'B4'],
            ],
            'C' => [
                'code' => 'EFET-C',
                'name' => 'Grau C - Dentistas Gerais',
                'description' => 'Dentistas gerais nacionais',
                'subgrades' => ['C1', 'C2', 'C3', 'C4'],
            ],
        ];
    }

    public function selectGrade(string $grade): void
    {
        $this->grade = $grade;
        $this->validate([
            'grade' => ['required', 'string', 'in:A,B,C'],
        ]);

        // Get registration type by grade
        $registrationType = RegistrationType::where('grade', $grade)->first();

        if (! $registrationType) {
            $this->addError('grade', 'Tipo de inscrição não encontrado para o grau selecionado.');

            return;
        }

        $this->nextStep();
    }

    protected function getExamResult()
    {
        $verifyStep = $this->state()->forStepClass(VerifyEligibilityStep::class);
        $examResultId = $verifyStep['exam_result_id'] ?? null;

        if (! $examResultId) {
            return null;
        }

        return \App\Models\ExamResult::find($examResultId);
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.effective.select-grade');
    }
}
