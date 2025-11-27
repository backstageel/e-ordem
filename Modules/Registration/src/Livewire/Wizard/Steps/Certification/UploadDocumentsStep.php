<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Certification;

use Livewire\WithFileUploads;
use Modules\Registration\Models\RegistrationType;
use Livewire\Attributes\On;
use Modules\Registration\Models\TemporaryRegistration;
use Spatie\LivewireWizard\Components\StepComponent;

class UploadDocumentsStep extends StepComponent
{
    use WithFileUploads;

    public array $uploads = [];

    public ?string $currentDocumentKey = null;

    public $currentFile;

    public function mount(): void
    {
        $this->uploads = (array) $this->state()->get('uploads', []);

        // Load from temporary registration if resuming
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
                $stored = $temp->getStepData(6) ?? [];
                if (isset($stored['uploads']) && is_array($stored['uploads'])) {
                    $this->uploads = $stored['uploads'];
                }
            }
        }
    }

    public function getRequiredDocumentsProperty(): array
    {
        $category = (int) ($this->state()->forStepClass(ChooseCategoryStep::class)['category'] ?? 0);
        $categoryData = $this->getCategoriesProperty()[$category] ?? null;
        $registrationType = $categoryData ? RegistrationType::where('code', $categoryData['code'])->first() : null;

        if (! $registrationType) {
            return [];
        }

        // For certification, documents are stored as a simple array
        // Get all required documents
        $docs = $registrationType->required_documents ?? [];
        if (is_array($docs)) {
            return $docs;
        }

        return [];
    }

    /**
     * Get document label for display.
     */
    public function getDocumentLabel(string $docCode): string
    {
        $labels = [
            'bi_valido' => 'Fotocópia do BI válido',
            'certificado_conclusao_curso' => 'Cópia autenticada do certificado de conclusão do curso',
            'curriculum_vitae' => 'Curriculum Vitae',
            'fotografias_tipo_passe' => 'Duas (2) fotografias tipo-passe',
            'nuit' => 'Fotocópia do cartão ou declaração do NUIT',
            'certificado_registo_criminal_mz' => 'Certificado de registo criminal moçambicano (emitido há menos de 90 dias)',
            'comprovativo_pagamento_exame' => 'Comprovativo de pagamento da taxa de inscrição no exame',
            'certificado_equivalencia_mec' => 'Certificado de equivalência emitido pelo MEC',
            'programa_curricular_detalhado' => 'Programa Curricular DETALHADO (disciplinas, programas, notas, carga horária)',
            'comprovativo_acreditacao_medical_council' => 'Comprovativo de acreditação da instituição pelo Medical Council do país',
            'carta_reconhecimento_ministerio_ensino_superior' => 'Carta de reconhecimento do programa pelo Ministério do Ensino Superior do país de origem',
            'certificado_registo_criminal_pais_estudo' => 'Certificado de registo criminal do país onde estudou (emitido há menos de 90 dias)',
            'comprovativo_pagamento_tramitacao' => 'Comprovativo de pagamento da taxa de tramitação',
            'certificado_registo_criminal_pais_origem' => 'Certificado de registo criminal do país de origem (emitido há menos de 90 dias)',
            'carta_autorizacao_ministerio_saude' => 'Carta de autorização do Ministério da Saúde do país de origem (se aplicável)',
        ];

        return $labels[$docCode] ?? str_replace('_', ' ', ucwords($docCode, '_'));
    }

    protected function getCategoriesProperty(): array
    {
        $chooseCategoryStep = new \Modules\Registration\Livewire\Wizard\Steps\Certification\ChooseCategoryStep;

        return $chooseCategoryStep->getCategoriesProperty();
    }

    public function queueDocument(string $key): void
    {
        $this->currentDocumentKey = $key;
        // File is already selected via the input, so we're ready to upload
    }

    public function uploadCurrent(): void
    {
        if (! $this->currentDocumentKey || ! $this->currentFile) {
            return;
        }

        $this->validate([
            'currentFile' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
        ], [
            'currentFile.mimes' => 'O ficheiro deve ser PDF, JPG, JPEG ou PNG.',
            'currentFile.max' => 'O tamanho do ficheiro não deve exceder 5MB.',
        ]);

        // Store in private storage
        $path = $this->currentFile->storeAs('temp/registration', $this->currentFile->hashName(), 'local');
        $this->uploads[$this->currentDocumentKey] = $path;

        // Persist immediately for resume capability
        $email = (string) (($this->state()->forStepClass(ContactInfoStep::class)['email'] ?? ''));
        $phone = (string) (($this->state()->forStepClass(ContactInfoStep::class)['phone'] ?? ''));
        $temp = TemporaryRegistration::firstOrCreate([
            'email' => $email ?: null,
            'phone' => $phone ?: null,
        ], [
            'current_step' => 6,
            'expires_at' => now()->addHours(24),
        ]);
        $temp->setStepData(6, ['uploads' => $this->uploads]);

        $this->reset(['currentDocumentKey', 'currentFile']);
    }

    public function saveAndNext(): void
    {
        // Documents are validated but optional at this stage
        // Missing documents will be tracked in additional_documents_required field

        $email = (string) (($this->state()->forStepClass(ContactInfoStep::class)['email'] ?? ''));
        $phone = (string) (($this->state()->forStepClass(ContactInfoStep::class)['phone'] ?? ''));
        $temp = TemporaryRegistration::firstOrCreate([
            'email' => $email ?: null,
            'phone' => $phone ?: null,
        ], [
            'current_step' => 6,
            'expires_at' => now()->addHours(24),
        ]);
        $temp->setStepData(6, ['uploads' => $this->uploads]);

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

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.certification.upload-documents');
    }
}
