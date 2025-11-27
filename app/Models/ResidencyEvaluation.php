<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResidencyEvaluation extends Model
{
    use HasFactory;

    protected $table = 'residency_evaluations';

    protected $guarded = false;

    protected $casts = [
        'evaluation_date' => 'date',
        'score' => 'decimal:2',
        'is_satisfactory' => 'boolean',
    ];

    /**
     * Get the application that this evaluation belongs to.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(ResidencyApplication::class, 'residency_application_id');
    }

    /**
     * Get the evaluator who performed this evaluation.
     */
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * Get the evaluation status as a badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->is_satisfactory) {
            true => '<span class="badge bg-success">Satisfatório</span>',
            false => '<span class="badge bg-danger">Não Satisfatório</span>',
            default => '<span class="badge bg-secondary">Não Avaliado</span>',
        };
    }

    /**
     * Get the candidatura attribute (Portuguese accessor for application).
     */
    public function getCandidaturaAttribute()
    {
        return $this->application;
    }

    /**
     * Get the avaliador attribute (Portuguese accessor for evaluator).
     */
    public function getAvaliadorAttribute()
    {
        return $this->evaluator;
    }

    /**
     * Get the data_avaliacao attribute (Portuguese accessor for evaluation_date).
     */
    public function getDataAvaliacaoAttribute()
    {
        return $this->evaluation_date;
    }

    /**
     * Get the pontuacao attribute (Portuguese accessor for score).
     */
    public function getPontuacaoAttribute()
    {
        return $this->score;
    }

    /**
     * Get the nota attribute (Portuguese accessor for grade).
     */
    public function getNotaAttribute()
    {
        return $this->grade;
    }

    /**
     * Get the comentarios attribute (Portuguese accessor for comments).
     */
    public function getComentariosAttribute()
    {
        return $this->comments;
    }

    /**
     * Get the recomendacoes attribute (Portuguese accessor for recommendations).
     */
    public function getRecomendacoesAttribute()
    {
        return $this->recommendations;
    }
}
