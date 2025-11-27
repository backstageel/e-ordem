<?php

namespace Modules\Exam\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed exam types
        DB::table('exam_types')->insert([
            [
                'name' => 'Exame de Licenciatura',
                'description' => 'Exame para obtenção da licença médica',
                'fee' => 500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Exame de Especialidade',
                'description' => 'Exame para obtenção de especialidade médica',
                'fee' => 750.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Exame de Recertificação',
                'description' => 'Exame para recertificação profissional',
                'fee' => 300.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Exame de Equivalência',
                'description' => 'Exame de equivalência para médicos estrangeiros',
                'fee' => 1000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Exame de Residência Médica',
                'description' => 'Exame para entrada em programa de residência médica',
                'fee' => 200.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
