<?php

namespace Database\Seeders;

use App\Models\ResidencyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResidencyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to act as coordinators
        $coordinators = User::take(10)->get();

        // If no users exist, create a default coordinator
        if ($coordinators->isEmpty()) {
            $defaultCoordinator = User::create([
                'name' => 'Dr. Coordenador Padrão',
                'email' => 'coordenador@ormm.mz',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $coordinators = collect([$defaultCoordinator]);
        }

        // Medical residency programs in Mozambique
        $programs = [
            [
                'name' => 'Residência Médica em Medicina Interna',
                'description' => 'Programa de residência médica em Medicina Interna com foco no diagnóstico e tratamento de doenças internas em adultos.',
                'specialty' => 'Medicina Interna',
                'duration_months' => 36,
                'fee' => 15000.00,
                'max_participants' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Cirurgia Geral',
                'description' => 'Programa de residência médica em Cirurgia Geral com treinamento em procedimentos cirúrgicos básicos e avançados.',
                'specialty' => 'Cirurgia Geral',
                'duration_months' => 48,
                'fee' => 18000.00,
                'max_participants' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Pediatria',
                'description' => 'Programa de residência médica em Pediatria com foco no cuidado médico de crianças e adolescentes.',
                'specialty' => 'Pediatria',
                'duration_months' => 36,
                'fee' => 16000.00,
                'max_participants' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Ginecologia e Obstetrícia',
                'description' => 'Programa de residência médica em Ginecologia e Obstetrícia com treinamento em saúde da mulher e partos.',
                'specialty' => 'Ginecologia e Obstetrícia',
                'duration_months' => 48,
                'fee' => 17000.00,
                'max_participants' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Cardiologia',
                'description' => 'Programa de residência médica em Cardiologia com foco no diagnóstico e tratamento de doenças cardíacas.',
                'specialty' => 'Cardiologia',
                'duration_months' => 36,
                'fee' => 20000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Ortopedia',
                'description' => 'Programa de residência médica em Ortopedia com treinamento em cirurgia ortopédica e traumatologia.',
                'specialty' => 'Ortopedia',
                'duration_months' => 48,
                'fee' => 19000.00,
                'max_participants' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Oftalmologia',
                'description' => 'Programa de residência médica em Oftalmologia com foco no diagnóstico e tratamento de doenças oculares.',
                'specialty' => 'Oftalmologia',
                'duration_months' => 36,
                'fee' => 18000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Psiquiatria',
                'description' => 'Programa de residência médica em Psiquiatria com foco no diagnóstico e tratamento de transtornos mentais.',
                'specialty' => 'Psiquiatria',
                'duration_months' => 36,
                'fee' => 15000.00,
                'max_participants' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Dermatologia',
                'description' => 'Programa de residência médica em Dermatologia com foco no diagnóstico e tratamento de doenças da pele.',
                'specialty' => 'Dermatologia',
                'duration_months' => 36,
                'fee' => 16000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Neurologia',
                'description' => 'Programa de residência médica em Neurologia com foco no diagnóstico e tratamento de doenças neurológicas.',
                'specialty' => 'Neurologia',
                'duration_months' => 36,
                'fee' => 19000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Urologia',
                'description' => 'Programa de residência médica em Urologia com foco no diagnóstico e tratamento de doenças urológicas.',
                'specialty' => 'Urologia',
                'duration_months' => 48,
                'fee' => 18000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Otorrinolaringologia',
                'description' => 'Programa de residência médica em Otorrinolaringologia com foco no diagnóstico e tratamento de doenças do ouvido, nariz e garganta.',
                'specialty' => 'Otorrinolaringologia',
                'duration_months' => 36,
                'fee' => 17000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Anestesiologia',
                'description' => 'Programa de residência médica em Anestesiologia com foco em anestesia e cuidados perioperatórios.',
                'specialty' => 'Anestesiologia',
                'duration_months' => 36,
                'fee' => 18000.00,
                'max_participants' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Radiologia',
                'description' => 'Programa de residência médica em Radiologia com foco no diagnóstico por imagem.',
                'specialty' => 'Radiologia',
                'duration_months' => 48,
                'fee' => 19000.00,
                'max_participants' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Medicina Familiar',
                'description' => 'Programa de residência médica em Medicina Familiar com foco no cuidado primário e medicina comunitária.',
                'specialty' => 'Medicina Familiar',
                'duration_months' => 36,
                'fee' => 14000.00,
                'max_participants' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Medicina Tropical',
                'description' => 'Programa de residência médica em Medicina Tropical com foco em doenças tropicais e infecciosas.',
                'specialty' => 'Medicina Tropical',
                'duration_months' => 36,
                'fee' => 16000.00,
                'max_participants' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Pneumologia',
                'description' => 'Programa de residência médica em Pneumologia com foco no diagnóstico e tratamento de doenças respiratórias.',
                'specialty' => 'Pneumologia',
                'duration_months' => 36,
                'fee' => 17000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Nefrologia',
                'description' => 'Programa de residência médica em Nefrologia com foco no diagnóstico e tratamento de doenças renais.',
                'specialty' => 'Nefrologia',
                'duration_months' => 36,
                'fee' => 18000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Oncologia',
                'description' => 'Programa de residência médica em Oncologia com foco no diagnóstico e tratamento do câncer.',
                'specialty' => 'Oncologia',
                'duration_months' => 36,
                'fee' => 20000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Residência Médica em Endocrinologia',
                'description' => 'Programa de residência médica em Endocrinologia com foco no diagnóstico e tratamento de doenças endócrinas.',
                'specialty' => 'Endocrinologia',
                'duration_months' => 36,
                'fee' => 17000.00,
                'max_participants' => 4,
                'is_active' => true,
            ],
        ];

        // Create residency programs
        foreach ($programs as $index => $programData) {
            $coordinator = $coordinators->random();

            ResidencyProgram::create([
                'name' => $programData['name'],
                'description' => $programData['description'],
                'specialty' => $programData['specialty'],
                'duration_months' => $programData['duration_months'],
                'fee' => $programData['fee'],
                'max_participants' => $programData['max_participants'],
                'is_active' => $programData['is_active'],
                'coordinator_id' => $coordinator->id,
            ]);
        }

        $this->command->info('Created '.count($programs).' residency programs.');
    }
}
