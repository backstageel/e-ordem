<?php

namespace Database\Seeders;

use App\Models\ResidenceProgram;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ResidenceProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Programa de Residência em Medicina Interna',
                'specialty' => 'Medicina Interna',
                'institution' => 'Hospital Central de Maputo',
                'vacancies' => 10,
                'duration' => 36, // 36 months
                'description' => 'Programa de residência médica em Medicina Interna com duração de 3 anos, focado na formação de especialistas em diagnóstico e tratamento de doenças em adultos.',
                'status' => 'active',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->startOfMonth()->addMonths(36),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Programa de Residência em Pediatria',
                'specialty' => 'Pediatria',
                'institution' => 'Hospital Central de Maputo',
                'vacancies' => 8,
                'duration' => 36, // 36 months
                'description' => 'Programa de residência médica em Pediatria com duração de 3 anos, focado na formação de especialistas em saúde infantil.',
                'status' => 'active',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->startOfMonth()->addMonths(36),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Programa de Residência em Cirurgia Geral',
                'specialty' => 'Cirurgia Geral',
                'institution' => 'Hospital Central de Maputo',
                'vacancies' => 6,
                'duration' => 48, // 48 months
                'description' => 'Programa de residência médica em Cirurgia Geral com duração de 4 anos, focado na formação de especialistas em procedimentos cirúrgicos.',
                'status' => 'active',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->startOfMonth()->addMonths(48),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Programa de Residência em Ginecologia e Obstetrícia',
                'specialty' => 'Ginecologia e Obstetrícia',
                'institution' => 'Hospital Central de Maputo',
                'vacancies' => 8,
                'duration' => 48, // 48 months
                'description' => 'Programa de residência médica em Ginecologia e Obstetrícia com duração de 4 anos, focado na formação de especialistas em saúde da mulher.',
                'status' => 'active',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->startOfMonth()->addMonths(48),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Programa de Residência em Cardiologia',
                'specialty' => 'Cardiologia',
                'institution' => 'Instituto do Coração de Maputo',
                'vacancies' => 4,
                'duration' => 24, // 24 months
                'description' => 'Programa de residência médica em Cardiologia com duração de 2 anos, focado na formação de especialistas em doenças cardiovasculares.',
                'status' => 'active',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->startOfMonth()->addMonths(24),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Programa de Residência em Ortopedia',
                'specialty' => 'Ortopedia',
                'institution' => 'Hospital Central da Beira',
                'vacancies' => 5,
                'duration' => 48, // 48 months
                'description' => 'Programa de residência médica em Ortopedia com duração de 4 anos, focado na formação de especialistas em doenças e lesões do sistema musculoesquelético.',
                'status' => 'active',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->startOfMonth()->addMonths(48),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Programa de Residência em Neurologia',
                'specialty' => 'Neurologia',
                'institution' => 'Hospital Central de Nampula',
                'vacancies' => 3,
                'duration' => 36, // 36 months
                'description' => 'Programa de residência médica em Neurologia com duração de 3 anos, focado na formação de especialistas em doenças do sistema nervoso.',
                'status' => 'in_review',
                'start_date' => Carbon::now()->startOfMonth()->addMonths(2),
                'end_date' => Carbon::now()->startOfMonth()->addMonths(38),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Programa de Residência em Dermatologia',
                'specialty' => 'Dermatologia',
                'institution' => 'Hospital Central de Maputo',
                'vacancies' => 4,
                'duration' => 36, // 36 months
                'description' => 'Programa de residência médica em Dermatologia com duração de 3 anos, focado na formação de especialistas em doenças da pele.',
                'status' => 'inactive',
                'start_date' => null,
                'end_date' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($programs as $program) {
            $created = ResidenceProgram::create($program);
        }
    }
}
