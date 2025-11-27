<?php

namespace Modules\Registration\Livewire\Wizard\Steps\Admin\Effective;

use Livewire\WithFileUploads;
use Modules\Registration\Models\RegistrationType;
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

        // Effective registrations don't use temporary storage
        // Documents are minimal (already validated in exam process)
    }

    public function getRequiredDocumentsProperty(): array
    {
        $grade = (string) ($this->state()->forStepClass(SelectGradeStep::class)['grade'] ?? '');
        $registrationType = $grade ? RegistrationType::where('grade', $grade)->first() : null;

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

        // Effective registrations don't use temporary storage

        $this->reset(['currentDocumentKey', 'currentFile']);
    }

    public function saveAndNext(): void
    {
        // Documents are validated but optional at this stage
        // Missing documents will be tracked in additional_documents_required field

        // Effective registrations don't use temporary storage

        $this->nextStep();
    }

    public function render()
    {
        return view('registration::livewire.registration.wizard.steps.admin.effective.upload-documents');
    }
}
