<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Admin\Provisional;

use Livewire\WithFileUploads;
use Modules\Registration\Models\RegistrationType;
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
        $subtype = (int) ($this->state()->forStepClass(ChooseSubtypeStep::class)['subtype'] ?? 0);
        $registrationType = $subtype > 0 ? RegistrationType::where('subtype_number', $subtype)->first() : null;

        if (! $registrationType) {
            return [];
        }

        // Get documents based on category
        $docs = $registrationType->required_documents ?? [];
        if (is_array($docs)) {
            return $docs;
        }

        return [];
    }

    public function queueDocument(string $key, $file = null): void
    {
        $this->currentDocumentKey = $key;
        if ($file) {
            $this->currentFile = $file;
        }
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

        $this->nextStep();
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.admin.provisional.upload-documents');
    }
}
