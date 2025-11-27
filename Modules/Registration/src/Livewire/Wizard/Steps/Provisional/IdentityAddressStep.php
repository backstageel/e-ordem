<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Provisional;

use Modules\Registration\Models\TemporaryRegistration;
use Spatie\LivewireWizard\Components\StepComponent;

class IdentityAddressStep extends StepComponent
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
                    $stored = $temp->getStepData(4) ?? [];
                    if (! empty($stored)) {
                        $this->form = $stored;
                    }
                }
            }
        }

    }

    public function saveAndNext(): void
    {
        $countryId = (int) ($this->form['living_country_id'] ?? 0);
        $hasProvinces = $countryId > 0 && \App\Models\Province::where('country_id', $countryId)->exists();
        $provinceId = (int) ($this->form['living_province_id'] ?? 0);
        $hasDistricts = $provinceId > 0 && \App\Models\District::where('province_id', $provinceId)->exists();

        $this->validate([
            'form.identity_document_id' => ['required', 'integer', 'exists:identity_documents,id'],
            'form.identity_document_number' => ['required', 'string', 'max:255'],
            'form.identity_document_issue_date' => ['required', 'date', 'before:tomorrow'],
            'form.identity_document_expiry_date' => ['required', 'date', 'after:'.now()->addMonths(6)->format('Y-m-d')],
            'form.living_address' => ['required', 'string', 'max:500'],
            'form.living_country_id' => ['required', 'integer', 'exists:countries,id'],
            'form.living_province_id' => $hasProvinces ? ['nullable', 'integer', 'exists:provinces,id'] : ['nullable'],
            'form.living_district_id' => $hasDistricts ? ['nullable', 'integer', 'exists:districts,id'] : ['nullable'],
            'form.neighborhood' => ['nullable', 'string', 'max:255'],
            'form.phone_2' => ['nullable', 'string', 'regex:/^\+258[2-8][0-9]{7,8}$/'],
            'form.phone_whatsapp' => ['nullable', 'string', 'regex:/^\+258[2-8][0-9]{7,8}$/'],
        ], [
            'form.identity_document_id.required' => 'O tipo de documento é obrigatório.',
            'form.identity_document_number.required' => 'O número do documento é obrigatório.',
            'form.identity_document_issue_date.required' => 'A data de emissão é obrigatória.',
            'form.identity_document_issue_date.before' => 'A data de emissão não pode ser futura.',
            'form.identity_document_expiry_date.required' => 'A data de validade é obrigatória.',
            'form.identity_document_expiry_date.after' => 'O documento deve ser válido por pelo menos 6 meses a partir de hoje.',
            'form.living_address.required' => 'O endereço é obrigatório.',
            'form.living_country_id.required' => 'O país de residência é obrigatório.',
            'form.phone_2.regex' => 'O telefone alternativo deve estar no formato +258821234567 ou +2588212345678.',
            'form.phone_whatsapp.regex' => 'O telefone WhatsApp deve estar no formato +258821234567 ou +2588212345678.',
        ]);

        // Persist snapshot
        $email = (string) (($this->state()->forStepClass(ContactInfoStep::class)['email'] ?? ''));
        $phone = (string) (($this->state()->forStepClass(ContactInfoStep::class)['phone'] ?? ''));
        $temp = TemporaryRegistration::firstOrCreate([
            'email' => $email ?: null,
            'phone' => $phone ?: null,
        ], [
            'current_step' => 4,
            'expires_at' => now()->addHours(24),
        ]);
        $temp->setStepData(4, $this->form);

        $this->nextStep();
    }

    public function getIdentityDocumentsProperty()
    {
        return \App\Models\IdentityDocument::query()->orderBy('name')->get(['id', 'name']);
    }

    public function getCountriesProperty()
    {
        return \App\Models\Country::query()->orderBy('name')->get(['id', 'name']);
    }

    public function getLivingProvincesProperty()
    {
        $countryId = (int) ($this->form['living_country_id'] ?? 0);
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

    public function getLivingDistrictsProperty()
    {
        $provinceId = (int) ($this->form['living_province_id'] ?? 0);
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

    public function getLivingCountryHasProvincesProperty(): bool
    {
        $countryId = (int) ($this->form['living_country_id'] ?? 0);
        if (! $countryId) {
            return false;
        }

        return \App\Models\Province::where('country_id', $countryId)->exists();
    }

    public function getLivingProvinceHasDistrictsProperty(): bool
    {
        $provinceId = (int) ($this->form['living_province_id'] ?? 0);
        if (! $provinceId) {
            return false;
        }

        return \App\Models\District::where('province_id', $provinceId)->exists();
    }

    public function updatedFormLivingCountryId(): void
    {
        // Reset province and district when country changes
        $this->form['living_province_id'] = null;
        $this->form['living_district_id'] = null;
    }

    public function updatedFormLivingProvinceId(): void
    {
        // Reset district when province changes
        $this->form['living_district_id'] = null;
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.provisional.identity-address');
    }
}
