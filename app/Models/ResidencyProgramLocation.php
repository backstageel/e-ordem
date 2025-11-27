<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResidencyProgramLocation extends Model
{
    use HasFactory;

    protected $table = 'residency_program_locations';

    protected $guarded = false;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'available_slots' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the program that this location assignment belongs to.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(ResidencyProgram::class, 'residency_program_id');
    }

    /**
     * Get the location that this assignment refers to.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(ResidencyLocation::class, 'residency_location_id');
    }

    /**
     * Get the assignment status as a badge.
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
     * Get the vagas_disponiveis attribute (Portuguese accessor for available_slots).
     */
    public function getVagasDisponiveisAttribute()
    {
        return $this->available_slots;
    }

    /**
     * Get the data_inicio attribute (Portuguese accessor for start_date).
     */
    public function getDataInicioAttribute()
    {
        return $this->start_date;
    }

    /**
     * Get the data_fim attribute (Portuguese accessor for end_date).
     */
    public function getDataFimAttribute()
    {
        return $this->end_date;
    }
}
