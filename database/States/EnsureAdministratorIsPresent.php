<?php

namespace Database\States;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EnsureAdministratorIsPresent
{
    public function __invoke()
    {
        // Check if required tables exist before proceeding
        if (!$this->tablesExist()) {
            return;
        }

        if ($this->present()) {
            return;
        }

        // Create default users
        $users = [
            [
                'name' => 'Administrador OrMM',
                'email' => 'admin@hostmoz.net',
                'password' => Hash::make('12345678'),
                'role' => 'super-admin',
            ],
            [
                'name' => 'Medico',
                'email' => 'medico@hostmoz.net',
                'password' => Hash::make('12345678'),
                'role' => 'member',
            ],
            [
                'name' => 'Professor',
                'email' => 'professor@hostmoz.net',
                'password' => Hash::make('12345678'),
                'role' => 'teacher',
            ],
        ];

        $adminUser = null;

        // Create permissions (only if they don't exist) with explicit display_name and category
        $permissions = [];

        $add = function (string $category, array $items, array $displayNames = []) use (&$permissions) {
            foreach ($items as $name) {
                $permissions[] = [
                    'name' => $name,
                    'display_name' => $displayNames[$name] ?? null,
                    'category' => $category,
                ];
            }
        };

        // Users
        $add('users', [
            'users.create', 'users.read', 'users.update', 'users.delete', 'users.manage_roles', 'users.change_password',
        ], [
            'users.create' => 'Criar Utilizadores',
            'users.read' => 'Ver Utilizadores',
            'users.update' => 'Atualizar Utilizadores',
            'users.delete' => 'Apagar Utilizadores',
            'users.manage_roles' => 'Gerir Perfis',
            'users.change_password' => 'Alterar Senha',
        ]);

        // Roles & Permissions
        $add('roles', ['roles.create', 'roles.read', 'roles.update', 'roles.delete'], [
            'roles.create' => 'Criar Perfis',
            'roles.read' => 'Ver Perfis',
            'roles.update' => 'Atualizar Perfis',
            'roles.delete' => 'Apagar Perfis',
        ]);
        $add('permissions', ['permissions.create', 'permissions.read', 'permissions.update', 'permissions.delete'], [
            'permissions.create' => 'Criar Permissões',
            'permissions.read' => 'Ver Permissões',
            'permissions.update' => 'Atualizar Permissões',
            'permissions.delete' => 'Apagar Permissões',
        ]);

        // Members
        $add('members', ['members.create', 'members.read', 'members.update', 'members.delete', 'members.activate', 'members.suspend', 'members.manage_quota', 'members.generate_card'], [
            'members.create' => 'Criar Membros',
            'members.read' => 'Ver Membros',
            'members.update' => 'Atualizar Membros',
            'members.delete' => 'Apagar Membros',
            'members.activate' => 'Ativar Membros',
            'members.suspend' => 'Suspender Membros',
            'members.manage_quota' => 'Gerir Quotas',
            'members.generate_card' => 'Gerar Cartão',
        ]);

        // Registrations
        $add('registrations', [
            'registrations.create', 'registrations.read', 'registrations.update', 'registrations.delete', 'registrations.approve', 'registrations.reject', 'registrations.renew', 'registrations.reinstate', 'registrations.archive', 'registrations.export', 'registrations.import',
        ], [
            'registrations.create' => 'Criar Inscrições',
            'registrations.read' => 'Ver Inscrições',
            'registrations.update' => 'Atualizar Inscrições',
            'registrations.delete' => 'Apagar Inscrições',
            'registrations.approve' => 'Aprovar Inscrições',
            'registrations.reject' => 'Rejeitar Inscrições',
            'registrations.renew' => 'Renovar Inscrições',
            'registrations.reinstate' => 'Reintegrar Inscrições',
            'registrations.archive' => 'Arquivar Inscrições',
            'registrations.export' => 'Exportar Inscrições',
            'registrations.import' => 'Importar Inscrições',
        ]);
        $add('registrations.workflow', ['registrations.workflow.manage', 'registrations.workflow.assign', 'registrations.workflow.review', 'registrations.workflow.approve', 'registrations.workflow.reject'], [
            'registrations.workflow.manage' => 'Gerir Workflow de Inscrições',
            'registrations.workflow.assign' => 'Atribuir Workflow',
            'registrations.workflow.review' => 'Rever Workflow',
            'registrations.workflow.approve' => 'Aprovar no Workflow',
            'registrations.workflow.reject' => 'Rejeitar no Workflow',
        ]);
        $add('registration_types', ['registration_types.create', 'registration_types.read', 'registration_types.update', 'registration_types.delete', 'registration_types.activate', 'registration_types.deactivate'], [
            'registration_types.create' => 'Criar Tipos de Inscrição',
            'registration_types.read' => 'Ver Tipos de Inscrição',
            'registration_types.update' => 'Atualizar Tipos de Inscrição',
            'registration_types.delete' => 'Apagar Tipos de Inscrição',
            'registration_types.activate' => 'Ativar Tipo de Inscrição',
            'registration_types.deactivate' => 'Desativar Tipo de Inscrição',
        ]);
        $add('registrations.documents', ['registrations.documents.upload', 'registrations.documents.read', 'registrations.documents.validate', 'registrations.documents.approve', 'registrations.documents.reject', 'registrations.documents.download', 'registrations.documents.manage_checklist'], [
            'registrations.documents.upload' => 'Carregar Documentos de Inscrição',
            'registrations.documents.read' => 'Ver Documentos de Inscrição',
            'registrations.documents.validate' => 'Validar Documentos',
            'registrations.documents.approve' => 'Aprovar Documentos',
            'registrations.documents.reject' => 'Rejeitar Documentos',
            'registrations.documents.download' => 'Descarregar Documentos',
            'registrations.documents.manage_checklist' => 'Gerir Checklist de Documentos',
        ]);
        $add('registrations.payments', ['registrations.payments.create', 'registrations.payments.read', 'registrations.payments.update', 'registrations.payments.verify', 'registrations.payments.refund', 'registrations.payments.export'], [
            'registrations.payments.create' => 'Criar Pagamentos de Inscrição',
            'registrations.payments.read' => 'Ver Pagamentos de Inscrição',
            'registrations.payments.update' => 'Atualizar Pagamentos de Inscrição',
            'registrations.payments.verify' => 'Verificar Pagamentos de Inscrição',
            'registrations.payments.refund' => 'Reembolsar Pagamentos de Inscrição',
            'registrations.payments.export' => 'Exportar Pagamentos de Inscrição',
        ]);
        $add('registrations.reports', ['registrations.reports.dashboard', 'registrations.reports.status', 'registrations.reports.types', 'registrations.reports.workflow', 'registrations.reports.payments', 'registrations.reports.documents', 'registrations.reports.export'], [
            'registrations.reports.dashboard' => 'Relatório: Painel de Inscrições',
            'registrations.reports.status' => 'Relatório: Estado das Inscrições',
            'registrations.reports.types' => 'Relatório: Tipos de Inscrição',
            'registrations.reports.workflow' => 'Relatório: Workflow das Inscrições',
            'registrations.reports.payments' => 'Relatório: Pagamentos das Inscrições',
            'registrations.reports.documents' => 'Relatório: Documentos das Inscrições',
            'registrations.reports.export' => 'Exportar Relatórios de Inscrições',
        ]);
        $add('registrations.guest', ['registrations.guest.create', 'registrations.guest.check_status', 'registrations.guest.upload_documents', 'registrations.guest.confirm_payment'], [
            'registrations.guest.create' => 'Criar Pré-Inscrição (Convidado)',
            'registrations.guest.check_status' => 'Verificar Estado (Convidado)',
            'registrations.guest.upload_documents' => 'Carregar Documentos (Convidado)',
            'registrations.guest.confirm_payment' => 'Confirmar Pagamento (Convidado)',
        ]);
        $add('registrations.settings', ['registrations.settings.workflow', 'registrations.settings.fees', 'registrations.settings.validity', 'registrations.settings.requirements'], [
            'registrations.settings.workflow' => 'Configurar Workflow',
            'registrations.settings.fees' => 'Configurar Taxas',
            'registrations.settings.validity' => 'Configurar Validade',
            'registrations.settings.requirements' => 'Configurar Requisitos',
        ]);

        // Documents
        $add('documents', ['documents.view', 'documents.manage', 'documents.upload', 'documents.validate', 'documents.approve', 'documents.reject', 'documents.download', 'documents.manage_checklist'], [
            'documents.view' => 'Ver Documentos',
            'documents.manage' => 'Gerir Documentos',
            'documents.upload' => 'Carregar Documentos',
            'documents.validate' => 'Validar Documentos',
            'documents.approve' => 'Aprovar Documentos',
            'documents.reject' => 'Rejeitar Documentos',
            'documents.download' => 'Descarregar Documentos',
            'documents.manage_checklist' => 'Gerir Checklist de Documentos',
        ]);

        // Exams & applications
        $add('exams', ['exams.create', 'exams.read', 'exams.update', 'exams.delete', 'exams.schedule', 'exams.evaluate', 'exams.publish_results', 'exams.generate_certificates', 'exams.manage_candidates'], [
            'exams.create' => 'Criar Exames',
            'exams.read' => 'Ver Exames',
            'exams.update' => 'Atualizar Exames',
            'exams.delete' => 'Apagar Exames',
            'exams.schedule' => 'Agendar Exames',
            'exams.evaluate' => 'Avaliar Exames',
            'exams.publish_results' => 'Publicar Resultados',
            'exams.generate_certificates' => 'Gerar Certificados',
            'exams.manage_candidates' => 'Gerir Candidatos',
        ]);
        $add('exam-applications', ['exam-applications.create', 'exam-applications.read', 'exam-applications.update', 'exam-applications.delete', 'exam-applications.approve', 'exam-applications.reject'], [
            'exam-applications.create' => 'Criar Candidaturas a Exame',
            'exam-applications.read' => 'Ver Candidaturas a Exame',
            'exam-applications.update' => 'Atualizar Candidaturas a Exame',
            'exam-applications.delete' => 'Apagar Candidaturas a Exame',
            'exam-applications.approve' => 'Aprovar Candidatura a Exame',
            'exam-applications.reject' => 'Rejeitar Candidatura a Exame',
        ]);

        // Payments
        $add('payments', ['payments.view', 'payments.create', 'payments.update', 'payments.delete', 'payments.process', 'payments.reconcile', 'payments.refund', 'payments.generate_receipt', 'payments.send_receipt', 'payments.export'], [
            'payments.view' => 'Ver Pagamentos',
            'payments.create' => 'Criar Pagamentos',
            'payments.update' => 'Atualizar Pagamentos',
            'payments.delete' => 'Apagar Pagamentos',
            'payments.process' => 'Processar Pagamentos',
            'payments.reconcile' => 'Conciliar Pagamentos',
            'payments.refund' => 'Reembolsar Pagamentos',
            'payments.generate_receipt' => 'Gerar Recibo',
            'payments.send_receipt' => 'Enviar Recibo',
            'payments.export' => 'Exportar Pagamentos',
        ]);

        // Reports
        $add('reports', ['reports.generate', 'reports.export', 'reports.view_financial', 'reports.print'], [
            'reports.generate' => 'Gerar Relatórios',
            'reports.export' => 'Exportar Relatórios',
            'reports.view_financial' => 'Ver Relatórios Financeiros',
            'reports.print' => 'Imprimir Relatórios',
        ]);

        // System
        $add('system', ['system.configure', 'system.backup', 'system.maintenance', 'system.view_logs'], [
            'system.configure' => 'Configurar Sistema',
            'system.backup' => 'Backup do Sistema',
            'system.maintenance' => 'Manutenção do Sistema',
            'system.view_logs' => 'Ver Logs do Sistema',
        ]);

        // Audit
        $add('audit', ['audit.view', 'audit.export', 'audit.statistics'], [
            'audit.view' => 'Ver Auditoria',
            'audit.export' => 'Exportar Auditoria',
            'audit.statistics' => 'Estatísticas de Auditoria',
        ]);

        // Notifications
        $add('notifications', ['notifications.create', 'notifications.read', 'notifications.update', 'notifications.delete', 'notifications.send', 'notifications.manage'], [
            'notifications.create' => 'Criar Notificações',
            'notifications.read' => 'Ver Notificações',
            'notifications.update' => 'Atualizar Notificações',
            'notifications.delete' => 'Apagar Notificações',
            'notifications.send' => 'Enviar Notificações',
            'notifications.manage' => 'Gerir Notificações',
        ]);

        // Archives
        $add('archives', ['archives.view', 'archives.restore', 'archives.force_delete', 'archives.export'], [
            'archives.view' => 'Ver Arquivo',
            'archives.restore' => 'Restaurar Arquivo',
            'archives.force_delete' => 'Apagar Arquivo (Forçado)',
            'archives.export' => 'Exportar Arquivo',
        ]);

        // AI Chat
        $add('ai-chat', ['ai-chat.access', 'ai-chat.manage'], [
            'ai-chat.access' => 'Aceder ao Chat IA',
            'ai-chat.manage' => 'Gerir Chat IA',
        ]);

        // Cards
        $add('cards', ['cards.create', 'cards.read', 'cards.update', 'cards.delete', 'cards.generate', 'cards.manage_status'], [
            'cards.create' => 'Criar Cartões',
            'cards.read' => 'Ver Cartões',
            'cards.update' => 'Atualizar Cartões',
            'cards.delete' => 'Apagar Cartões',
            'cards.generate' => 'Gerar Cartões',
            'cards.manage_status' => 'Gerir Estado dos Cartões',
        ]);

        // Residency
        $add('residency.programs', ['residency.programs.create', 'residency.programs.read', 'residency.programs.update', 'residency.programs.delete'], [
            'residency.programs.create' => 'Criar Programas de Residência',
            'residency.programs.read' => 'Ver Programas de Residência',
            'residency.programs.update' => 'Atualizar Programas de Residência',
            'residency.programs.delete' => 'Apagar Programas de Residência',
        ]);
        $add('residency.residents', ['residency.residents.create', 'residency.residents.read', 'residency.residents.update', 'residency.residents.delete'], [
            'residency.residents.create' => 'Criar Residentes',
            'residency.residents.read' => 'Ver Residentes',
            'residency.residents.update' => 'Atualizar Residentes',
            'residency.residents.delete' => 'Apagar Residentes',
        ]);
        $add('residency.applications', ['residency.applications.create', 'residency.applications.read', 'residency.applications.update', 'residency.applications.delete', 'residency.applications.approve', 'residency.applications.reject'], [
            'residency.applications.create' => 'Criar Candidaturas de Residência',
            'residency.applications.read' => 'Ver Candidaturas de Residência',
            'residency.applications.update' => 'Atualizar Candidaturas de Residência',
            'residency.applications.delete' => 'Apagar Candidaturas de Residência',
            'residency.applications.approve' => 'Aprovar Candidatura de Residência',
            'residency.applications.reject' => 'Rejeitar Candidatura de Residência',
        ]);
        $add('residency.evaluations', ['residency.evaluations.create', 'residency.evaluations.read', 'residency.evaluations.update', 'residency.evaluations.delete'], [
            'residency.evaluations.create' => 'Criar Avaliações de Residência',
            'residency.evaluations.read' => 'Ver Avaliações de Residência',
            'residency.evaluations.update' => 'Atualizar Avaliações de Residência',
            'residency.evaluations.delete' => 'Apagar Avaliações de Residência',
        ]);
        $add('residency.management', ['residency.locations.manage', 'residency.completions.manage', 'residency.exams.manage', 'residency.reports.generate', 'residency.history.view'], [
            'residency.locations.manage' => 'Gerir Locais de Residência',
            'residency.completions.manage' => 'Gerir Conclusões de Residência',
            'residency.exams.manage' => 'Gerir Exames de Residência',
            'residency.reports.generate' => 'Gerar Relatórios de Residência',
            'residency.history.view' => 'Ver Histórico de Residência',
        ]);

        // Dashboard
        $add('dashboard', ['dashboard.view', 'dashboard.statistics'], [
            'dashboard.view' => 'Ver Painel',
            'dashboard.statistics' => 'Ver Estatísticas do Painel',
        ]);

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name']],
                [
                    'guard_name' => 'web',
                    'display_name' => $perm['display_name'],
                    'category' => $perm['category'],
                ]
            );
        }

        // Create roles and assign permissions
        $roles = [
            [
                'name' => 'super-admin',
                'display_name' => 'Super Administrador',
                'permissions' => [
                    // All permissions
                    'users.create', 'users.read', 'users.update', 'users.delete', 'users.manage_roles', 'users.change_password',
                    'roles.create', 'roles.read', 'roles.update', 'roles.delete',
                    'permissions.create', 'permissions.read', 'permissions.update', 'permissions.delete',
                    'members.create', 'members.read', 'members.update', 'members.delete', 'members.activate', 'members.suspend', 'members.manage_quota', 'members.generate_card',
                    'registrations.create', 'registrations.read', 'registrations.update', 'registrations.approve', 'registrations.reject', 'registrations.renew', 'registrations.reinstate',
                    'documents.view', 'documents.manage', 'documents.upload', 'documents.validate', 'documents.approve', 'documents.reject', 'documents.download', 'documents.manage_checklist',
                    'exams.create', 'exams.read', 'exams.update', 'exams.delete', 'exams.schedule', 'exams.evaluate', 'exams.publish_results', 'exams.generate_certificates', 'exams.manage_candidates',
                    'exam-applications.create', 'exam-applications.read', 'exam-applications.update', 'exam-applications.delete', 'exam-applications.approve', 'exam-applications.reject',
                    'payments.view', 'payments.create', 'payments.update', 'payments.delete', 'payments.process', 'payments.reconcile', 'payments.refund', 'payments.generate_receipt', 'payments.send_receipt', 'payments.export',
                    'reports.generate', 'reports.export', 'reports.view_financial', 'reports.print',
                    'system.configure', 'system.backup', 'system.maintenance', 'system.view_logs',
                    'audit.view', 'audit.export', 'audit.statistics',
                    'notifications.create', 'notifications.read', 'notifications.update', 'notifications.delete', 'notifications.send', 'notifications.manage',
                    'archives.view', 'archives.restore', 'archives.force_delete', 'archives.export',
                    'ai-chat.access', 'ai-chat.manage',
                    'cards.create', 'cards.read', 'cards.update', 'cards.delete', 'cards.generate', 'cards.manage_status',
                    'residency.programs.create', 'residency.programs.read', 'residency.programs.update', 'residency.programs.delete',
                    'residency.residents.create', 'residency.residents.read', 'residency.residents.update', 'residency.residents.delete',
                    'residency.applications.create', 'residency.applications.read', 'residency.applications.update', 'residency.applications.delete', 'residency.applications.approve', 'residency.applications.reject',
                    'residency.evaluations.create', 'residency.evaluations.read', 'residency.evaluations.update', 'residency.evaluations.delete',
                    'residency.locations.manage', 'residency.completions.manage', 'residency.exams.manage', 'residency.reports.generate', 'residency.history.view',
                    'dashboard.view', 'dashboard.statistics',
                ],
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'permissions' => [
                    'users.create', 'users.read', 'users.update', 'users.delete', 'users.change_password',
                    'roles.read', 'permissions.read',
                    'members.create', 'members.read', 'members.update', 'members.delete', 'members.activate', 'members.suspend', 'members.manage_quota', 'members.generate_card',
                    'registrations.create', 'registrations.read', 'registrations.update', 'registrations.approve', 'registrations.reject', 'registrations.renew', 'registrations.reinstate',
                    'documents.upload', 'documents.validate', 'documents.approve', 'documents.reject', 'documents.download', 'documents.manage_checklist',
                    'exams.create', 'exams.read', 'exams.update', 'exams.delete', 'exams.schedule', 'exams.evaluate', 'exams.publish_results', 'exams.generate_certificates', 'exams.manage_candidates',
                    'exam-applications.create', 'exam-applications.read', 'exam-applications.update', 'exam-applications.delete', 'exam-applications.approve', 'exam-applications.reject',
                    'payments.view', 'payments.create', 'payments.update', 'payments.delete', 'payments.process', 'payments.reconcile', 'payments.refund', 'payments.generate_receipt', 'payments.send_receipt', 'payments.export',
                    'reports.generate', 'reports.export', 'reports.view_financial', 'reports.print',
                    'system.configure', 'system.backup', 'system.view_logs',
                    'audit.view', 'audit.export', 'audit.statistics',
                    'notifications.create', 'notifications.read', 'notifications.update', 'notifications.delete', 'notifications.send', 'notifications.manage',
                    'archives.view', 'archives.restore', 'archives.export',
                    'ai-chat.access',
                    'cards.create', 'cards.read', 'cards.update', 'cards.delete', 'cards.generate', 'cards.manage_status',
                    'residency.programs.create', 'residency.programs.read', 'residency.programs.update', 'residency.programs.delete',
                    'residency.residents.create', 'residency.residents.read', 'residency.residents.update', 'residency.residents.delete',
                    'residency.applications.create', 'residency.applications.read', 'residency.applications.update', 'residency.applications.delete', 'residency.applications.approve', 'residency.applications.reject',
                    'residency.evaluations.create', 'residency.evaluations.read', 'residency.evaluations.update', 'residency.evaluations.delete',
                    'residency.locations.manage', 'residency.completions.manage', 'residency.exams.manage', 'residency.reports.generate', 'residency.history.view',
                    'dashboard.view', 'dashboard.statistics',
                ],
            ],
            [
                'name' => 'secretariat',
                'display_name' => 'Secretariado',
                'permissions' => [
                    'users.read',
                    'members.read', 'members.update', 'members.generate_card',
                    'registrations.create', 'registrations.read', 'registrations.update',
                    'documents.upload', 'documents.validate', 'documents.download',
                    'exams.read', 'exams.schedule',
                    'exam-applications.read',
                    'payments.view', 'payments.create', 'payments.process', 'payments.generate_receipt', 'payments.send_receipt',
                    'reports.generate', 'reports.export',
                    'notifications.create', 'notifications.read', 'notifications.send',
                    'cards.read', 'cards.generate',
                    'dashboard.view',
                ],
            ],
            [
                'name' => 'validator',
                'display_name' => 'Validador',
                'permissions' => [
                    'users.read',
                    'members.read',
                    'registrations.read',
                    'documents.validate', 'documents.approve', 'documents.reject', 'documents.download', 'documents.manage_checklist',
                    'payments.view',
                    'reports.generate',
                    'notifications.read',
                    'dashboard.view',
                ],
            ],
            [
                'name' => 'evaluator',
                'display_name' => 'Avaliador',
                'permissions' => [
                    'users.read',
                    'members.read',
                    'registrations.read',
                    'exams.read', 'exams.evaluate', 'exams.publish_results', 'exams.generate_certificates', 'exams.manage_candidates',
                    'exam-applications.read', 'exam-applications.approve', 'exam-applications.reject',
                    'payments.view',
                    'reports.generate',
                    'notifications.read',
                    'dashboard.view',
                ],
            ],
            [
                'name' => 'treasury',
                'display_name' => 'Tesouraria',
                'permissions' => [
                    'users.read',
                    'members.read',
                    'registrations.read',
                    'payments.view', 'payments.create', 'payments.update', 'payments.process', 'payments.reconcile', 'payments.refund', 'payments.generate_receipt', 'payments.send_receipt', 'payments.export',
                    'reports.generate', 'reports.export', 'reports.view_financial', 'reports.print',
                    'notifications.read',
                    'dashboard.view', 'dashboard.statistics',
                ],
            ],
            [
                'name' => 'council',
                'display_name' => 'Conselho',
                'permissions' => [
                    'users.read',
                    'members.read', 'members.activate', 'members.suspend',
                    'registrations.read', 'registrations.approve', 'registrations.reject',
                    'documents.approve', 'documents.reject',
                    'exams.read', 'exams.publish_results',
                    'payments.view',
                    'reports.generate', 'reports.export', 'reports.view_financial',
                    'notifications.read',
                    'dashboard.view', 'dashboard.statistics',
                ],
            ],
            [
                'name' => 'auditor',
                'display_name' => 'Auditor',
                'permissions' => [
                    'users.read',
                    'members.read',
                    'registrations.read',
                    'payments.view',
                    'reports.generate', 'reports.export',
                    'audit.view', 'audit.export', 'audit.statistics',
                    'notifications.read',
                    'dashboard.view',
                ],
            ],
            [
                'name' => 'member',
                'display_name' => 'Membro',
                'permissions' => [
                    'members.read',
                    'registrations.read',
                    'documents.upload',
                    'payments.view',
                    'notifications.read',
                    'dashboard.view',
                ],
            ],
            [
                'name' => 'candidate',
                'display_name' => 'Candidato',
                'permissions' => [
                    'registrations.create',
                    'documents.upload',
                    'payments.view',
                    'notifications.read',
                ],
            ],
            [
                'name' => 'teacher',
                'display_name' => 'Professor',
                'permissions' => [
                    'dashboard.view',
                ],
            ],
        ];

        foreach ($roles as $roleDef) {
            $role = Role::firstOrCreate(
                ['name' => $roleDef['name']],
                [
                    'guard_name' => 'web',
                    'display_name' => $roleDef['display_name'],
                ]
            );
            $role->syncPermissions($roleDef['permissions']);
        }

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role(s) to user
            if ($role === 'super-admin') {
                // Admin user gets both 'admin' and 'super-admin' roles
                $user->syncRoles(['admin', 'super-admin']);
                $adminUser = $user;
            } else {
                $user->syncRoles([$role]);
            }
        }

    }

    private function tablesExist(): bool
    {
        try {
            // Check if required tables exist
            $requiredTables = ['users', 'permissions', 'roles'];
            
            foreach ($requiredTables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) {
                    return false;
                }
            }
            
            return true;
        } catch (\Exception $e) {
            // If there's any error checking tables, assume they don't exist yet
            return false;
        }
    }

    private function present()
    {
        // Check if all default users exist
        $defaultUsers = [
            'admin@hostmoz.net',
            'medico@hostmoz.net',
            'professor@hostmoz.net',
        ];

        $existingCount = DB::table('users')
            ->whereIn('email', $defaultUsers)
            ->count();

        return $existingCount === count($defaultUsers);
    }

    // makeRoleDisplayName removed in favour of explicit display_name on role definitions
}
