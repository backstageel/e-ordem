<?php

namespace App\Services\Exam;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\ExamResult;
use Illuminate\Support\Collection;

class ExamResultService
{
    public function calculateScore(array $answers, Exam $exam): float
    {
        // This is a simplified version - would need actual question structure
        $totalQuestions = $exam->questions_count ?? 100;
        $correctAnswers = count(array_filter($answers, fn ($answer) => $answer['correct'] ?? false));
        $totalScore = ($correctAnswers / $totalQuestions) * $exam->minimum_grade * 2; // Scale to 20

        return round($totalScore, 1);
    }

    public function processResults(Exam $exam, Collection $results): array
    {
        $processed = [];
        $statistics = [
            'total' => 0,
            'present' => 0,
            'absent' => 0,
            'eliminated' => 0,
            'approved' => 0,
            'rejected' => 0,
            'average_score' => 0,
            'pass_rate' => 0,
        ];

        foreach ($results as $resultData) {
            $application = ExamApplication::find($resultData['application_id']);
            if (! $application) {
                continue;
            }

            $statistics['total']++;

            // Create or update result
            $resultDataToSave = [
                'grade' => $resultData['grade'] ?? null,
                'status' => $resultData['status'],
                'decision_type' => $resultData['decision_type'] ?? null,
                'evaluated_by' => $resultData['evaluated_by'] ?? null,
                'evaluated_at' => now(),
            ];

            if (isset($resultData['notes'])) {
                $resultDataToSave['notes'] = $resultData['notes'];
            }

            $result = ExamResult::updateOrCreate(
                ['exam_application_id' => $application->id],
                $resultDataToSave
            );

            // Update application presence
            $application->is_present = ($resultData['status'] === 'presente');
            $application->save();

            // Update statistics
            if ($resultData['status'] === 'presente') {
                $statistics['present']++;
            } elseif ($resultData['status'] === 'ausente') {
                $statistics['absent']++;
            } elseif ($resultData['status'] === 'eliminado') {
                $statistics['eliminated']++;
            }

            // Determine decision based on grade
            if ($resultData['status'] === 'presente' && isset($resultData['grade'])) {
                if ($resultData['grade'] >= $exam->minimum_grade) {
                    $result->decision = 'aprovado';
                    $statistics['approved']++;
                } else {
                    $result->decision = 'reprovado';
                    $statistics['rejected']++;
                }
                $result->save();
            }

            $processed[] = $result;
        }

        // Calculate average score
        $scores = array_filter($processed, fn ($r) => $r->grade !== null);
        if (count($scores) > 0) {
            $statistics['average_score'] = round(
                array_sum(array_column($scores, 'grade')) / count($scores),
                1
            );
        }

        // Calculate pass rate
        $totalPresent = $statistics['present'];
        if ($totalPresent > 0) {
            $statistics['pass_rate'] = round(($statistics['approved'] / $totalPresent) * 100, 2);
        }

        return [
            'results' => $processed,
            'statistics' => $statistics,
        ];
    }

    public function generateAdmittedList(Exam $exam): Collection
    {
        return ExamApplication::where('exam_id', $exam->id)
            ->whereHas('result', function ($query) use ($exam) {
                $query->where('status', 'presente')
                    ->where('grade', '>=', $exam->minimum_grade)
                    ->where('decision', 'aprovado');
            })
            ->with(['user.person', 'result'])
            ->orderByRaw('(SELECT grade FROM exam_results WHERE exam_results.exam_application_id = exam_applications.id) DESC')
            ->get();
    }

    public function generateExcludedList(Exam $exam): Collection
    {
        return ExamApplication::where('exam_id', $exam->id)
            ->where(function ($query) use ($exam) {
                $query->whereHas('result', function ($q) {
                    $q->where('status', 'ausente')
                        ->orWhere('status', 'eliminado');
                })
                    ->orWhereHas('result', function ($q) use ($exam) {
                        $q->where('status', 'presente')
                            ->where(function ($sq) use ($exam) {
                                $sq->where('grade', '<', $exam->minimum_grade)
                                    ->orWhere('decision', 'reprovado');
                            });
                    })
                    ->orWhereDoesntHave('result');
            })
            ->with(['user.person', 'result'])
            ->get();
    }

    public function getStatistics(Exam $exam): array
    {
        $totalApplications = $exam->applications()->count();
        $approved = $exam->applications()
            ->whereHas('result', function ($query) use ($exam) {
                $query->where('status', 'presente')
                    ->where('grade', '>=', $exam->minimum_grade)
                    ->where('decision', 'aprovado');
            })
            ->count();

        $rejected = $exam->applications()
            ->whereHas('result', function ($query) {
                $query->where('decision', 'reprovado');
            })
            ->count();

        $absent = $exam->applications()
            ->whereHas('result', function ($query) {
                $query->where('status', 'ausente');
            })
            ->count();

        $averageScore = ExamResult::whereHas('application', function ($query) use ($exam) {
            $query->where('exam_id', $exam->id);
        })
            ->whereNotNull('grade')
            ->avg('grade');

        return [
            'total_applications' => $totalApplications,
            'approved' => $approved,
            'rejected' => $rejected,
            'absent' => $absent,
            'average_score' => $averageScore ? round($averageScore, 1) : 0,
            'pass_rate' => $totalApplications > 0 ? round(($approved / $totalApplications) * 100, 2) : 0,
        ];
    }
}
