<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Certification;

use Livewire\Attributes\On;
use Modules\Registration\Models\RegistrationType;
use Modules\Registration\Models\TemporaryRegistration;
use Modules\Registration\Services\EligibilityValidationService;
use Spatie\LivewireWizard\Components\StepComponent;

class AcademicProfessionalStep extends StepComponent
{
    public array $form = [];

    public function mount(): void
    {
        $this->form = (array) ($this->state()->forStepClass(self::class)['form'] ?? []);

        if (empty($this->form)) {
            $email = (string) (($this->state()->forStepClass(ContactInfoStep::class)['email'] ?? ''));
            $phone = (string) (($this->state()->forStepClass(ContactInfoStep::class)['phone'] ?? ''));
            if ($email || $phone) {
                $temp = TemporaryRegistration::query()
                    ->where('email', $email)
                    ->orWhere('phone', $phone)
                    ->notExpired()
                    ->latest('id')
                    ->first();
                if ($temp) {
                    $stored = $temp->getStepData(5) ?? [];
                    if (! empty($stored)) {
                        $this->form = $stored;
                    }
                }
            }
        }
    }

    public function saveAndNext(): void
    {
        $this->validate($this->rules(), $this->messages());

        // Validate eligibility based on training country (now available in this step)
        $category = (int) ($this->state()->forStepClass(ChooseCategoryStep::class)['category'] ?? 0);
        $categoryData = (new ChooseCategoryStep)->getCategoriesProperty()[$category] ?? null;
        $registrationType = $categoryData ? RegistrationType::where('code', $categoryData['code'])->first() : null;

        if ($registrationType) {
            $eligibilityService = app(EligibilityValidationService::class);

            // Get data from previous steps
            $personalData = (array) ($this->state()->forStepClass(PersonalInfoStep::class)['form'] ?? []);

            $eligibilityData = [
                'nationality_id' => $personalData['nationality_id'] ?? null,
                'birth_country_id' => $personalData['birth_country_id'] ?? null,
                'birth_date' => $personalData['birth_date'] ?? null,
                'training_country_id' => $this->form['university_country_id'] ?? null, // Now available!
            ];

            $validation = $eligibilityService->validateForCertification($eligibilityData, $category);

            if (! $validation['eligible']) {
                foreach ($validation['issues'] as $issue) {
                    // Check if the issue is related to training country
                    if (str_contains($issue, 'formação')) {
                        $this->addError('form.university_country_id', $issue);
                    } else {
                        // Other issues (nationality, age) should have been caught in PersonalInfoStep
                        // But we show them here as well for completeness
                        $this->addError('form.degree_type', $issue);
                    }
                }

                return;
            }
        }

        $email = (string) (($this->state()->forStepClass(ContactInfoStep::class)['email'] ?? ''));
        $phone = (string) (($this->state()->forStepClass(ContactInfoStep::class)['phone'] ?? ''));
        $temp = TemporaryRegistration::firstOrCreate([
            'email' => $email ?: null,
            'phone' => $phone ?: null,
        ], [
            'current_step' => 5,
            'expires_at' => now()->addHours(24),
        ]);
        $temp->setStepData(5, $this->form);

        // Notify wizard that step is completed
        $this->dispatch('step-completed')->to($this->wizardClassName);
    }

    /**
     * Handle wizard next button click - calls saveAndNext.
     */
    #[On('wizard-next-step')]
    public function handleWizardNext(): void
    {
        $this->saveAndNext();
    }

