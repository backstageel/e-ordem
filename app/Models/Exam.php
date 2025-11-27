<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'allow_consultation' => 'boolean',
        'is_mandatory' => 'boolean',
        'immediate_result' => 'boolean',
        'minimum_grade' => 'decimal:1',
    ];

    /**
     * Get the applications for this exam.
     */
    public function applications()
    {
        return $this->hasMany(ExamApplication::class);
    }

    /**
     * Get the primary evaluator for this exam.
     */
    public function primaryEvaluator()
    {
        return $this->belongsTo(User::class, 'primary_evaluator_id');
    }

    /**
     * Get the secondary evaluator for this exam.
     */
    public function secondaryEvaluator()
    {
        return $this->belongsTo(User::class, 'secondary_evaluator_id');
    }

    /**
     * Get all results for this exam through applications.
     */
    public function results()
    {
        return $this->hasManyThrough(ExamResult::class, ExamApplication::class);
    }

    /**
     * Get the exam type for this exam.
     */
    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    /**
     * Get the schedules for this exam.
     */
    public function schedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    /**
     * Get the decisions for this exam.
     */
    public function decisions()
    {
        return $this->hasMany(ExamDecision::class);
    }

    /**
     * Get the appeals for this exam.
     */
    public function appeals()
    {
        return $this->hasMany(ExamAppeal::class);
    }

    /**
     * Get the payments for this exam.
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * Get the approved candidates for this exam.
     */
    public function approvedCandidates()
    {
        return $this->applications()
            ->whereHas('result', function ($query) {
                $query->where('decision', 'aprovado');
            });
    }

    /**
     * Get the rejected candidates for this exam.
     */
    public function rejectedCandidates()
    {
        return $this->applications()
            ->whereHas('result', function ($query) {
                $query->where('decision', 'reprovado');
            });
    }

    /**
     * Get the absent candidates for this exam.
     */
    public function absentCandidates()
    {
        return $this->applications()
            ->whereHas('result', function ($query) {
                $query->where('status', 'ausente');
            });
    }

    /**
     * Check if the exam is scheduled.
     */
    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if the exam is in progress.
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if the exam is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the exam is cancelled.
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
