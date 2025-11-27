<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Admin\Certification;

use App\Models\CivilState;
use App\Models\Country;
use App\Models\District;
use App\Models\Gender;
use App\Models\IdentityDocument;
use App\Models\Province;
use Modules\Registration\Models\RegistrationType;
use Spatie\LivewireWizard\Components\StepComponent;

class ReviewSubmitStep extends StepComponent
{
    public function summary(): array
    {
        $category = (int) ($this->state()->forStepClass(ChooseCategoryStep::class)['category'] ?? 0);
        $categoryData = $this->getCategoriesProperty()[$category] ?? null;
        $registrationType = $categoryData ? RegistrationType::where('code', $categoryData['code'])->first() : null;

        $contactState = (array) ($this->state()->forStepClass(ContactInfoStep::class) ?? []);
        $contact = [
            'email' => $contactState['email'] ?? null,
            'phone' => $contactState['phone'] ?? null,
        ];
        $personal = (array) ($this->state()->forStepClass(PersonalInfoStep::class)['form'] ?? []);
        $identity = (array) ($this->state()->forStepClass(IdentityAddressStep::class)['form'] ?? []);
        $academic = (array) ($this->state()->forStepClass(AcademicProfessionalStep::class)['form'] ?? []);
        $uploads = (array) ($this->state()->forStepClass(UploadDocumentsStep::class)['uploads'] ?? []);

        return [
            'category' => $category,
            'category_name' => $categoryData['name'] ?? null,
            'type' => $registrationType?->name,
            'contact' => $this->formatContact($contact),
            'personal' => $this->formatPersonal($personal),
            'identity' => $this->formatIdentity($identity),
            'academic' => $this->formatAcademic($academic),
            'uploads' => $uploads,
            'fee' => $categoryData['fee'] ?? 0,
        ];
    }

    protected function formatContact(array $contact): array
    {
        $labels = [
            'email' => 'Email',
            'phone' => 'Telefone',
        ];

        $formatted = [];
        foreach ($contact as $key => $value) {
            if ($value !== null && $value !== '') {
                $formatted[$labels[$key] ?? $key] = $value;
            }
        }

        return $formatted;
    }

    protected function formatPersonal(array $personal): array
    {
        $labels = [
            'first_name' => 'Nome',
            'middle_name' => 'Nome do Meio',
            'last_name' => 'Apelido',
            'father_name' => 'Nome do Pai',
            'mother_name' => 'Nome da Mãe',
            'gender_id' => 'Género',
            'birth_date' => 'Data de Nascimento',
            'birth_country_id' => 'País de Nascimento',
            'birth_province_id' => 'Província de Nascimento',
            'birth_district_id' => 'Distrito de Nascimento',
            'marital_status_id' => 'Estado Civil',
            'nationality_id' => 'Nacionalidade',
        ];

        $formatted = [];
        foreach ($personal as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $label = $labels[$key] ?? str_replace('_', ' ', $key);

            // Format IDs to labels
            if ($key === 'gender_id' && is_numeric($value)) {
                $gender = Gender::find($value);
                $formatted[$label] = $gender?->name ?? $value;
            } elseif ($key === 'marital_status_id' && is_numeric($value)) {
                $status = CivilState::find($value);
                $formatted[$label] = $status?->name ?? $value;
            } elseif ($key === 'birth_country_id' && is_numeric($value)) {
                $country = Country::find($value);
                $formatted[$label] = $country?->name ?? $value;
            } elseif ($key === 'birth_province_id' && is_numeric($value)) {
                $province = Province::find($value);
                $formatted[$label] = $province?->name ?? $value;
            } elseif ($key === 'birth_district_id' && is_numeric($value)) {
                $district = District::find($value);
                $formatted[$label] = $district?->name ?? $value;
            } elseif ($key === 'nationality_id' && is_numeric($value)) {
                $country = Country::find($value);
                $formatted[$label] = $country?->name ?? $value;
            } elseif ($key === 'birth_date') {
                try {
                    $formatted[$label] = \Carbon\Carbon::parse($value)->format('d/m/Y');
                } catch (\Exception $e) {
                    $formatted[$label] = $value;
                }
            } else {
                $formatted[$label] = $value;
            }
        }

        return $formatted;
    }

