<?php

namespace App\Console\Commands;

use App\Actions\Exam\ProcessResultsAction;
use App\Models\Exam;
use Illuminate\Console\Command;

class ProcessExamResults extends Command
{
    protected $signature = 'exams:process-results 
                            {exam_id? : Specific exam ID to process}
                            {--all : Process all completed exams}
                            {--dry-run : Show what would be processed without actually processing}';

    protected $description = 'Process exam results in batch mode';

    public function handle(ProcessResultsAction $action): int
    {
        $examId = $this->argument('exam_id');
        $all = $this->option('all');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No results will be processed');
            $this->newLine();
        }

        if ($examId) {
            $exam = Exam::findOrFail($examId);
            $this->processExam($exam, $action, $dryRun);
        } elseif ($all) {
            $exams = Exam::where('status', 'completed')
                ->whereDoesntHave('results')
                ->get();

            $this->info("Found {$exams->count()} exams to process");
            $this->newLine();

            foreach ($exams as $exam) {
                $this->processExam($exam, $action, $dryRun);
            }
        } else {
            $this->error('Please provide either an exam_id or use --all flag');

            return Command::FAILURE;
        }

        if (! $dryRun) {
            $this->info('✅ Results processed successfully');
        }

        return Command::SUCCESS;
    }

    private function processExam(Exam $exam, ProcessResultsAction $action, bool $dryRun): void
    {
        $this->info("Processing exam: {$exam->name} (ID: {$exam->id})");

        if ($dryRun) {
            $applicationsCount = $exam->applications()->count();
            $this->line("  Would process {$applicationsCount} applications");

            return;
        }

        // Get all applications with results
        $applications = $exam->applications()->with('result')->get();
        $results = [];

        foreach ($applications as $application) {
            if ($application->result) {
                $results[] = [
                    'application_id' => $application->id,
                    'grade' => $application->result->grade,
                    'status' => $application->result->status,
                    'decision_type' => $application->result->decision_type,
                    'notes' => $application->result->notes,
                ];
            }
        }

        if (empty($results)) {
            $this->warn('  No results found for this exam');

            return;
        }

        try {
            $processed = $action->execute($exam, $results);
            $this->info("  ✅ Processed {$processed['statistics']['total']} results");
            $this->line("  Approved: {$processed['statistics']['approved']}");
            $this->line("  Rejected: {$processed['statistics']['rejected']}");
        } catch (\Throwable $e) {
            $this->error("  ❌ Error: {$e->getMessage()}");
        }

        $this->newLine();
    }
}
