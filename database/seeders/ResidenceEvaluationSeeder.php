<?php

namespace Database\Seeders;

use App\Models\ResidenceEvaluation;
use App\Models\ResidenceResident;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ResidenceEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active and completed residents
        $residents = ResidenceResident::whereIn('status', ['active', 'completed'])->get();

        // Get evaluator users
        $evaluators = User::whereHas('roles', function ($query) {
            $query->where('name', 'evaluator');
        })->get();

        // If no evaluators found, use any users
        if ($evaluators->isEmpty()) {
            $evaluators = User::take(5)->get();
        }

        // Periods for evaluations
        $periods = [
            '1º Trimestre', '2º Trimestre', '3º Trimestre', '4º Trimestre',
            '1º Semestre', '2º Semestre',
            'Avaliação Anual',
            'Avaliação Final',
        ];

        // Create evaluations for each resident
        foreach ($residents as $resident) {
            // Calculate how many evaluations to create based on time in program
            $startDate = $resident->start_date;
            $endDate = $resident->actual_end_date ?? ($resident->status === 'completed' ? $resident->expected_end_date : Carbon::now());

            $monthsInProgram = $startDate->diffInMonths($endDate);
            $evaluationsCount = min(max(1, intval($monthsInProgram / 3)), 8); // At least 1, at most 8 evaluations

            // Create evaluations
            for ($i = 0; $i < $evaluationsCount; $i++) {
                // Calculate evaluation date
                $evaluationDate = $startDate->copy()->addMonths(($i + 1) * 3)->subDays(rand(0, 30));

                // Ensure evaluation date is not in the future or after end date
                if ($evaluationDate->gt(Carbon::now()) || $evaluationDate->gt($endDate)) {
                    $evaluationDate = $endDate->copy()->subDays(rand(0, 30));
                }

                // Generate scores
                $theoreticalScore = $this->generateScore($i, $evaluationsCount);
                $practicalScore = $this->generateScore($i, $evaluationsCount);

                // Create evaluation
                ResidenceEvaluation::create([
                    'resident_id' => $resident->id,
                    'evaluator_id' => $evaluators->random()->id,
                    'evaluation_date' => $evaluationDate,
                    'period' => $periods[$i % count($periods)],
                    'theoretical_score' => $theoreticalScore,
                    'practical_score' => $practicalScore,
                    'observations' => $this->getObservationForScores($theoreticalScore, $practicalScore),
                    'created_at' => $evaluationDate,
                    'updated_at' => $evaluationDate,
                ]);
            }
        }
    }

    /**
     * Generate a score that tends to improve over time.
     */
    private function generateScore(int $evaluationIndex, int $totalEvaluations): float
    {
        // Base score between 10 and 16
        $baseScore = mt_rand(100, 160) / 10;

        // Add improvement factor (later evaluations tend to be better)
        $improvementFactor = ($evaluationIndex / max(1, $totalEvaluations - 1)) * 4;

        // Add some randomness
        $randomFactor = mt_rand(-10, 10) / 10;

        // Calculate final score (between 0 and 20)
        $score = min(20, max(0, $baseScore + $improvementFactor + $randomFactor));

        // Round to 1 decimal place
        return round($score, 1);
    }

    /**
     * Get observation based on scores.
     */
    private function getObservationForScores(float $theoreticalScore, float $practicalScore): ?string
    {
        $averageScore = ($theoreticalScore + $practicalScore) / 2;

        if ($averageScore >= 18) {
            $observations = [
                'Desempenho excepcional em todos os aspectos avaliados.',
                'Demonstra conhecimento e habilidades muito acima da média.',
                'Excelente capacidade de diagnóstico e tratamento.',
                'Recomendado para menção honrosa no programa.',
            ];
        } elseif ($averageScore >= 16) {
            $observations = [
                'Muito bom desempenho, demonstrando sólido conhecimento teórico e prático.',
                'Ótima evolução no programa, com boas habilidades clínicas.',
                'Demonstra grande potencial para a especialidade.',
                'Desempenho consistentemente acima da média.',
            ];
        } elseif ($averageScore >= 14) {
            $observations = [
                'Bom desempenho, atendendo às expectativas do programa.',
                'Demonstra conhecimento adequado e boa evolução nas habilidades práticas.',
                'Mantém bom relacionamento com pacientes e equipe.',
                'Evolução satisfatória no programa de residência.',
            ];
        } elseif ($averageScore >= 12) {
            $observations = [
                'Desempenho satisfatório, mas com aspectos a melhorar.',
                'Conhecimento teórico adequado, mas necessita desenvolver mais habilidades práticas.',
                'Recomenda-se maior dedicação aos estudos e prática clínica.',
                'Evolução dentro do esperado, mas com potencial para melhorar.',
            ];
        } else {
            $observations = [
                'Desempenho abaixo do esperado, necessitando melhorias significativas.',
                'Dificuldades na aplicação do conhecimento teórico na prática clínica.',
                'Recomenda-se acompanhamento mais próximo e plano de recuperação.',
                'Necessita dedicação adicional para atingir os objetivos do programa.',
            ];
        }

        return $observations[array_rand($observations)];
    }
}
