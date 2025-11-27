<?php

namespace Modules\Registration\Database\Seeders;

use App\Enums\RegistrationStatus;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all persons
        $persons = Person::all();

        // Get all registration types
        $registrationTypes = RegistrationType::all();

        // Get admin users for approval
        $adminUsers = User::where('email', 'like', '%@example.com')->take(5)->get();

        // If no admin users, create one
        if ($adminUsers->isEmpty()) {
            $adminUsers = collect([
                User::create([
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]),
            ]);
        }

        // Find the person with email medico@hostmoz.net
        $targetPerson = Person::where('email', 'medico@hostmoz.net')->first();

        // Possible statuses for registrations
        $statuses = [
            RegistrationStatus::SUBMITTED,
            RegistrationStatus::APPROVED,
            RegistrationStatus::REJECTED,
            RegistrationStatus::EXPIRED,
            RegistrationStatus::ARCHIVED,
        ];

        // Create 3 registrations for the target person if found
        if ($targetPerson) {
            for ($i = 0; $i < 3; $i++) {
                // Select a random registration type
                $registrationType = $registrationTypes->random();

                // Always set status to approved for these registrations
                $status = RegistrationStatus::APPROVED;

                // Generate dates
                $submissionDate = now()->subDays(rand(1, 365));
                $approvalDate = (clone $submissionDate)->addDays(rand(1, 30));

                // Payment details
                $isPaid = true;
                $paymentReference = 'PAY-'.strtoupper(Str::random(8));
                $paymentDate = (clone $submissionDate)->addDays(rand(0, 5));
                $paymentAmount = $registrationType->fee;

                // Documents validation
                $documentsValidated = true;

                // Create the registration
                Registration::create([
                    'person_id' => $targetPerson->id,
                    'registration_type_id' => $registrationType->id,
                    'registration_number' => 'REG-'.date('Y').'-MEDICO-'.($i + 1).'-'.Str::random(4),
                    'status' => $status,
                    'submission_date' => $submissionDate,
                    'approval_date' => $approvalDate,
                    'rejection_reason' => null,
                    'notes' => 'Registration for person with email medico@hostmoz.net',
                    'approved_by' => $adminUsers->random()->id,
                    'is_paid' => $isPaid,
                    'payment_reference' => $paymentReference,
                    'payment_date' => $paymentDate,
                    'payment_amount' => $paymentAmount,
                    'documents_validated' => $documentsValidated,
                    'is_renewal' => $i > 0, // First one is not a renewal, others are
                    'previous_registration_id' => $i > 0 ? $targetPerson->registrations()->latest()->first()->id : null,
                    'created_at' => $submissionDate,
                    'updated_at' => $approvalDate,
                ]);
            }
        }

        // Create remaining registrations
        for ($i = 0; $i < 30; $i++) {
            // Select a random person
            $person = $persons->random();

            // Select a random registration type
            $registrationType = $registrationTypes->random();

            // Determine if this is a renewal
            $isRenewal = rand(0, 3) == 0; // 25% chance of being a renewal

            // Get previous registration if this is a renewal
            $previousRegistrationId = null;
            if ($isRenewal && $person->registrations->count() > 0) {
                $previousRegistrationId = $person->registrations->random()->id;
            } else {
                $isRenewal = false; // Reset to false if no previous registrations
            }

            // Generate random status with weighted probabilities
            $statusWeights = [
                RegistrationStatus::SUBMITTED->value => 20,
                RegistrationStatus::APPROVED->value => 50,
                RegistrationStatus::REJECTED->value => 10,
                RegistrationStatus::EXPIRED->value => 15,
                RegistrationStatus::ARCHIVED->value => 5,
            ];

            $statusValue = $this->getRandomWeightedElement($statusWeights);
            $status = RegistrationStatus::from($statusValue);

            // Generate dates based on status
            $submissionDate = now()->subDays(rand(1, 365));
            $approvalDate = null;

            if ($status === RegistrationStatus::APPROVED) {
                $approvalDate = (clone $submissionDate)->addDays(rand(1, 30));
            } elseif ($status === RegistrationStatus::EXPIRED) {
                $approvalDate = (clone $submissionDate)->addDays(rand(1, 30));
            } elseif ($status === RegistrationStatus::REJECTED) {
                $approvalDate = (clone $submissionDate)->addDays(rand(1, 30));
            }

            // Payment details
            $isPaid = in_array($status, [RegistrationStatus::APPROVED, RegistrationStatus::EXPIRED]) || (rand(0, 1) == 1 && $status === RegistrationStatus::SUBMITTED);
            $paymentReference = $isPaid ? 'PAY-'.strtoupper(Str::random(8)) : null;
            $paymentDate = $isPaid ? (clone $submissionDate)->addDays(rand(0, 5)) : null;
            $paymentAmount = $isPaid ? $registrationType->fee : null;

            // Documents validation
            $documentsValidated = in_array($status, [RegistrationStatus::APPROVED, RegistrationStatus::EXPIRED]) || (rand(0, 1) == 1 && $status === RegistrationStatus::SUBMITTED);

            // Create the registration
            Registration::create([
                'person_id' => $person->id,
                'registration_type_id' => $registrationType->id,
                'registration_number' => 'REG-'.date('Y').'-'.str_pad($i + 1, 5, '0', STR_PAD_LEFT).'-'.Str::random(4),
                'status' => $status,
                'submission_date' => $submissionDate,
                'approval_date' => $approvalDate,
                'rejection_reason' => $status === RegistrationStatus::REJECTED ? $this->getRandomRejectionReason() : null,
                'notes' => rand(0, 1) == 1 ? $this->getRandomNote() : null,
                'approved_by' => in_array($status, [RegistrationStatus::APPROVED, RegistrationStatus::REJECTED, RegistrationStatus::EXPIRED]) ? $adminUsers->random()->id : null,
                'is_paid' => $isPaid,
                'payment_reference' => $paymentReference,
                'payment_date' => $paymentDate,
                'payment_amount' => $paymentAmount,
                'documents_validated' => $documentsValidated,
                'is_renewal' => $isRenewal,
                'previous_registration_id' => $previousRegistrationId,
                'created_at' => $submissionDate,
                'updated_at' => $approvalDate ?? $submissionDate,
            ]);
        }
    }

    /**
     * Get a random element based on weights.
     */
    private function getRandomWeightedElement(array $weightedValues)
    {
        $rand = rand(1, array_sum($weightedValues));

        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }

        return array_key_first($weightedValues);
    }

    /**
     * Get a random rejection reason.
     */
    private function getRandomRejectionReason(): string
    {
        $reasons = [
            'Documentação incompleta ou inválida.',
            'Qualificações não atendem aos requisitos mínimos.',
            'Informações inconsistentes nos documentos apresentados.',
            'Falta de comprovação de experiência profissional.',
            'Diploma não reconhecido pelo Ministério da Educação.',
            'Histórico de má conduta profissional.',
            'Não cumprimento dos requisitos éticos da profissão.',
            'Pendências com o conselho de classe anterior.',
            'Documentos com suspeita de falsificação.',
            'Não comparecimento à entrevista obrigatória.',
        ];

        return $reasons[array_rand($reasons)];
    }

    /**
     * Get a random note.
     */
    private function getRandomNote(): string
    {
        $notes = [
            'Processo analisado com prioridade devido à necessidade urgente de profissionais.',
            'Candidato com excelente histórico acadêmico e profissional.',
            'Recomendação especial da instituição de formação.',
            'Profissional com experiência internacional relevante.',
            'Especialidade em área de alta demanda no sistema de saúde.',
            'Processo com pendências menores que foram resolvidas durante a análise.',
            'Candidato solicitou agilidade no processo devido a oferta de emprego.',
            'Documentação complementar foi solicitada durante o processo.',
            'Verificação adicional de autenticidade dos documentos foi realizada.',
            'Profissional com histórico de trabalho em áreas remotas do país.',
        ];

        return $notes[array_rand($notes)];
    }
}
