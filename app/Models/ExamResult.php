<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $guarded = false;

    protected $casts = [
        'grade' => 'decimal:1',
        'notification_sent' => 'boolean',
        'evaluated_at' => 'datetime',
    ];

    /**
     * Get the application that this result belongs to.
     */
    public function application()
    {
        return $this->belongsTo(ExamApplication::class, 'exam_application_id');
    }

    /**
     * Get the user (evaluator) that evaluated this result.
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    /**
     * Get the exam through the application.
     */
    public function exam()
    {
        return $this->hasOneThrough(Exam::class, ExamApplication::class, 'id', 'id', 'exam_application_id', 'exam_id');
    }

    /**
     * Get the candidate through the application.
     */
    public function candidate()
    {
        return $this->hasOneThrough(User::class, ExamApplication::class, 'id', 'id', 'exam_application_id', 'user_id');
    }

    /**
     * Check if the candidate was present.
     */
    public function wasPresent()
    {
        return $this->status === 'presente';
    }

    /**
     * Check if the candidate was absent.
     */
    public function wasAbsent()
    {
        return $this->status === 'ausente';
    }

    /**
     * Check if the candidate was eliminated.
     */
    public function wasEliminated()
    {
        return $this->status === 'eliminado';
    }

    /**
     * Check if the candidate was approved.
     */
    public function isApproved()
    {
        return $this->decision === 'aprovado';
    }

    /**
     * Check if the candidate was rejected.
     */
    public function isRejected()
    {
        return $this->decision === 'reprovado';
    }

    /**
     * Check if the candidate's result is in appeal.
     */
    public function isInAppeal()
    {
        return $this->decision === 'recurso';
    }

    /**
     * Get the decision for this result.
     */
    public function decision()
    {
        return $this->hasOne(ExamDecision::class, 'result_id');
    }

    /**
     * Get the appeals for this result.
     */
    public function appeals()
    {
        return $this->hasMany(ExamAppeal::class, 'result_id');
    }

    /**
     * Get the result status as a badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'presente' => '<span class="badge bg-success">Presente</span>',
            'ausente' => '<span class="badge bg-warning">Ausente</span>',
            'eliminado' => '<span class="badge bg-danger">Eliminado</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Desconhecido</span>';
    }

    /**
     * Get the result decision as a badge HTML.
     */
    public function getDecisionBadgeAttribute()
    {
        $badges = [
            'aprovado' => '<span class="badge bg-success">Aprovado</span>',
            'reprovado' => '<span class="badge bg-danger">Reprovado</span>',
            'recurso' => '<span class="badge bg-warning">Em Recurso</span>',
        ];

        return $badges[$this->decision] ?? '<span class="badge bg-secondary">Pendente</span>';
    }
}
