<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Admin\Certification;

use Modules\Registration\Models\RegistrationType;
use Modules\Registration\Models\TemporaryRegistration;
use Spatie\LivewireWizard\Components\StepComponent;

class ContactInfoStep extends StepComponent
{
    public string $email = '';

    public string $phone = '';

    public bool $resuming = false;

    public function mount(): void
    {
        $seed = $this->state()->forStepClass(self::class);
        $this->email = (string) ($seed['email'] ?? '');
        $this->phone = (string) ($seed['phone'] ?? '');

        // Check for existing temporary registration (resume)
        if ($this->email || $this->phone) {
            $existing = TemporaryRegistration::query()
                ->where(function ($query) {
                    if ($this->email) {
                        $query->where('email', $this->email);
                    }
                    if ($this->phone) {
                        $query->orWhere('phone', $this->phone);
                    }
                })
                ->notExpired()
                ->latest('id')
                ->first();

            if ($existing && is_array($existing->step_data) && count($existing->step_data) > 0) {
                $this->resuming = true;
            }
        }
    }

    public function continue(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^\+258[2-8][0-9]{7,8}$/'],
        ], [
            'phone.required' => 'O número de telefone é obrigatório.',
            'phone.regex' => 'O número de telefone deve estar no formato +258821234567 ou +2588212345678 (código do país + operadora 2-8 + 7 ou 8 dígitos)',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'email.unique' => 'Este email já está registado no sistema.',
        ]);

        // Get selected category and registration type
        $category = (int) ($this->state()->forStepClass(ChooseCategoryStep::class)['category'] ?? 0);
        $categoryData = $this->getCategoriesProperty()[$category] ?? null;
        $registrationType = $categoryData ? RegistrationType::where('code', $categoryData['code'])->first() : null;

        // Create or update temporary registration
        $temp = TemporaryRegistration::updateOrCreate(
            [
                'email' => $this->email,
                'phone' => $this->phone,
            ],
            [
                'registration_type' => $registrationType?->code ?? null,
                'current_step' => 2,
                'expires_at' => now()->addHours(24),
            ]
        );

        // Store step data for resume
        $temp->setStepData(1, ['category' => $category]);
        $temp->setStepData(2, [
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->nextStep();
    }

    protected function getCategoriesProperty(): array
    {
        $chooseCategoryStep = new ChooseCategoryStep;

        return $chooseCategoryStep->getCategoriesProperty();
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.admin.certification.contact-info');
    }
}
