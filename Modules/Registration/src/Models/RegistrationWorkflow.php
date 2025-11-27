<?php

namespace Modules\Registration\Models;

use App\Enums\WorkflowStatus;
use App\Enums\WorkflowStep;
use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Registration\Database\Factories\RegistrationWorkflowFactory;

class RegistrationWorkflow extends BaseModel
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return RegistrationWorkflowFactory::new();
    }

    protected $casts = [
        'current_step' => WorkflowStep::class,
        'status' => WorkflowStatus::class,
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'decisions' => 'array',
        'workflow_data' => 'array',
    ];

    // Auditing configuration inherited from BaseModel

    /**
     * Get the registration that owns the workflow.
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
     * Scope a query to only include pending workflows.
     */
    public function scopePending($query)
    {
        return $query->where('status', WorkflowStatus::PENDING);
    }

    /**
     * Scope a query to only include in progress workflows.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', WorkflowStatus::IN_PROGRESS);
    }

    /**
     * Scope a query to only include completed workflows.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', WorkflowStatus::COMPLETED);
    }

    /**
     * Scope a query to only include cancelled workflows.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', WorkflowStatus::CANCELLED);
    }

    /**
     * Scope a query to only include workflows assigned to a specific user.
     */
    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Check if the workflow is pending.
     */
    public function isPending(): bool
    {
        return $this->status === WorkflowStatus::PENDING;
    }

    /**
     * Check if the workflow is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === WorkflowStatus::IN_PROGRESS;
    }

    /**
     * Check if the workflow is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === WorkflowStatus::COMPLETED;
    }

    /**
     * Check if the workflow is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === WorkflowStatus::CANCELLED;
    }

    /**
     * Move to the next step in the workflow.
     */
    public function moveToNextStep(): void
    {
        $steps = $this->getWorkflowSteps();
        $currentIndex = array_search($this->current_step, $steps);

        if ($currentIndex !== false && $currentIndex < count($steps) - 1) {
            $this->current_step = $steps[$currentIndex + 1];
            $this->save();
        }
    }

    /**
     * Complete the workflow.
     */
    public function complete(): void
    {
        $this->status = WorkflowStatus::COMPLETED;
        $this->completed_at = now();
        $this->current_step = WorkflowStep::COMPLETED;
        $this->save();
    }

    /**
     * Cancel the workflow.
     */
    public function cancel(?string $reason = null): void
    {
        $this->status = WorkflowStatus::CANCELLED;
        $this->completed_at = now();

        if ($reason) {
            $this->notes = ($this->notes ? $this->notes."\n" : '').'Cancelado: '.$reason;
        }

        $this->save();
    }

    /**
     * Assign the workflow to a user.
     */
    public function assignTo(User $user): void
    {
        $this->assigned_to = $user->id;
        $this->status = WorkflowStatus::IN_PROGRESS;
        $this->save();
    }

    /**
     * Add a decision to the workflow.
     */
    public function addDecision(string $step, string $decision, ?string $notes = null): void
    {
        $decisions = $this->decisions ?? [];
        $decisions[] = [
            'step' => $step,
            'decision' => $decision,
            'notes' => $notes,
            'timestamp' => now()->toISOString(),
            'user_id' => auth()->id(),
        ];

        $this->decisions = $decisions;
        $this->save();
    }

    /**
     * Get the workflow steps for this registration type.
     */
    public function getWorkflowSteps(): array
    {
        if ($this->registration && $this->registration->registrationType) {
            return $this->registration->registrationType->getWorkflowSteps();
        }

        return WorkflowStep::getProvisionalSteps();
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeColor(): string
    {
        return $this->status->color();
    }

    /**
     * Get the status label.
     */
    public function getStatusLabel(): string
    {
        return $this->status->label();
    }

    /**
     * Get the current step label.
     */
    public function getCurrentStepLabel(): string
    {
        return $this->current_step->label();
    }

    /**
     * Get the duration of the workflow.
     */
    public function getDuration(): ?int
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInDays($this->completed_at);
        }

        return null;
    }

    /**
     * Get the duration in hours.
     */
    public function getDurationInHours(): ?int
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInHours($this->completed_at);
        }

        return null;
    }
}

