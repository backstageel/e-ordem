<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use App\Services\Exam\ExamResultService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcessResultsAction
{
    public function __construct(
        private ExamResultService $resultService
    ) {}

    public function execute(Exam $exam, array $results, ?string $decisionType = null): array
    {
        return DB::transaction(function () use ($exam, $results, $decisionType) {
            // Convert array to collection
            $resultsCollection = collect($results);

            // Process results using service
            $processed = $this->resultService->processResults($exam, $resultsCollection);

            // Add evaluated_by and decision_type if provided
            if ($decisionType) {
                foreach ($processed['results'] as $result) {
                    if (! $result->evaluated_by && Auth::check()) {
                        $result->evaluated_by = Auth::id();
                    }
                    if (! $result->decision_type && $decisionType) {
                        $result->decision_type = $decisionType;
                    }
                    $result->save();
                }
            }

            // Update exam status to completed
            $exam->status = 'completed';
            $exam->save();

            // Send notifications to candidates
            foreach ($processed['results'] as $result) {
                try {
                    $application = $result->application;
                    if ($application && $application->user) {
                        $application->user->notify(new \App\Notifications\Exam\ResultPublishedNotification($application));
                    }
                } catch (\Throwable $e) {
                    // Swallow notification errors
                }
            }

            return $processed;
        });
    }

    public function generateLists(Exam $exam): array
    {
        $admitted = $this->resultService->generateAdmittedList($exam);
        $excluded = $this->resultService->generateExcludedList($exam);

        return [
            'admitted' => $admitted,
            'excluded' => $excluded,
            'statistics' => $this->resultService->getStatistics($exam),
        ];
    }
}
