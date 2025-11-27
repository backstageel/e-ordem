<?php

namespace App\Livewire\Admin\Documents;

use App\Documents\CheckDocumentPendenciesAction;
use App\Documents\RequestDocumentCorrectionAction;
use App\Models\Document;
use App\Services\Documents\DocumentValidationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ValidationPanel extends Component
{
    public Document $document;

    public string $status = 'pending';

    public ?string $rejectionReason = null;

    public ?string $notes = null;

    public array $validationResults = [];

    public bool $showValidationResults = false;

    public function mount(Document $document): void
    {
        $this->document = $document->load(['documentType', 'person', 'member']);
        $this->status = $document->status->value;
    }

    public function runValidation(): void
    {
        $validationService = app(DocumentValidationService::class);
        $results = $validationService->validateDocument($this->document);

        $this->validationResults = $results;
        $this->showValidationResults = true;

        if ($results['valid']) {
            session()->flash('validation-success', 'Validação automática concluída com sucesso!');
        } else {
            session()->flash('validation-error', 'Validação automática encontrou problemas: '.implode(', ', $results['errors']));
        }
    }

    public function validateDocument(): void
    {
        $this->validate([
            'status' => 'required|in:under_review,requires_correction,validated,expired,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->document->status = \App\Enums\DocumentStatus::from($this->status);
            $this->document->validation_date = now();
            $this->document->validated_by = Auth::id();

            if ($this->status === 'rejected' && $this->rejectionReason) {
                $this->document->rejection_reason = $this->rejectionReason;
            }

            if ($this->notes) {
                $this->document->notes = $this->notes;
            }

            $this->document->save();

            // If document requires correction, use the action
            if ($this->status === 'requires_correction') {
                $correctionAction = app(RequestDocumentCorrectionAction::class);
                $correctionAction->execute(
                    $this->document,
                    Auth::user(),
                    $this->notes ?? 'Documento requer correção.',
                    $this->rejectionReason
                );
            }

            // Check and update registration pendencies
            if ($this->document->registration) {
                $checkPendencies = app(CheckDocumentPendenciesAction::class);
                $checkPendencies->execute($this->document->registration);
            }

            session()->flash('success', 'Documento validado com sucesso.');
            $this->dispatch('document-validated', $this->document->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao validar documento: '.$e->getMessage());
        }
    }

    public function generateValidationReport(): mixed
    {
        try {
            // Run validation first
            $validationService = app(DocumentValidationService::class);
            $validationResults = $validationService->validateDocument($this->document);

            // Generate PDF report
            $pdf = Pdf::loadView('admin.documents.validation-report', [
                'document' => $this->document,
                'validationResults' => $validationResults,
                'validator' => Auth::user(),
                'date' => now(),
            ]);

            $filename = 'parecer_validacao_'.$this->document->id.'_'.now()->format('Y-m-d_H-i-s').'.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao gerar parecer: '.$e->getMessage());

            return null;
        }
    }

    public function render()
    {
        return view('livewire.admin.documents.validation-panel');
    }
}
