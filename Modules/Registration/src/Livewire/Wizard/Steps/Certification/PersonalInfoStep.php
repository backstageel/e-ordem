<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Certification;

use Modules\Registration\Models\RegistrationType;
use Modules\Registration\Models\TemporaryRegistration;
use Modules\Registration\Services\EligibilityValidationService;
use Spatie\LivewireWizard\Components\StepComponent;

class PersonalInfoStep extends StepComponent
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
                    $stored = $temp->getStepData(3) ?? [];
                    if (! empty($stored)) {
                        $this->form = $stored;
                    }
                }
            }
        }
    }

    public function saveAndNext(): void
    {
        $this->validate($this->rules());

        // Validate eligibility based on selected category
        $category = (int) ($this->state()->forStepClass(ChooseCategoryStep::class)['category'] ?? 0);
        $categoryData = (new ChooseCategoryStep)->getCategoriesProperty()[$category] ?? null;
        $registrationType = $categoryData ? RegistrationType::where('code', $categoryData['code'])->first() : null;

        if ($registrationType) {
            $eligibilityService = app(EligibilityValidationService::class);
            $eligibilityData = [
                'nationality_id' => $this->form['nationality_id'] ?? null,
                'birth_country_id' => $this->form['birth_country_id'] ?? null,
                'birth_date' => $this->form['birth_date'] ?? null,
                // Note: academic_training_country_id will be validated in AcademicProfessionalStep
                // For now, we only validate nationality and birth country
            ];

            $validation = $eligibilityService->validateForCertification($eligibilityData, $category);

            if (! $validation['eligible']) {
                foreach ($validation['issues'] as $issue) {
                    $this->addError('form.nationality_id', $issue);
                }

                return;
            }
        }

        // Persist snapshot to TemporaryRegistration
        $email = (string) (($this->state()->forStepClass(ContactInfoStep::class)['email'] ?? ''));
        $phone = (string) (($this->state()->forStepClass(ContactInfoStep::class)['phone'] ?? ''));

        $temp = TemporaryRegistration::updateOrCreate(
            [
                'email' => $email ?: null,
                'phone' => $phone ?: null,
            ],
            [
                'current_step' => 3,
                'expires_at' => now()->addHours(24),
            ]
        );
        $temp->setStepData(3, $this->form);

        $this->nextStep();
    }

    protected function rules(): array
    {
        $countryId = (int) ($this->form['birth_country_id'] ?? 0);
        $hasProvinces = $countryId > 0 && \App\Models\Province::where('country_id', $countryId)->exists();
        $provinceId = (int) ($this->form['birth_province_id'] ?? 0);
        $hasDistricts = $provinceId > 0 && \App\Models\District::where('province_id', $provinceId)->exists();

        return [
            'form.first_name' => ['required', 'string', 'max:255'],
            'form.middle_name' => ['nullable', 'string', 'max:255'],
            'form.last_name' => ['required', 'string', 'max:255'],
            'form.father_name' => ['nullable', 'string', 'max:255'],
            'form.mother_name' => ['nullable', 'string', 'max:255'],
            'form.gender_id' => ['required', 'integer', 'exists:genders,id'],
            'form.birth_date' => ['required', 'date', 'before:today', function ($attribute, $value, $fail) {
                try {
                    $birthDate = \Carbon\Carbon::parse($value);
                    $today = \Carbon\Carbon::now();
                    $age = $today->year - $birthDate->year;

                    // Adjust if birthday hasn't occurred this year
                    if ($today->month < $birthDate->month ||
                        ($today->month == $birthDate->month && $today->day < $birthDate->day)) {
                        $age--;
                    }

                    if ($age < 22) {
                        $fail('A idade mínima é de 22 anos.');
                    }
                } catch (\Exception $e) {
                    $fail('Data de nascimento inválida.');
                }
            }],
            'form.birth_country_id' => ['required', 'integer', 'exists:countries,id'],
            'form.birth_province_id' => $hasProvinces ? ['nullable', 'integer', 'exists:provinces,id'] : ['nullable'],
            'form.birth_district_id' => $hasDistricts ? ['nullable', 'integer', 'exists:districts,id'] : ['nullable'],
            'form.marital_status_id' => ['nullable', 'integer', 'exists:civil_states,id'],
            'form.nationality_id' => ['required', 'integer', 'exists:countries,id'],
        ];
    }

    public function getGendersProperty()
    {
        return \App\Models\Gender::query()->orderBy('name')->get(['id', 'name']);
    }

    public function getCivilStatesProperty()
    {
        return \App\Models\CivilState::query()->orderBy('name')->get(['id', 'name']);
    }

    public function getCountriesProperty()
    {
        return \App\Models\Country::query()->orderBy('name')->get(['id', 'name']);
    }

    public function getBirthProvincesProperty()
    {
        $countryId = (int) ($this->form['birth_country_id'] ?? 0);
        if (! $countryId) {
            return collect();
        }

        $provinces = \App\Models\Province::query()->where('country_id', $countryId)->orderBy('name')->get(['id', 'name']);

        // If country has no provinces, return empty (will show "Estrangeiro" option in view)
        if ($provinces->isEmpty()) {
            return collect();
        }

        return $provinces;
    }

    public function getBirthDistrictsProperty()
    {
        $provinceId = (int) ($this->form['birth_province_id'] ?? 0);
        if (! $provinceId) {
            return collect();
        }

        $districts = \App\Models\District::query()->where('province_id', $provinceId)->orderBy('name')->get(['id', 'name']);

        // If province has no districts, return empty (will show "Estrangeiro" option in view)
        if ($districts->isEmpty()) {
            return collect();
        }

        return $districts;
    }

    public function getBirthCountryHasProvincesProperty(): bool
    {
        $countryId = (int) ($this->form['birth_country_id'] ?? 0);
        if (! $countryId) {
            return false;
        }

        return \App\Models\Province::where('country_id', $countryId)->exists();
    }

    public function getBirthProvinceHasDistrictsProperty(): bool
    {
        $provinceId = (int) ($this->form['birth_province_id'] ?? 0);
        if (! $provinceId) {
            return false;
        }

        return \App\Models\District::where('province_id', $provinceId)->exists();
    }

    public function updatedFormBirthCountryId(): void
    {
        // Reset province and district when country changes
        $this->form['birth_province_id'] = null;
        $this->form['birth_district_id'] = null;
    }

    public function updatedFormBirthProvinceId(): void
    {
        // Reset district when province changes
        $this->form['birth_district_id'] = null;
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.certification.personal-info');
    }
}
