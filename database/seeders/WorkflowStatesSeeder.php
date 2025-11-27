<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkflowStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing workflow states
        DB::table('workflow_states')->truncate();

        // Seed workflow states for registrations (7 estados conforme plano)
        DB::table('workflow_states')->insert([
            [
                'name' => 'Rascunho',
                'description' => 'Inscrição em rascunho, ainda não submetida',
                'module' => 'registration',
                'order' => 1,
                'is_initial' => true,
                'is_final' => false,
                'is_active' => true,
                'allowed_transitions' => json_encode(['Submetido']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Submetido',
                'description' => 'Inscrição submetida e aguardando análise',
                'module' => 'registration',
                'order' => 2,
                'is_initial' => false,
                'is_final' => false,
                'is_active' => true,
                'allowed_transitions' => json_encode(['Em Análise', 'Rejeitado']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Em Análise',
                'description' => 'Inscrição em análise pelo secretariado',
                'module' => 'registration',
                'order' => 3,
                'is_initial' => false,
                'is_final' => false,
                'is_active' => true,
                'allowed_transitions' => json_encode(['Documentos Pendentes', 'Pagamento Pendente', 'Validado', 'Rejeitado']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Documentos Pendentes',
                'description' => 'Aguardando documentos adicionais',
                'module' => 'registration',
                'order' => 4,
                'is_initial' => false,
                'is_final' => false,
                'is_active' => true,
                'allowed_transitions' => json_encode(['Em Análise', 'Rejeitado', 'Arquivado']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pagamento Pendente',
                'description' => 'Aguardando confirmação de pagamento',
                'module' => 'registration',
                'order' => 5,
                'is_initial' => false,
                'is_final' => false,
                'is_active' => true,
                'allowed_transitions' => json_encode(['Validado', 'Rejeitado']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Validado',
                'description' => 'Inscrição validada, pronta para aprovação',
                'module' => 'registration',
                'order' => 6,
                'is_initial' => false,
                'is_final' => false,
                'is_active' => true,
                'allowed_transitions' => json_encode(['Aprovado', 'Rejeitado']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aprovado',
                'description' => 'Inscrição aprovada e ativa',
                'module' => 'registration',
                'order' => 7,
                'is_initial' => false,
                'is_final' => true,
                'is_active' => true,
                'allowed_transitions' => json_encode(['Arquivado', 'Expirado']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rejeitado',
                'description' => 'Inscrição rejeitada',
                'module' => 'registration',
                'order' => 8,
                'is_initial' => false,
                'is_final' => true,
                'is_active' => true,
                'allowed_transitions' => json_encode(['Arquivado']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arquivado',
                'description' => 'Inscrição arquivada (inativa há mais de 45 dias)',
                'module' => 'registration',
                'order' => 9,
                'is_initial' => false,
                'is_final' => true,
                'is_active' => true,
                'allowed_transitions' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Expirado',
                'description' => 'Inscrição expirada',
                'module' => 'registration',
                'order' => 10,
                'is_initial' => false,
                'is_final' => true,
                'is_active' => true,
                'allowed_transitions' => json_encode(['Arquivado']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
