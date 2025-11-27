<?php

namespace Modules\Exam\Database\Seeders;

use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get evaluator users
        $evaluators = User::whereHas('roles', function ($query) {
            $query->where('name', 'evaluator');
        })->get();

        // If no evaluators found, use any users
        if ($evaluators->isEmpty()) {
            $evaluators = User::take(2)->get();
        }

        // Create sample exams
        $exams = [
            [
                'name' => 'Exame de Certificação em Medicina Geral',
                'type' => 'teorico',
                'level' => 'intermediario',
                'specialty' => 'Medicina Geral',
                'description' => 'Exame para certificação em medicina geral com foco em diagnóstico e tratamento de doenças comuns.',
                'exam_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'duration' => 180,
                'location' => 'Auditório Principal - Ordem dos Médicos',
                'address' => 'Av. Eduardo Mondlane, 123, Maputo',
                'capacity' => 50,
                'minimum_grade' => 12.0,
                'questions_count' => 100,
                'time_limit' => 180,
                'attempts_allowed' => 1,
                'allow_consultation' => false,
                'is_mandatory' => true,
                'immediate_result' => false,
                'primary_evaluator_id' => $evaluators->isNotEmpty() ? $evaluators->first()->id : null,
                'secondary_evaluator_id' => $evaluators->count() > 1 ? $evaluators->last()->id : null,
                'notes' => 'Trazer documento de identificação e caneta azul ou preta.',
                'status' => 'scheduled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Exame Prático de Cirurgia Geral',
                'type' => 'pratico',
                'level' => 'avancado',
                'specialty' => 'Cirurgia Geral',
                'description' => 'Avaliação prática de habilidades cirúrgicas básicas e avançadas.',
                'exam_date' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'duration' => 480,
                'location' => 'Hospital Central de Maputo - Bloco Cirúrgico',
                'address' => 'Av. Agostinho Neto, 1364, Maputo',
                'capacity' => 20,
                'minimum_grade' => 14.0,
                'questions_count' => null,
                'time_limit' => 480,
                'attempts_allowed' => 1,
                'allow_consultation' => true,
                'is_mandatory' => true,
                'immediate_result' => false,
                'primary_evaluator_id' => $evaluators->isNotEmpty() ? $evaluators->first()->id : null,
                'secondary_evaluator_id' => $evaluators->count() > 1 ? $evaluators->last()->id : null,
                'notes' => 'Trazer jaleco, luvas e equipamentos de proteção individual.',
                'status' => 'scheduled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Exame Oral de Pediatria',
                'type' => 'oral',
                'level' => 'intermediario',
                'specialty' => 'Pediatria',
                'description' => 'Avaliação oral de conhecimentos em pediatria, com foco em diagnóstico e tratamento de doenças infantis.',
                'exam_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'duration' => 240,
                'location' => 'Faculdade de Medicina - Sala 302',
                'address' => 'Campus Universitário, Maputo',
                'capacity' => 30,
                'minimum_grade' => 13.0,
                'questions_count' => null,
                'time_limit' => 30,
                'attempts_allowed' => 1,
                'allow_consultation' => false,
                'is_mandatory' => false,
                'immediate_result' => true,
                'primary_evaluator_id' => $evaluators->isNotEmpty() ? $evaluators->first()->id : null,
                'secondary_evaluator_id' => $evaluators->count() > 1 ? $evaluators->last()->id : null,
                'notes' => 'Cada candidato terá 30 minutos para responder às perguntas da banca examinadora.',
                'status' => 'scheduled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Exame de Recertificação em Cardiologia',
                'type' => 'misto',
                'level' => 'avancado',
                'specialty' => 'Cardiologia',
                'description' => 'Exame para recertificação em cardiologia, incluindo prova teórica e prática.',
                'exam_date' => Carbon::now()->addDays(60)->format('Y-m-d'),
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'duration' => 540,
                'location' => 'Instituto do Coração - Auditório',
                'address' => 'Rua das Acácias, 45, Maputo',
                'capacity' => 25,
                'minimum_grade' => 15.0,
                'questions_count' => 80,
                'time_limit' => 540,
                'attempts_allowed' => 1,
                'allow_consultation' => false,
                'is_mandatory' => true,
                'immediate_result' => false,
                'primary_evaluator_id' => $evaluators->isNotEmpty() ? $evaluators->first()->id : null,
                'secondary_evaluator_id' => $evaluators->count() > 1 ? $evaluators->last()->id : null,
                'notes' => 'O exame será dividido em duas partes: teórica pela manhã e prática à tarde.',
                'status' => 'scheduled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Exame de Certificação em Anestesiologia',
                'type' => 'teorico',
                'level' => 'intermediario',
                'specialty' => 'Anestesiologia',
                'description' => 'Avaliação teórica de conhecimentos em anestesiologia.',
                'exam_date' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'duration' => 240,
                'location' => 'Hospital Central de Maputo - Sala de Conferências',
                'address' => 'Av. Agostinho Neto, 1364, Maputo',
                'capacity' => 40,
                'minimum_grade' => 12.0,
                'questions_count' => 120,
                'time_limit' => 240,
                'attempts_allowed' => 1,
                'allow_consultation' => false,
                'is_mandatory' => true,
                'immediate_result' => false,
                'primary_evaluator_id' => $evaluators->isNotEmpty() ? $evaluators->first()->id : null,
                'secondary_evaluator_id' => $evaluators->count() > 1 ? $evaluators->last()->id : null,
                'notes' => 'Exame já realizado.',
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(60),
                'updated_at' => Carbon::now()->subDays(30),
            ],
        ];

        // Insert exams
        foreach ($exams as $exam) {
            Exam::create($exam);
        }
    }
}