    protected function formatIdentity(array $identity): array
    {
        $labels = [
            'identity_document_id' => 'Tipo de Documento',
            'identity_document_number' => 'Número do Documento',
            'identity_document_issue_date' => 'Data de Emissão',
            'identity_document_expiry_date' => 'Data de Validade',
            'living_address' => 'Endereço',
            'living_country_id' => 'País de Residência',
            'living_province_id' => 'Província de Residência',
            'living_district_id' => 'Distrito de Residência',
            'neighborhood' => 'Bairro',
            'phone_2' => 'Telefone Alternativo',
            'phone_whatsapp' => 'Telefone com WhatsApp',
        ];

        $formatted = [];
        foreach ($identity as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $label = $labels[$key] ?? str_replace('_', ' ', $key);

            // Format IDs to labels
            if ($key === 'identity_document_id' && is_numeric($value)) {
                $doc = IdentityDocument::find($value);
                $formatted[$label] = $doc?->name ?? $value;
            } elseif ($key === 'living_country_id' && is_numeric($value)) {
                $country = Country::find($value);
                $formatted[$label] = $country?->name ?? $value;
            } elseif ($key === 'living_province_id' && is_numeric($value)) {
                $province = Province::find($value);
                $formatted[$label] = $province?->name ?? $value;
            } elseif ($key === 'living_district_id' && is_numeric($value)) {
                $district = District::find($value);
                $formatted[$label] = $district?->name ?? $value;
            } elseif (in_array($key, ['identity_document_issue_date', 'identity_document_expiry_date'])) {
                try {
                    $formatted[$label] = \Carbon\Carbon::parse($value)->format('d/m/Y');
                } catch (\Exception $e) {
                    $formatted[$label] = $value;
                }
            } else {
                $formatted[$label] = $value;
            }
        }

        return $formatted;
    }

    protected function formatAcademic(array $academic): array
    {
        $labels = [
            'degree_type' => 'Licenciatura em',
            'university' => 'Instituição de Ensino Superior',
            'university_start_year' => 'Ano de Início',
            'university_end_year' => 'Ano de Término',
            'university_country_id' => 'País da Licenciatura',
            'university_city_district' => 'Cidade/Distrito da Licenciatura',
            'university_final_grade' => 'Nota Final da Licenciatura',
            'high_school_institution' => 'Instituição de Ensino Médio',
            'high_school_country_id' => 'País do Ensino Médio',
            'high_school_city_district' => 'Cidade/Distrito do Ensino Médio',
            'high_school_completion_year' => 'Ano de Conclusão do Ensino Médio',
            'high_school_final_grade' => 'Nota de Conclusão do Ensino Médio',
        ];

        $formatted = [];
        foreach ($academic as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $label = $labels[$key] ?? str_replace('_', ' ', $key);

            // Format IDs to labels
            if ($key === 'university_country_id' && is_numeric($value)) {
                $country = Country::find($value);
                $formatted[$label] = $country?->name ?? $value;
            } elseif ($key === 'high_school_country_id' && is_numeric($value)) {
                $country = Country::find($value);
                $formatted[$label] = $country?->name ?? $value;
            } elseif (in_array($key, ['university_final_grade', 'high_school_final_grade'])) {
                $formatted[$label] = number_format((float) $value, 2, ',', '.');
            } else {
                $formatted[$label] = $value;
            }
        }

        return $formatted;
    }

    public function submit(): mixed
    {
        $category = (int) ($this->state()->forStepClass(ChooseCategoryStep::class)['category'] ?? 0);
        $categoryData = $this->getCategoriesProperty()[$category] ?? null;
        $registrationType = $categoryData ? RegistrationType::where('code', $categoryData['code'])->first() : null;

        if (! $registrationType) {
            $this->addError('submit', 'Tipo de inscrição não encontrado.');

            return null;
        }

        $contact = (array) $this->state()->forStepClass(ContactInfoStep::class);
        $personal = (array) ($this->state()->forStepClass(PersonalInfoStep::class)['form'] ?? []);
        $identity = (array) ($this->state()->forStepClass(IdentityAddressStep::class)['form'] ?? []);
        $academic = (array) ($this->state()->forStepClass(AcademicProfessionalStep::class)['form'] ?? []);
        $uploads = (array) ($this->state()->forStepClass(UploadDocumentsStep::class)['uploads'] ?? []);

        try {
            $action = new \App\Actions\Registration\CreateCertificationRegistrationAction;
            $registration = $action->execute(
                category: $category,
                contact: $contact,
                personal: $personal,
                identity: $identity,
                academic: $academic,
                uploads: $uploads,
                email: $contact['email'] ?? null,
                phone: $contact['phone'] ?? null
            );

            session([
                'registration_number' => $registration->process_number,
                'registration_id' => $registration->id,
            ]);

            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('success', 'Inscrição criada com sucesso! Número de processo: '.$registration->process_number);
        } catch (\Exception $e) {
            \Log::error('Error creating certification registration: '.$e->getMessage(), [
                'exception' => $e,
                'category' => $category,
            ]);
            $this->addError('submit', 'Ocorreu um erro ao submeter a inscrição. Por favor, tente novamente.');

            return null;
        }
    }

    protected function getCategoriesProperty(): array
    {
        $chooseCategoryStep = new ChooseCategoryStep;

        return $chooseCategoryStep->getCategoriesProperty();
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.admin.certification.review-submit', [
            'summary' => $this->summary(),
        ]);
    }
}
