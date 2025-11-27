<?php

namespace Modules\Exam\Database\Seeders;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExamApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get exams
        $exams = Exam::all();

        if ($exams->isEmpty()) {
            $this->command->info('No exams found. Please run ExamSeeder first.');

            return;
        }

        // Get users (candidates)
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        if ($users->isEmpty()) {
            $users = User::take(10)->get();
        }

        // Find the person with email medico@hostmoz.net and get their user
        $targetPerson = \App\Models\Person::where('email', 'medico@hostmoz.net')->first();
        $targetUser = $targetPerson ? $targetPerson->user : null;

        // Sample specialties
        $specialties = [
            'Medicina Geral',
            'Cirurgia Geral',
            'Pediatria',
            'Cardiologia',
            'Anestesiologia',
            'Ginecologia e Obstetrícia',
            'Ortopedia',
            'Neurologia',
            'Psiquiatria',
            'Dermatologia',
            'Oftalmologia',
            'Otorrinolaringologia',
            'Urologia',
            'Radiologia',
            'Oncologia',
        ];

        // Sample institutions
        $institutions = [
            'Hospital Central de Maputo',
            'Hospital Geral de Mavalane',
            'Hospital Provincial de Xai-Xai',
            'Hospital Provincial de Inhambane',
            'Hospital Provincial de Quelimane',
            'Hospital Provincial de Tete',
            'Hospital Provincial de Lichinga',
            'Hospital Provincial de Pemba',
            'Clínica Privada de Maputo',
            'Centro de Saúde de Polana Caniço',
            'Universidade Eduardo Mondlane',
            'Instituto Superior de Ciências de Saúde',
            'Ministério da Saúde',
            'ONG Médicos Sem Fronteiras',
            'Centro de Investigação em Saúde de Manhiça',
        ];

        // Sample experience years
        $experienceYears = ['menos_1', '1_3', '3_5', '5_10', 'mais_10'];

        // Sample statuses
        $statuses = ['draft', 'submitted', 'in_review', 'approved', 'rejected', 'documents_pending'];

        // Create applications
        $applications = [];

        // Create 3 applications for the target user if found
        if ($targetUser && $exams->isNotEmpty()) {
            for ($i = 0; $i < 3; $i++) {
                $exam = $exams->random();
                $specialty = $specialties[array_rand($specialties)];
                $status = 'approved';

                $application = [
                    'exam_id' => $exam->id,
                    'user_id' => $targetUser->id,
                    'exam_type' => array_rand(['certificacao' => 0, 'especialidade' => 1, 'revalidacao' => 2, 'recertificacao' => 3]),
                    'specialty' => $specialty,
                    'other_specialty' => null,
                    'preferred_date' => Carbon::now()->addDays(rand(1, 30))->format('Y-m-d'),
                    'preferred_location' => $institutions[array_rand($institutions)],
                    'cv_path' => 'exam-applications/cv/sample_cv_'.$targetUser->id.'.pdf',
                    'payment_proof_path' => 'exam-applications/payment/sample_payment_'.$targetUser->id.'.pdf',
                    'recommendation_letter_path' => 'exam-applications/recommendation/sample_recommendation_'.$targetUser->id.'.pdf',
                    'additional_documents_path' => 'exam-applications/additional/sample_additional_'.$targetUser->id.'.pdf',
                    'experience_summary' => 'Experiência profissional em '.$specialty.' com foco em atendimento clínico e pesquisa.',
                    'experience_years' => $experienceYears[array_rand($experienceYears)],
                    'current_institution' => $institutions[array_rand($institutions)],
                    'special_needs' => null,
                    'observations' => 'Exam application for user with email medico@hostmoz.net',
                    'terms_accepted' => true,
                    'status' => $status,
                    'rejection_reason' => null,
                    'is_confirmed' => true,
                    'is_present' => true,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 15)),
                ];

                $applications[] = $application;
            }
        }

        // For each exam, create 5-15 applications
        foreach ($exams as $exam) {
            $numApplications = rand(5, 15);

            for ($i = 0; $i < $numApplications; $i++) {
                $user = $users->random();
                $specialty = $specialties[array_rand($specialties)];
                $status = $statuses[array_rand($statuses)];

                // If exam is completed, make sure some applications are approved
                if ($exam->status === 'completed' && $i < 3) {
                    $status = 'approved';
                }

                $application = [
                    'exam_id' => $exam->id,
                    'user_id' => $user->id,
                    'exam_type' => array_rand(['certificacao' => 0, 'especialidade' => 1, 'revalidacao' => 2, 'recertificacao' => 3]),
                    'specialty' => $specialty,
                    'other_specialty' => null,
                    'preferred_date' => Carbon::now()->addDays(rand(1, 30))->format('Y-m-d'),
                    'preferred_location' => $institutions[array_rand($institutions)],
                    'cv_path' => 'exam-applications/cv/sample_cv_'.$user->id.'.pdf',
                    'payment_proof_path' => 'exam-applications/payment/sample_payment_'.$user->id.'.pdf',
                    'recommendation_letter_path' => rand(0, 1) ? 'exam-applications/recommendation/sample_recommendation_'.$user->id.'.pdf' : null,
                    'additional_documents_path' => rand(0, 1) ? 'exam-applications/additional/sample_additional_'.$user->id.'.pdf' : null,
                    'experience_summary' => 'Experiência profissional em '.$specialty.' com foco em atendimento clínico e pesquisa.',
                    'experience_years' => $experienceYears[array_rand($experienceYears)],
                    'current_institution' => $institutions[array_rand($institutions)],
                    'special_needs' => rand(0, 10) === 0 ? 'Necessita de acessibilidade para cadeira de rodas' : null,
                    'observations' => rand(0, 1) ? 'Observações adicionais sobre a candidatura.' : null,
                    'terms_accepted' => true,
                    'status' => $status,
                    'rejection_reason' => $status === 'rejected' ? 'Documentação incompleta ou inadequada.' : null,
                    'is_confirmed' => $status === 'approved',
                    'is_present' => $status === 'approved' && $exam->status === 'completed' && rand(0, 10) > 2, // 80% chance of being present if approved and exam completed
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 15)),
                ];

                $applications[] = $application;
            }
        }

        // Insert applications
        foreach ($applications as $application) {
            ExamApplication::create($application);
        }
    }
}
