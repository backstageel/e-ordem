<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\ResidencyApplication;
use App\Models\ResidencyProgram;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ResidencyApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active members
        $members = Member::where('status', 'active')->take(20)->get();

        // Get all residency programs
        $programs = ResidencyProgram::all();

        // Get evaluator users
        $evaluators = User::whereHas('roles', function ($query) {
            $query->where('name', 'evaluator');
        })->get();

        // If no evaluators found, use any users
        if ($evaluators->isEmpty()) {
            $evaluators = User::take(3)->get();
        }

        // Statuses for applications
        $statuses = ['pending', 'approved', 'rejected', 'in_progress', 'completed', 'cancelled'];

        // Create applications
        $applications = [];

        foreach ($members as $index => $member) {
            // Assign each member to 1-2 programs randomly
            $programCount = rand(1, 2);
            $selectedPrograms = $programs->random($programCount);

            foreach ($selectedPrograms as $program) {
                $status = $statuses[array_rand($statuses)];
                $submissionDate = Carbon::now()->subDays(rand(1, 60));

                $application = [
                    'member_id' => $member->id,
                    'residency_program_id' => $program->id,
                    'residency_location_id' => null, // Will be set later if needed
                    'status' => $status,
                    'application_date' => $submissionDate,
                    'approval_date' => in_array($status, ['approved', 'in_progress', 'completed']) ? $submissionDate->copy()->addDays(rand(1, 14)) : null,
                    'start_date' => in_array($status, ['in_progress', 'completed']) ? $submissionDate->copy()->addDays(rand(15, 30)) : null,
                    'expected_completion_date' => in_array($status, ['in_progress', 'completed']) ? $submissionDate->copy()->addMonths($program->duration_months) : null,
                    'actual_completion_date' => $status === 'completed' ? $submissionDate->copy()->addMonths($program->duration_months)->addDays(rand(-30, 30)) : null,
                    'rejection_reason' => $status === 'rejected' ? 'Não atende aos critérios mínimos de seleção.' : null,
                    'notes' => rand(0, 1) ? 'Observações sobre a candidatura do Dr(a). '.$member->full_name : null,
                    'approved_by' => in_array($status, ['approved', 'in_progress', 'completed']) ? $evaluators->random()->id : null,
                    'is_paid' => in_array($status, ['approved', 'in_progress', 'completed']) ? rand(0, 1) : false,
                    'payment_reference' => null,
                    'payment_date' => null,
                    'payment_amount' => null,
                    'created_at' => $submissionDate,
                    'updated_at' => in_array($status, ['approved', 'rejected', 'in_progress', 'completed']) ? $submissionDate->copy()->addDays(rand(1, 14)) : $submissionDate,
                ];

                $applications[] = $application;
            }
        }

        // Insert applications
        foreach ($applications as $application) {
            ResidencyApplication::create($application);
        }
    }
}
