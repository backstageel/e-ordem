<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Admin\Certification;

use Modules\Registration\Models\RegistrationType;
use Spatie\LivewireWizard\Components\StepComponent;

class ChooseCategoryStep extends StepComponent
{
    public int $category = 0;

    public function mount(): void
    {
        $seed = $this->state()->forStepClass(self::class);
        $this->category = (int) ($seed['category'] ?? 0);
    }

    public function getCategoriesProperty(): array
    {
        return [
            1 => [
                'code' => 'CERT-1',
                'name' => 'Moçambicanos formados em Moçambique',
                'description' => 'Categoria 1: Moçambicanos formados em instituições moçambicanas',
                'documents_count' => 7,
                'fee' => 8300.00,
            ],
            2 => [
                'code' => 'CERT-2',
                'name' => 'Moçambicanos formados no estrangeiro',
                'description' => 'Categoria 2: Moçambicanos formados em instituições estrangeiras',
                'documents_count' => 13,
                'fee' => 10800.00,
            ],
            3 => [
                'code' => 'CERT-3',
                'name' => 'Estrangeiros formados em Moçambique',
                'description' => 'Categoria 3: Médicos estrangeiros formados em instituições moçambicanas',
                'documents_count' => 9,
                'fee' => 8300.00,
            ],
        ];
    }

    public function selectCategory(int $category): void
    {
        $this->category = $category;
        $this->validate([
            'category' => ['required', 'integer', 'in:1,2,3'],
        ]);

        // Get registration type by code
        $categoryData = $this->categories[$category];
        $registrationType = RegistrationType::where('code', $categoryData['code'])->first();

        if (! $registrationType) {
            $this->addError('category', 'Tipo de inscrição não encontrado para a categoria selecionada.');

            return;
        }

        $this->nextStep();
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.admin.certification.choose-category');
    }
}
