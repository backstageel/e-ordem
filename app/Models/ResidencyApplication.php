<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResidencyApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'residency_applications';

    protected $guarded = false;

    protected $casts = [
        'application_date' => 'date',
        'approval_date' => 'date',
        'start_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'payment_date' => 'date',
        'payment_amount' => 'decimal:2',
        'is_paid' => 'boolean',
    ];

    /**
     * Get the program that the application is for.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(ResidencyProgram::class, 'residency_program_id');
    }

    /**
     * Get the location for the application.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(ResidencyLocation::class, 'residency_location_id');
    }

    /**
     * Get the member that submitted the application.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Get the user that approved the application.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the evaluations for this application.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(ResidencyEvaluation::class, 'residency_application_id');
    }

    /**
     * Get the application status as a badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning">Pendente</span>',
            'approved' => '<span class="badge bg-success">Aprovada</span>',
            'rejected' => '<span class="badge bg-danger">Rejeitada</span>',
            'in_progress' => '<span class="badge bg-info">Em Progresso</span>',
            'completed' => '<span class="badge bg-primary">Concluída</span>',
            'cancelled' => '<span class="badge bg-secondary">Cancelada</span>',
            default => '<span class="badge bg-secondary">Desconhecido</span>',
        };
    }

    /**
     * Get the membro attribute (Portuguese accessor for member).
     */
    public function getMembroAttribute()
    {
        return $this->member;
    }

    /**
     * Get the programa attribute (Portuguese accessor for program).
     */
    public function getProgramaAttribute()
    {
        return $this->program;
    }

    /**
     * Get the localizacao attribute (Portuguese accessor for location).
     */
    public function getLocalizacaoAttribute()
    {
        return $this->location;
    }

    /**
     * Get the data_candidatura attribute (Portuguese accessor for application_date).
     */
    public function getDataCandidaturaAttribute()
    {
        return $this->application_date;
    }

    /**
     * Get the data_aprovacao attribute (Portuguese accessor for approval_date).
     */
    public function getDataAprovacaoAttribute()
    {
        return $this->approval_date;
    }

    /**
     * Get the data_inicio attribute (Portuguese accessor for start_date).
     */
    public function getDataInicioAttribute()
    {
        return $this->start_date;
    }

    /**
     * Get the data_conclusao_esperada attribute (Portuguese accessor for expected_completion_date).
     */
    public function getDataConclusaoEsperadaAttribute()
    {
        return $this->expected_completion_date;
    }

    /**
     * Get the data_conclusao_real attribute (Portuguese accessor for actual_completion_date).
     */
    public function getDataConclusaoRealAttribute()
    {
        return $this->actual_completion_date;
    }
}
