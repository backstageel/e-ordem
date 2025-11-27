<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamApplication extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $guarded = false;

    protected $casts = [
        'preferred_date' => 'date',
        'terms_accepted' => 'boolean',
        'is_confirmed' => 'boolean',
        'is_present' => 'boolean',
    ];

    /**
     * Get the exam that this application belongs to.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the user (candidate) that this application belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the result for this application.
     */
    public function result()
    {
        return $this->hasOne(ExamResult::class);
    }

    /**
     * Get the schedule for this application.
     */
    public function schedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }

    /**
     * Get the decision for this application.
     */
    public function decision()
    {
        return $this->hasOne(ExamDecision::class, 'application_id');
    }

    /**
     * Get the appeals for this application.
     */
    public function appeals()
    {
        return $this->hasMany(ExamAppeal::class, 'application_id');
    }

    /**
     * Check if the application is a draft.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the application is submitted.
     */
    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if the application is in review.
     */
    public function isInReview()
    {
        return $this->status === 'in_review';
    }

    /**
     * Check if the application is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the application is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the application has pending documents.
     */
    public function hasDocumentsPending()
    {
        return $this->status === 'documents_pending';
    }

    /**
     * Check if the candidate is confirmed for the exam.
     */
    public function isConfirmed()
    {
        return $this->is_confirmed;
    }

    /**
     * Check if the candidate was present at the exam.
     */
    public function wasPresent()
    {
        return $this->is_present;
    }

    /**
     * Get the application status as a badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Rascunho</span>',
            'submitted' => '<span class="badge bg-primary">Submetido</span>',
            'in_review' => '<span class="badge bg-info">Em Análise</span>',
            'approved' => '<span class="badge bg-success">Aprovado</span>',
            'rejected' => '<span class="badge bg-danger">Rejeitado</span>',
            'documents_pending' => '<span class="badge bg-warning">Documentos Pendentes</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Desconhecido</span>';
    }
}
