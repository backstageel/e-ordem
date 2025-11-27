<?php

namespace Modules\Registration\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Registration\Database\Factories\CertificationWorkflowFactory;

class CertificationWorkflow extends BaseModel
{
    use HasFactory, SoftDeletes;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CertificationWorkflowFactory::new();
    }

    protected $casts = [
        'current_step' => 'integer', // 1-9 conforme etapas do Edital
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'step_data' => 'array', // Dados específicos de cada etapa
        'decisions' => 'array', // Decisões e pareceres por etapa
        'history' => 'array', // Histórico completo de transições
    ];

    /**
     * Get the registration that owns this certification workflow.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the user assigned to this workflow.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the exam application related to this certification.
     */
    public function examApplication(): BelongsTo
    {
        return $this->belongsTo(\App\Models\ExamApplication::class);
    }

    /**
     * Get the exam result related to this certification.
     */
    public function examResult(): BelongsTo
    {
        return $this->belongsTo(\App\Models\ExamResult::class);
    }

    /**
     * Get all 9 steps of the certification workflow.
     */
    public static function getSteps(): array
    {
        return [
            1 => 'Submissão Online de Documentos',
            2 => 'Avaliação Documental Preliminar',
            3 => 'Convocação para Exame',
            4 => 'Realização do Exame',
            5 => 'Envio Personalizado de Resultados',
            6 => 'Submissão de Reclamações',
            7 => 'Revisão e Correção',
            8 => 'Publicação de Resultados Finais',
            9 => 'Pagamentos e Emissão de Cartão',
        ];
    }

    /**
     * Get the label for the current step.
     */
    public function getCurrentStepLabel(): string
    {
        $steps = self::getSteps();

        return $steps[$this->current_step] ?? 'Desconhecido';
    }

    /**
     * Move to the next step.
     */
    public function moveToNextStep(): void
    {
        if ($this->current_step < 9) {
            $this->addHistoryEntry($this->current_step, $this->current_step + 1);
            $this->current_step = $this->current_step + 1;
            $this->save();
        }
    }

    /**
     * Move to a specific step.
     */
    public function moveToStep(int $step): void
    {
        if ($step >= 1 && $step <= 9) {
            $this->addHistoryEntry($this->current_step, $step);
            $this->current_step = $step;
            $this->save();
        }
    }

    /**
     * Complete the workflow (step 9 completed).
     */
    public function complete(): void
    {
        $this->current_step = 9;
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Check if the workflow is completed.
     */
    public function isCompleted(): bool
    {
        return $this->current_step === 9 && $this->completed_at !== null;
    }

    /**
     * Add a decision for a specific step.
     */
    public function addDecision(int $step, string $decision, ?string $notes = null, ?int $userId = null): void
    {
        $decisions = $this->decisions ?? [];
        $decisions[] = [
            'step' => $step,
            'decision' => $decision,
            'notes' => $notes,
            'user_id' => $userId ?? auth()->id(),
            'timestamp' => now()->toISOString(),
        ];

        $this->decisions = $decisions;
        $this->save();
    }

    /**
     * Add data for a specific step.
     */
    public function setStepData(int $step, array $data): void
    {
        $stepData = $this->step_data ?? [];
        $stepData[$step] = $data;
        $this->step_data = $stepData;
        $this->save();
    }

    /**
     * Get data for a specific step.
     */
    public function getStepData(int $step): ?array
    {
        return $this->step_data[$step] ?? null;
    }

    /**
     * Add history entry for step transition.
     */
    protected function addHistoryEntry(int $fromStep, int $toStep): void
    {
        $history = $this->history ?? [];
        $history[] = [
            'from_step' => $fromStep,
            'to_step' => $toStep,
            'timestamp' => now()->toISOString(),
            'user_id' => auth()->id(),
        ];

        $this->history = $history;
    }

    /**
     * Get the history of the workflow.
     */
    public function getHistory(): array
    {
        return $this->history ?? [];
    }

    /**
     * Check if a specific step is completed.
     */
    public function isStepCompleted(int $step): bool
    {
        return $this->current_step > $step;
    }

    /**
     * Check if the workflow is at a specific step.
     */
    public function isAtStep(int $step): bool
    {
        return $this->current_step === $step;
    }
}
