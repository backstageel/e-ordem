<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResidencyProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'residency_programs';

    protected $guarded = false;

    protected $casts = [
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the applications for the program.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(ResidencyApplication::class, 'residency_program_id');
    }

    /**
     * Get the evaluations for the program through applications.
     */
    public function evaluations(): HasManyThrough
    {
        return $this->hasManyThrough(ResidencyEvaluation::class, ResidencyApplication::class, 'residency_program_id', 'residency_application_id');
    }

    /**
     * Get the program locations.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(ResidencyProgramLocation::class, 'residency_program_id');
    }

    /**
     * Get the coordinator of the program.
     */
    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    /**
     * Get the program status as a badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->is_active) {
            true => '<span class="badge bg-success">Ativo</span>',
            false => '<span class="badge bg-danger">Inativo</span>',
            default => '<span class="badge bg-secondary">Desconhecido</span>',
        };
    }

    /**
     * Get the nome attribute (Portuguese accessor for name).
     */
    public function getNomeAttribute()
    {
        return $this->name;
    }

    /**
     * Get the especialidade attribute (Portuguese accessor for specialty).
     */
    public function getEspecialidadeAttribute()
    {
        return $this->specialty;
    }

    /**
     * Get the duracao attribute (Portuguese accessor for duration_months).
     */
    public function getDuracaoAttribute()
    {
        return $this->duration_months;
    }

    /**
     * Get the taxa attribute (Portuguese accessor for fee).
     */
    public function getTaxaAttribute()
    {
        return $this->fee;
    }

    /**
     * Get the max_participantes attribute (Portuguese accessor for max_participants).
     */
    public function getMaxParticipantesAttribute()
    {
        return $this->max_participants;
    }
}
