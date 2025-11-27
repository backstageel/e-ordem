<?php

namespace App\Livewire\Exam;

use App\Models\Exam;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplicationForm extends Component
{
    use WithFileUploads;

    public Exam $exam;

    public $exam_type = 'certificacao';

    public $specialty = '';

    public $other_specialty = '';

    public $preferred_date = '';

    public $preferred_location = '';

    public $cv_file;

    public $payment_proof_file;

    public $recommendation_letter_file;

    public $additional_documents_file;

    public $experience_summary = '';

    public $experience_years = '';

    public $current_institution = '';

    public $special_needs = '';

    public $observations = '';

    public $terms_accepted = false;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;
        $this->specialty = $exam->specialty;
    }

    public function submit(): void
    {
        $this->validate([
            'exam_type' => ['required', 'in:certificacao,especialidade,revalidacao,recertificacao'],
            'specialty' => ['required', 'string', 'max:255'],
            'terms_accepted' => ['required', 'accepted'],
        ]);

        // Emit event to parent or handle submission
        $this->dispatch('application-submit', [
            'exam_id' => $this->exam->id,
            'exam_type' => $this->exam_type,
            'specialty' => $this->specialty,
        ]);
    }

    public function render()
    {
        return view('livewire.exam.application-form');
    }
}
