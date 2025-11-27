<?php

namespace Modules\Exam\Database\Seeders;

use App\Models\ExamApplication;
use App\Models\ExamResult;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExamResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get applications for completed exams
        $applications = ExamApplication::whereHas('exam', function ($query) {
            $query->where('status', 'completed');
        })->get();

        if ($applications->isEmpty()) {
            $this->command->info('No applications for completed exams found. Please run ExamSeeder and ExamApplicationSeeder first.');

            return;
        }

        // Get evaluator users
        $evaluators = User::whereHas('roles', function ($query) {
            $query->where('name', 'evaluator');
        })->get();

        if ($evaluators->isEmpty()) {
            $evaluators = User::take(2)->get();
        }

        // Sample decision types
        $decisionTypes = [
            'aprovacao_automatica',
            'aprovacao_manual',
            'reprovacao_automatica',
            'reprovacao_manual',
            'recurso',
        ];

        // Sample notes
        $notes = [
            'Excelente desempenho em todas as áreas avaliadas.',
            'Bom desempenho, com algumas áreas para melhoria.',
            'Desempenho satisfatório, atendendo aos requisitos mínimos.',
            'Desempenho abaixo do esperado, necessita melhorar conhecimentos teóricos.',
            'Desempenho insatisfatório, não atendeu aos requisitos mínimos.',
            'Candidato ausente no dia do exame.',
            'Candidato eliminado por violação das regras do exame.',
            'Em processo de recurso, aguardando reavaliação.',
            null,
        ];

        // Create results
        $results = [];

        foreach ($applications as $application) {
            // Skip if not approved or not present
            if (! $application->is_confirmed || ! $application->is_present) {
                continue;
            }

            $exam = $application->exam;
            $evaluator = $evaluators->random();

            // Determine status (presente, ausente, eliminado)
            $status = 'presente';
            if (rand(0, 10) === 0) { // 10% chance of being absent
                $status = 'ausente';
            } elseif (rand(0, 20) === 0) { // 5% chance of being eliminated
                $status = 'eliminado';
            }

            // Calculate grade if present
            $grade = null;
            $decision = null;
            $decisionType = null;

            if ($status === 'presente') {
                // Generate a grade between 0 and 20, with most grades between 8 and 18
                $grade = round(max(0, min(20, rand(80, 180) / 10)), 1);

                // Determine decision based on grade and minimum grade
                if ($grade >= $exam->minimum_grade) {
                    $decision = 'aprovado';
                    $decisionType = rand(0, 1) ? 'aprovacao_automatica' : 'aprovacao_manual';
                } else {
                    $decision = 'reprovado';
                    $decisionType = rand(0, 1) ? 'reprovacao_automatica' : 'reprovacao_manual';
                }

                // 5% chance of being in appeal regardless of grade
                if (rand(0, 20) === 0) {
                    $decision = 'recurso';
                    $decisionType = 'recurso';
                }
            }

            $result = [
                'exam_application_id' => $application->id,
                'grade' => $grade,
                'status' => $status,
                'decision' => $decision,
                'decision_type' => $decisionType,
                'notes' => $notes[array_rand($notes)],
                'certificate_path' => ($status === 'presente' && $decision === 'aprovado') ? 'exam-results/certificates/certificate_'.$application->user_id.'_'.$exam->id.'.pdf' : null,
                'notification_sent' => rand(0, 1),
                'evaluated_by' => $evaluator->id,
                'evaluated_at' => Carbon::now()->subDays(rand(1, 15)),
                'created_at' => Carbon::now()->subDays(rand(1, 15)),
                'updated_at' => Carbon::now()->subDays(rand(0, 10)),
            ];

            $results[] = $result;
        }

        // Insert results
        foreach ($results as $result) {
            ExamResult::create($result);
        }
    }
}