    protected function rules(): array
    {
        $currentYear = (int) date('Y');
        $minYear = $currentYear - 50; // Allow up to 50 years ago
        $maxYear = $currentYear + 1; // Allow next year for start year

        return [
            // Primeira linha: Licenciatura em, Instituição, Ano início, Ano término
            'form.degree_type' => ['required', 'string', 'in:Medicina Geral,Medicina Dentária'],
            'form.university' => ['required', 'string', 'max:255'],
            'form.university_start_year' => ['required', 'integer', 'min:'.$minYear, 'max:'.$maxYear],
            'form.university_end_year' => [
                'required',
                'integer',
                'min:'.$minYear,
                'max:'.$currentYear,
                function ($attribute, $value, $fail) {
                    $startYear = (int) ($this->form['university_start_year'] ?? 0);
                    if ($startYear > 0 && (int) $value < $startYear) {
                        $fail('O ano de término deve ser igual ou posterior ao ano de início.');
                    }
                },
            ],

            // Segunda linha: País, Cidade/Distrito, Nota final
            'form.university_country_id' => ['required', 'integer', 'exists:countries,id'],
            'form.university_city_district' => ['required', 'string', 'max:255'],
            'form.university_final_grade' => ['required', 'numeric', 'min:0', 'max:20'],

            // Terceira linha: Instituição Ensino Médio, País, Cidade/Distrito
            'form.high_school_institution' => ['required', 'string', 'max:255'],
            'form.high_school_country_id' => ['required', 'integer', 'exists:countries,id'],
            'form.high_school_city_district' => ['required', 'string', 'max:255'],

            // Quarta linha: Ano conclusão, Nota conclusão
            'form.high_school_completion_year' => ['required', 'integer', 'min:'.$minYear, 'max:'.$currentYear],
            'form.high_school_final_grade' => ['required', 'numeric', 'min:0', 'max:20'],
        ];
    }

    protected function messages(): array
    {
        return [
            'form.degree_type.required' => 'O tipo de licenciatura é obrigatório.',
            'form.degree_type.in' => 'O tipo de licenciatura deve ser "Medicina Geral" ou "Medicina Dentária".',
            'form.university.required' => 'A instituição onde concluiu o Ensino Superior é obrigatória.',
            'form.university_start_year.required' => 'O ano de início é obrigatório.',
            'form.university_start_year.min' => 'O ano de início deve ser após '.((int) date('Y') - 50).'.',
            'form.university_start_year.max' => 'O ano de início não pode ser futuro.',
            'form.university_end_year.required' => 'O ano de término é obrigatório.',
            'form.university_end_year.min' => 'O ano de término deve ser após '.((int) date('Y') - 50).'.',
            'form.university_end_year.max' => 'O ano de término não pode ser futuro.',
            'form.university_end_year.gte' => 'O ano de término deve ser igual ou posterior ao ano de início.',
            'form.university_country_id.required' => 'O país onde concluiu a Licenciatura é obrigatório.',
            'form.university_city_district.required' => 'A cidade/distrito onde concluiu a Licenciatura é obrigatório.',
            'form.university_final_grade.required' => 'A nota final da Licenciatura é obrigatória.',
            'form.university_final_grade.numeric' => 'A nota final da Licenciatura deve ser um número.',
            'form.university_final_grade.min' => 'A nota final da Licenciatura deve ser no mínimo 0.',
            'form.university_final_grade.max' => 'A nota final da Licenciatura deve ser no máximo 20.',
            'form.high_school_institution.required' => 'A instituição onde concluiu o Ensino Médio é obrigatória.',
            'form.high_school_country_id.required' => 'O país onde concluiu o Ensino Médio é obrigatório.',
            'form.high_school_city_district.required' => 'A cidade/distrito onde concluiu o Ensino Médio é obrigatório.',
            'form.high_school_completion_year.required' => 'O ano de conclusão do Ensino Médio é obrigatório.',
            'form.high_school_completion_year.min' => 'O ano de conclusão deve ser após '.((int) date('Y') - 50).'.',
            'form.high_school_completion_year.max' => 'O ano de conclusão não pode ser futuro.',
            'form.high_school_final_grade.required' => 'A nota de conclusão do Ensino Médio é obrigatória.',
            'form.high_school_final_grade.numeric' => 'A nota de conclusão do Ensino Médio deve ser um número.',
            'form.high_school_final_grade.min' => 'A nota de conclusão do Ensino Médio deve ser no mínimo 0.',
            'form.high_school_final_grade.max' => 'A nota de conclusão do Ensino Médio deve ser no máximo 20.',
        ];
    }

    public function getCountriesProperty()
    {
        return \App\Models\Country::query()->orderBy('name')->get(['id', 'name']);
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.certification.academic-professional');
    }
}
