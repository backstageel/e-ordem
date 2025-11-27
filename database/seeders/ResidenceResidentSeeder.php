<?php

namespace Database\Seeders;

use App\Models\ResidenceApplication;
use App\Models\ResidenceResident;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ResidenceResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get approved applications
        $approvedApplications = ResidenceApplication::where('status', 'approved')->get();

        // Statuses for residents
        $statuses = ['active', 'inactive', 'completed', 'withdrawn'];
        $statusWeights = [70, 10, 15, 5]; // Weights for random selection (70% active, 10% inactive, etc.)

        // Create residents from approved applications
        foreach ($approvedApplications as $application) {
            // Get program duration in months
            $program = $application->program;
            $duration = $program->duration;

            // Calculate start and end dates
            $startDate = Carbon::now()->subMonths(rand(0, $duration - 1))->startOfMonth();
            $expectedEndDate = $startDate->copy()->addMonths($duration);

            // Determine status based on time elapsed and random factors
            $timeElapsed = $startDate->diffInMonths(Carbon::now());
            $timePercentage = ($timeElapsed / $duration) * 100;

            // Weighted random status selection
            $status = $this->getWeightedRandomStatus($statuses, $statusWeights, $timePercentage);

            // Set actual end date based on status
            $actualEndDate = null;
            if ($status === 'completed') {
                $actualEndDate = $expectedEndDate->copy()->subDays(rand(0, 30));
            } elseif ($status === 'withdrawn') {
                $actualEndDate = $startDate->copy()->addMonths(rand(1, max(1, intval($duration / 2))));
            }

            // Create resident
            ResidenceResident::create([
                'member_id' => $application->member_id,
                'program_id' => $application->program_id,
                'start_date' => $startDate,
                'expected_end_date' => $expectedEndDate,
                'actual_end_date' => $actualEndDate,
                'status' => $status,
                'observations' => $this->getRandomObservation($status),
                'created_at' => $startDate,
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Get a weighted random status based on time percentage and weights.
     */
    private function getWeightedRandomStatus(array $statuses, array $weights, float $timePercentage): string
    {
        // Adjust weights based on time percentage
        if ($timePercentage < 10) {
            // Just started - more likely to be active or withdrawn
            $weights = [85, 5, 0, 10]; // active, inactive, completed, withdrawn
        } elseif ($timePercentage >= 100) {
            // Should be completed
            $weights = [5, 5, 85, 5]; // active, inactive, completed, withdrawn
        } elseif ($timePercentage >= 90) {
            // Almost completed - more likely to be active or completed
            $weights = [70, 5, 20, 5]; // active, inactive, completed, withdrawn
        }

        // Create cumulative weights
        $cumulativeWeights = [];
        $cumulative = 0;

        foreach ($weights as $weight) {
            $cumulative += $weight;
            $cumulativeWeights[] = $cumulative;
        }

        // Get random value
        $randomValue = mt_rand(1, $cumulative);

        // Find corresponding status
        foreach ($cumulativeWeights as $index => $weight) {
            if ($randomValue <= $weight) {
                return $statuses[$index];
            }
        }

        // Default to active
        return 'active';
    }

    /**
     * Get random observation based on status.
     */
    private function getRandomObservation(string $status): ?string
    {
        $observations = [
            'active' => [
                'Residente com bom desempenho.',
                'Cumprindo todas as atividades conforme programado.',
                'Demonstra grande interesse e dedicação.',
                null, // 25% chance of no observation
            ],
            'inactive' => [
                'Residente temporariamente afastado por motivos de saúde.',
                'Licença temporária solicitada pelo residente.',
                'Afastado por questões administrativas.',
                'Suspensão temporária por desempenho insatisfatório.',
            ],
            'completed' => [
                'Concluiu o programa com excelente desempenho.',
                'Completou todos os requisitos do programa com sucesso.',
                'Aprovado em todas as avaliações com boas notas.',
                'Recomendado para contratação permanente.',
            ],
            'withdrawn' => [
                'Desistiu do programa por motivos pessoais.',
                'Transferido para outro programa de residência.',
                'Desistência por incompatibilidade com a especialidade.',
                'Afastado definitivamente por desempenho insatisfatório.',
            ],
        ];

        return $observations[$status][array_rand($observations[$status])];
    }
}
