<?php

namespace App\Models;

use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission implements Auditable
{
    use AuditableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'category',
    ];

    /**
     * The attributes that should be audited.
     *
     * @var array
     */
    protected $auditInclude = [
        'name',
        'guard_name',
        'display_name',
        'category',
    ];

    /**
     * The attributes that should not be audited.
     *
     * @var array
     */
    protected $auditExclude = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $auditHidden = [];

    /**
     * The attributes that should be visible in the audit.
     *
     * @var array
     */
    protected $auditVisible = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $auditStrict = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $auditTimestamps = true;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    /**
     * Derive category from a permission name.
     */
    public static function deriveCategory(string $name): string
    {
        $parts = explode('.', $name, 2);
        return $parts[0] ?? 'other';
    }

    /**
     * Derive a Portuguese display name from a permission name.
     */
    public static function deriveDisplayName(string $name): string
    {
        $category = self::deriveCategory($name);
        $rest = trim(substr($name, strlen($category) + 1));

        $resourceMap = [
            'users' => 'Utilizadores',
            'roles' => 'Perfis',
            'permissions' => 'Permissões',
            'members' => 'Membros',
            'registrations' => 'Inscrições',
            'documents' => 'Documentos',
            'exams' => 'Exames',
            'exam-applications' => 'Candidaturas a Exame',
            'payments' => 'Pagamentos',
            'reports' => 'Relatórios',
            'system' => 'Sistema',
            'audit' => 'Auditoria',
            'notifications' => 'Notificações',
            'archives' => 'Arquivo',
            'ai-chat' => 'Chat IA',
            'cards' => 'Cartões',
            'residency' => 'Residência',
            'dashboard' => 'Painel',
        ];

        $actionMap = [
            'create' => 'Criar',
            'read' => 'Ver',
            'update' => 'Atualizar',
            'delete' => 'Eliminar',
            'manage_roles' => 'Gerir perfis',
            'change_password' => 'Alterar palavra-passe',
            'approve' => 'Aprovar',
            'reject' => 'Rejeitar',
            'renew' => 'Renovar',
            'reinstate' => 'Reintegrar',
            'upload' => 'Carregar',
            'validate' => 'Validar',
            'download' => 'Descarregar',
            'manage_checklist' => 'Gerir checklist',
            'schedule' => 'Agendar',
            'evaluate' => 'Avaliar',
            'publish_results' => 'Publicar resultados',
            'generate_certificates' => 'Emitir certificados',
            'manage_candidates' => 'Gerir candidatos',
            'view' => 'Ver',
            'process' => 'Processar',
            'reconcile' => 'Conciliar',
            'refund' => 'Reembolsar',
            'generate_receipt' => 'Gerar recibo',
            'send_receipt' => 'Enviar recibo',
            'export' => 'Exportar',
            'view_financial' => 'Ver financeiro',
            'print' => 'Imprimir',
            'configure' => 'Configurar',
            'backup' => 'Cópia de segurança',
            'maintenance' => 'Manutenção',
            'view_logs' => 'Ver logs',
            'statistics' => 'Estatísticas',
            'manage' => 'Gerir',
            'restore' => 'Restaurar',
            'force_delete' => 'Eliminar definitivamente',
            'access' => 'Aceder',
            'generate' => 'Gerar',
            'manage_status' => 'Gerir estado',
        ];

        $resourceLabel = $resourceMap[$category] ?? ucfirst(str_replace(['-', '_'], ' ', $category));

        $actionKey = $rest;
        if ($rest === '' || $rest === false) {
            $actionKey = '';
        } elseif (str_contains($rest, '.')) {
            $segments = explode('.', $rest);
            $actionKey = end($segments);
            $subResource = implode(' / ', array_map(function ($seg) use ($resourceMap) {
                return $resourceMap[$seg] ?? ucfirst(str_replace(['-', '_'], ' ', $seg));
            }, array_slice($segments, 0, -1)));
            if ($subResource !== '') {
                $resourceLabel .= ' · ' . $subResource;
            }
        }

        $actionLabel = $actionMap[$actionKey] ?? ($actionKey !== '' ? ucfirst(str_replace(['-', '_'], ' ', $actionKey)) : '');

        return trim(($actionLabel !== '' ? ($actionLabel . ' ') : '') . $resourceLabel);
    }

    /**
     * Get the audit data for the model.
     */
    public function getAuditData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ];
    }

    /**
     * Get the audit user for the model.
     *
     * @return mixed
     */
    public function getAuditUser()
    {
        return auth()->user();
    }

    /**
     * Get the audit URL for the model.
     */
    public function getAuditUrl(): ?string
    {
        return route('admin.permissions.show', $this->id);
    }

    /**
     * Get the audit tags for the model.
     */
    public function getAuditTags(): array
    {
        return ['permission', 'role'];
    }

    /**
     * Get the audit metadata for the model.
     */
    public function getAuditMetadata(): array
    {
        return [
            'roles_count' => $this->roles()->count(),
        ];
    }
}
