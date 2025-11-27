<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing user profiles
        DB::table('user_profiles')->truncate();

        // Seed user profiles
        DB::table('user_profiles')->insert([
            [
                'name' => 'Administrador do Sistema',
                'description' => 'Acesso completo ao sistema',
                'code' => 'admin',
                'permissions' => json_encode(['*']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Secretariado/Inscrições',
                'description' => 'Gestão de candidaturas e processos de inscrição',
                'code' => 'secretariado',
                'permissions' => json_encode(['registrations.create', 'registrations.read', 'registrations.update', 'members.read']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Validador Documental',
                'description' => 'Validação de documentos e pareceres',
                'code' => 'validador',
                'permissions' => json_encode(['documents.validate', 'documents.read', 'registrations.read']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Avaliador de Exames',
                'description' => 'Gestão de exames e avaliações',
                'code' => 'avaliador',
                'permissions' => json_encode(['exams.create', 'exams.read', 'exams.update', 'exam_results.create']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor de Residência',
                'description' => 'Supervisão de programas de residência',
                'code' => 'supervisor',
                'permissions' => json_encode(['residence.read', 'residence.update', 'residence_evaluations.create']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tesouraria/Financeiro',
                'description' => 'Gestão de pagamentos e finanças',
                'code' => 'tesouraria',
                'permissions' => json_encode(['payments.create', 'payments.read', 'payments.update', 'reports.financial']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Conselho/Decisor',
                'description' => 'Decisões finais e aprovações',
                'code' => 'conselho',
                'permissions' => json_encode(['registrations.approve', 'registrations.reject', 'members.approve']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Auditor Externo',
                'description' => 'Acesso somente-leitura para auditoria',
                'code' => 'auditor',
                'permissions' => json_encode(['audit.read', 'reports.read']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Candidato',
                'description' => 'Acesso ao portal do candidato',
                'code' => 'candidato',
                'permissions' => json_encode(['registrations.create', 'registrations.read', 'documents.upload', 'payments.create']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Membro',
                'description' => 'Acesso ao portal do membro',
                'code' => 'membro',
                'permissions' => json_encode(['profile.read', 'profile.update', 'payments.create', 'cards.download']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
