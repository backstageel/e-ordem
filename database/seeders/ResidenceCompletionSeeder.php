<?php

namespace Database\Seeders;

use App\Models\ResidenceCompletion;
use App\Models\ResidenceResident;
use Illuminate\Database\Seeder;

class ResidenceCompletionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get completed residents
        $completedResidents = ResidenceResident::where('status', 'completed')->get();

        // Create completions for each completed resident
        foreach ($completedResidents as $resident) {
            // Get the actual end date or expected end date
            $completionDate = $resident->actual_end_date ?? $resident->expected_end_date;

            // Get evaluations for this resident
            $evaluations = $resident->evaluations ?? collect();

            // Calculate final score based on evaluations or generate a random score
            $finalScore = $this->calculateFinalScore($evaluations);

            // Create completion record
            ResidenceCompletion::create([
                'resident_id' => $resident->id,
                'completion_date' => $completionDate,
                'final_score' => $finalScore,
                'observations' => $this->getObservationForScore($finalScore),
                'created_at' => $completionDate,
                'updated_at' => $completionDate,
            ]);
        }
    }

    /**
     * Calculate final score based on evaluations or generate a random score.
     */
    private function calculateFinalScore($evaluations): float
    {
        if ($evaluations->isEmpty()) {
            // If no evaluations, generate a random score between 12 and 19
            return mt_rand(120, 190) / 10;
        }

        // Calculate average of theoretical and practical scores
        $theoreticalAvg = $evaluations->avg('theoretical_score');
        $practicalAvg = $evaluations->avg('practical_score');

        // Final score is weighted average (60% practical, 40% theoretical)
        $finalScore = ($practicalAvg * 0.6) + ($theoreticalAvg * 0.4);

        // Add small random factor
        $finalScore += (mt_rand(-5, 5) / 10);

        // Ensure score is between 0 and 20
        $finalScore = min(20, max(0, $finalScore));

        // Round to 1 decimal place
        return round($finalScore, 1);
    }

    /**
     * Get observation based on final score.
     */
    private function getObservationForScore(float $score): string
    {
        if ($score >= 18) {
            $observations = [
                'Concluiu o programa com distinção, demonstrando excelência em todas as áreas.',
                'Desempenho excepcional ao longo de todo o programa de residência.',
                'Recomendado para reconhecimento especial pela Ordem dos Médicos.',
                'Demonstrou habilidades e conhecimentos muito acima da média durante toda a residência.',
            ];
        } elseif ($score >= 16) {
            $observations = [
                'Concluiu o programa com ótimo aproveitamento em todas as áreas avaliadas.',
                'Demonstrou consistentemente alto nível de conhecimento e habilidades clínicas.',
                'Excelente evolução ao longo do programa, com grande potencial para a especialidade.',
                'Recomendado para futuras oportunidades acadêmicas e profissionais na área.',
            ];
        } elseif ($score >= 14) {
            $observations = [
                'Concluiu o programa com bom aproveitamento, atendendo a todos os requisitos.',
                'Demonstrou boa evolução nas habilidades clínicas e conhecimentos teóricos.',
                'Bom desempenho geral, com capacidade para exercer a especialidade com competência.',
                'Completou satisfatoriamente todas as etapas do programa de residência.',
            ];
        } else {
            $observations = [
                'Concluiu o programa atendendo aos requisitos mínimos necessários.',
                'Demonstrou evolução adequada, mas com áreas que ainda necessitam desenvolvimento.',
                'Recomenda-se educação médica continuada para aprimoramento na especialidade.',
                'Aprovado com o mínimo necessário para conclusão do programa.',
            ];
        }

        return $observations[array_rand($observations)];
    }
}
