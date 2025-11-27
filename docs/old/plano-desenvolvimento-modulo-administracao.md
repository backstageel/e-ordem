# Plano de Desenvolvimento do Módulo de Administração
## Sistema Integrado de Gestão da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.0  
**Data:** 2025-01-27  
**Autor:** Equipe de Desenvolvimento MillPáginas  

---

## 1. VISÃO GERAL DO MÓDULO DE ADMINISTRAÇÃO

### 1.1 Objetivo
O Módulo de Administração é o núcleo central do sistema OrMM, responsável por:
- Gestão completa de usuários e permissões
- Configuração e manutenção do sistema
- Monitoramento e auditoria de todas as operações
- Dashboards executivos e relatórios gerenciais
- Configuração de parâmetros globais

### 1.2 Escopo
Este módulo abrange todas as funcionalidades administrativas necessárias para:
- **Gestão de Usuários**: Criação, edição, ativação/desativação de usuários
- **Gestão de Roles e Permissões**: Sistema RBAC completo
- **Configurações do Sistema**: Parâmetros globais, taxas, prazos
- **Auditoria e Logs**: Rastreabilidade completa de ações
- **Dashboards**: Métricas e KPIs em tempo real
- **Relatórios**: Geração de relatórios administrativos
- **Backup e Manutenção**: Operações de sistema

---

## 2. ARQUITETURA E ESTRUTURA

### 2.1 Pacotes Necessários

#### 2.1.1 Pacotes Principais
- **spatie/laravel-permission**: Sistema RBAC completo para roles e permissões
- **owen-it/laravel-auditing**: Auditoria automática de modelos Eloquent
- **spatie/laravel-backup**: Sistema de backup automático
- **spatie/laravel-medialibrary**: Gestão de arquivos e documentos
- **barryvdh/laravel-dompdf**: Geração de relatórios em PDF
- **maatwebsite/excel**: Exportação de dados para Excel/CSV

#### 2.1.2 Pacotes de Desenvolvimento
- **spatie/laravel-query-builder**: Construção de queries avançadas
- **spatie/laravel-data**: Data Transfer Objects (DTOs) - Substitui Form Requests
- **spatie/laravel-model-states**: Estados de modelos
- **spatie/laravel-sluggable**: URLs amigáveis

### 2.2 Estrutura de Diretórios
```
app/
├── Http/Controllers/Admin/
│   ├── DashboardController.php          # Dashboard principal
│   ├── UserController.php              # Gestão de usuários
│   ├── RoleController.php              # Gestão de roles
│   ├── PermissionController.php        # Gestão de permissões
│   ├── SystemConfigController.php      # Configurações do sistema
│   ├── AuditController.php             # Logs de auditoria
│   ├── ReportController.php            # Relatórios
│   ├── BackupController.php            # Backup e manutenção
│   └── NotificationController.php      # Gestão de notificações
├── Actions/Admin/                       # Action Pattern
│   ├── CreateUserAction.php
│   ├── UpdateUserAction.php
│   ├── DeleteUserAction.php
│   ├── AssignRoleAction.php
│   ├── GenerateReportAction.php
│   └── CreateBackupAction.php
├── Data/Admin/                          # Laravel Data Classes
│   ├── UserData.php
│   ├── RoleData.php
│   └── SystemConfigData.php
├── Models/
│   ├── User.php                        # Com traits de auditoria
│   ├── SystemConfig.php
│   └── NotificationTemplate.php

resources/views/admin/
├── dashboard/
│   ├── index.blade.php
│   └── widgets/
├── users/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── roles/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── permissions/
│   ├── index.blade.php
│   └── manage.blade.php
├── system/
│   ├── config.blade.php
│   ├── settings.blade.php
│   └── maintenance.blade.php
├── audit/
│   ├── logs.blade.php
│   └── reports.blade.php
└── reports/
    ├── index.blade.php
    ├── financial.blade.php
    └── operational.blade.php
```

### 2.3 Modelos Principais
- **User**: Usuários do sistema (com traits de auditoria)
- **SystemConfig**: Configurações do sistema
- **NotificationTemplate**: Templates de notificação
- **Role/Permission**: Gerenciados pelo Spatie Laravel Permission
- **Audit**: Gerenciado pelo Laravel Auditing

---

## 3. FUNCIONALIDADES DETALHADAS

### 3.1 Dashboard Administrativo

#### 3.1.1 Métricas Principais
- **Total de Membros**: Ativos, inativos, suspensos
- **Inscrições**: Pendentes, aprovadas, rejeitadas (últimos 30 dias)
- **Pagamentos**: Receitas do mês, inadimplência
- **Documentos**: Pendentes de validação, expirados
- **Exames**: Agendados, realizados, resultados pendentes
- **Residências**: Candidaturas ativas, vagas disponíveis

#### 3.1.2 Gráficos e Visualizações
- Gráfico de inscrições por mês (últimos 12 meses)
- Distribuição de membros por especialidade
- Status de pagamentos (pagos vs. pendentes)
- Performance de validação de documentos
- Atividade de usuários (logins, ações)

#### 3.1.3 Alertas e Notificações
- Documentos próximos do vencimento
- Pagamentos em atraso
- Processos pendentes há mais de 7 dias
- Falhas no sistema
- Tentativas de acesso não autorizado

### 3.2 Gestão de Usuários

#### 3.2.1 Funcionalidades CRUD
- **Criar Usuário**: Formulário completo com validações
- **Listar Usuários**: Tabela com filtros e paginação
- **Editar Usuário**: Atualização de dados e permissões
- **Visualizar Perfil**: Detalhes completos do usuário
- **Ativar/Desativar**: Controle de status do usuário
- **Reset de Senha**: Geração de nova senha temporária

#### 3.2.2 Campos do Usuário
- Dados pessoais (nome, email, telefone)
- Dados profissionais (cargo, departamento)
- Configurações de conta (status, MFA)
- Roles e permissões atribuídas
- Histórico de atividades
- Último login e sessões ativas

#### 3.2.3 Validações
- Email único no sistema
- Telefone único (se fornecido)
- Senha forte (mínimo 8 caracteres, maiúscula, minúscula, número)
- Validação de formato de email
- Verificação de duplicidade de dados

### 3.3 Gestão de Roles e Permissões

#### 3.3.1 Sistema RBAC
- **Roles**: Grupos de permissões (Admin, Secretariado, Validador, etc.)
- **Permissions**: Ações específicas (users.create, members.view, etc.)
- **Hierarquia**: Roles podem herdar permissões de outros roles
- **Contexto**: Permissões podem ser específicas por módulo

#### 3.3.2 Roles Predefinidos
1. **Super Admin**: Acesso total ao sistema
2. **Admin**: Gestão completa exceto configurações críticas
3. **Secretariado**: Gestão de inscrições e documentos
4. **Validador**: Validação de documentos
5. **Avaliador**: Avaliação de exames
6. **Tesouraria**: Gestão financeira
7. **Conselho**: Aprovações e decisões
8. **Auditor**: Apenas visualização de logs
9. **Membro**: Acesso ao próprio perfil
10. **Candidato**: Submissão de processos

#### 3.3.3 Permissões por Módulo
- **users**: create, read, update, delete, manage_roles
- **members**: create, read, update, delete, activate, suspend
- **registrations**: create, read, update, approve, reject
- **documents**: upload, validate, approve, reject
- **exams**: create, schedule, evaluate, publish_results
- **payments**: view, process, reconcile, refund
- **reports**: generate, export, view_financial
- **system**: configure, backup, maintenance

### 3.4 Configurações do Sistema

#### 3.4.1 Configurações Gerais
- **Informações da OrMM**: Nome, endereço, contatos
- **Configurações de Email**: SMTP, templates padrão
- **Configurações de SMS**: Gateway, remetente
- **Configurações de Backup**: Frequência, retenção
- **Configurações de Segurança**: Políticas de senha, MFA

#### 3.4.2 Configurações de Negócio
- **Taxas e Emolumentos**: Valores por tipo de processo
- **Prazos**: Tempos limite para cada processo
- **Documentos Obrigatórios**: Por tipo de inscrição
- **Especialidades Médicas**: Lista atualizada
- **Instituições**: Universidades e hospitais reconhecidos

#### 3.4.3 Configurações de Integração
- **Pagamentos**: APIs de carteiras móveis e bancos
- **Notificações**: Configurações de email e SMS
- **QR Codes**: Configurações de geração
- **Certificados Digitais**: Configurações de assinatura

### 3.5 Auditoria e Logs

#### 3.5.1 Tipos de Logs
- **Login/Logout**: Tentativas de acesso
- **CRUD Operations**: Criação, edição, exclusão de registros
- **Aprovações/Rejeições**: Decisões administrativas
- **Pagamentos**: Transações financeiras
- **Configurações**: Alterações no sistema
- **Segurança**: Tentativas de acesso não autorizado

#### 3.5.2 Informações Registradas
- **Usuário**: Quem executou a ação
- **Ação**: Tipo de operação realizada
- **Entidade**: Modelo/registro afetado
- **Dados Antigos**: Estado anterior (para updates)
- **Dados Novos**: Estado posterior (para updates)
- **Timestamp**: Data e hora da ação
- **IP Address**: Endereço IP do usuário
- **User Agent**: Navegador/dispositivo utilizado

#### 3.5.3 Visualização e Filtros
- **Filtros por Data**: Período específico
- **Filtros por Usuário**: Ações de usuário específico
- **Filtros por Ação**: Tipo de operação
- **Filtros por Módulo**: Área do sistema
- **Busca**: Texto livre nos logs
- **Exportação**: CSV, PDF para auditoria externa

### 3.6 Relatórios Administrativos

#### 3.6.1 Relatórios Operacionais
- **Inscrições por Período**: Estatísticas de candidaturas
- **Status de Processos**: Distribuição por estado
- **Performance de Validação**: Tempos médios de processamento
- **Atividade de Usuários**: Logins e ações por usuário
- **Documentos Pendentes**: Lista de documentos aguardando validação

#### 3.6.2 Relatórios Financeiros
- **Receitas por Período**: Arrecadação mensal/anual
- **Inadimplência**: Membros com pagamentos em atraso
- **Reconciliação**: Comparação com extratos bancários
- **Taxas por Tipo**: Receita por categoria de processo
- **Projeções**: Estimativas de receita futura

#### 3.6.3 Relatórios de Conformidade
- **Auditoria de Acessos**: Logs de segurança
- **Conformidade de Processos**: Adesão aos prazos
- **Qualidade de Dados**: Integridade dos registros
- **Backup e Recuperação**: Status das operações
- **Conformidade Legal**: Adesão aos regulamentos

---

## 4. IMPLEMENTAÇÃO TÉCNICA

### 4.1 Controllers

#### 4.1.1 DashboardController
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Document;
use App\Models\Exam;
use App\Models\ResidencyApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = $this->getMetrics();
        $charts = $this->getChartsData();
        $alerts = $this->getAlerts();
        
        return view('admin.dashboard.index', compact('metrics', 'charts', 'alerts'));
    }

    private function getMetrics(): array
    {
        return [
            'total_members' => Member::count(),
            'active_members' => Member::where('status', 'active')->count(),
            'pending_registrations' => Registration::where('status', 'pending')->count(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'pending_documents' => Document::where('status', 'pending')->count(),
            'scheduled_exams' => Exam::where('exam_date', '>=', now())->count(),
            'active_residencies' => ResidencyApplication::where('status', 'active')->count(),
        ];
    }

    private function getChartsData(): array
    {
        return [
            'registrations_by_month' => $this->getRegistrationsByMonth(),
            'members_by_specialty' => $this->getMembersBySpecialty(),
            'payment_status' => $this->getPaymentStatus(),
            'document_validation_performance' => $this->getDocumentValidationPerformance(),
        ];
    }

    private function getAlerts(): array
    {
        return [
            'expiring_documents' => Document::where('expiry_date', '<=', now()->addDays(30))->count(),
            'overdue_payments' => Payment::where('due_date', '<', now())->where('status', 'pending')->count(),
            'pending_processes' => Registration::where('status', 'pending')
                ->where('submission_date', '<=', now()->subDays(7))->count(),
        ];
    }

    private function getMonthlyRevenue(): float
    {
        return Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->where('status', 'completed')
            ->sum('amount');
    }

    private function getRegistrationsByMonth(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $data[] = [
                'month' => $date->format('M Y'),
                'count' => Registration::whereMonth('submission_date', $date->month)
                    ->whereYear('submission_date', $date->year)
                    ->count()
            ];
        }
        return $data;
    }

    private function getMembersBySpecialty(): array
    {
        return Member::selectRaw('specialty, COUNT(*) as count')
            ->groupBy('specialty')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getPaymentStatus(): array
    {
        $total = Payment::count();
        $completed = Payment::where('status', 'completed')->count();
        $pending = Payment::where('status', 'pending')->count();
        $failed = Payment::where('status', 'failed')->count();

        return [
            'completed' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
            'pending' => $total > 0 ? round(($pending / $total) * 100, 2) : 0,
            'failed' => $total > 0 ? round(($failed / $total) * 100, 2) : 0,
        ];
    }

    private function getDocumentValidationPerformance(): array
    {
        $total = Document::count();
        $validated = Document::where('status', 'validated')->count();
        $rejected = Document::where('status', 'rejected')->count();
        $pending = Document::where('status', 'pending')->count();

        return [
            'validated' => $total > 0 ? round(($validated / $total) * 100, 2) : 0,
            'rejected' => $total > 0 ? round(($rejected / $total) * 100, 2) : 0,
            'pending' => $total > 0 ? round(($pending / $total) * 100, 2) : 0,
        ];
    }
}
```

#### 4.1.2 UserController
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Actions\Admin\CreateUserAction;
use App\Actions\Admin\UpdateUserAction;
use App\Actions\Admin\DeleteUserAction;
use App\Data\Admin\UserData;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'permissions']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request, CreateUserAction $action)
    {
        $userData = UserData::from($request);
        $user = $action->execute($userData);
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'permissions']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user, UpdateUserAction $action)
    {
        $userData = UserData::from($request);
        $action->execute($user, $userData);
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user, DeleteUserAction $action)
    {
        $action->execute($user);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário removido com sucesso.');
    }
}
```

### 4.2 Actions (Action Pattern)

#### 4.2.1 CreateUserAction
```php
<?php

namespace App\Actions\Admin;

use App\Models\User;
use App\Data\Admin\UserData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateUserAction
{
    public function execute(UserData $userData): User
    {
        return DB::transaction(function () use ($userData) {
            $user = User::create([
                'name' => $userData->name,
                'email' => $userData->email,
                'phone' => $userData->phone,
                'password' => Hash::make($userData->password),
                'status' => $userData->status ?? 'active',
            ]);

            if ($userData->roles) {
                $user->assignRole($userData->roles);
            }

            if ($userData->permissions) {
                $user->givePermissionTo($userData->permissions);
            }

            return $user;
        });
    }
}
```

#### 4.2.2 UpdateUserAction
```php
<?php

namespace App\Actions\Admin;

use App\Models\User;
use App\Data\Admin\UserData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    public function execute(User $user, UserData $userData): void
    {
        DB::transaction(function () use ($user, $userData) {
            $updateData = [
                'name' => $userData->name,
                'email' => $userData->email,
                'phone' => $userData->phone,
                'status' => $userData->status ?? $user->status,
            ];

            if ($userData->password) {
                $updateData['password'] = Hash::make($userData->password);
            }

            $user->update($updateData);

            if ($userData->roles) {
                $user->syncRoles($userData->roles);
            }

            if ($userData->permissions) {
                $user->syncPermissions($userData->permissions);
            }
        });
    }
}
```

#### 4.2.3 DeleteUserAction
```php
<?php

namespace App\Actions\Admin;

use App\Models\User;
use Exception;

class DeleteUserAction
{
    public function execute(User $user): void
    {
        // Verificar se o usuário pode ser deletado
        if ($user->hasRole('super-admin')) {
            throw new Exception('Não é possível deletar um super administrador.');
        }

        $user->delete();
    }
}
```

### 4.3 Laravel Data Classes

#### 4.3.1 UserData
```php
<?php

namespace App\Data\Admin;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Nullable;

class UserData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public string $name,

        #[Required, Email, Unique('users', 'email')]
        public string $email,

        #[Nullable, StringType, Max(20), Unique('users', 'phone')]
        public ?string $phone,

        #[Required, StringType, Min(8), Confirmed]
        public string $password,

        #[Required, In(['active', 'inactive', 'suspended'])]
        public string $status = 'active',

        #[ArrayType]
        public ?array $roles = null,

        #[ArrayType]
        public ?array $permissions = null,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }

    public static function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está em uso.',
            'phone.unique' => 'Este telefone já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'status.required' => 'O status é obrigatório.',
            'roles.array' => 'Os roles devem ser um array.',
            'roles.*.exists' => 'Um ou mais roles selecionados são inválidos.',
            'permissions.array' => 'As permissões devem ser um array.',
            'permissions.*.exists' => 'Uma ou mais permissões selecionadas são inválidas.',
        ];
    }

    public static function authorize(): bool
    {
        return auth()->user()->can('users.create');
    }
}
```

#### 4.3.2 RoleData
```php
<?php

namespace App\Data\Admin;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Exists;

class RoleData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255), Unique('roles', 'name')]
        public string $name,

        #[StringType, Max(255)]
        public ?string $description = null,

        #[ArrayType]
        public ?array $permissions = null,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }

    public static function messages(): array
    {
        return [
            'name.required' => 'O nome do role é obrigatório.',
            'name.unique' => 'Este nome de role já está em uso.',
            'permissions.array' => 'As permissões devem ser um array.',
            'permissions.*.exists' => 'Uma ou mais permissões selecionadas são inválidas.',
        ];
    }

    public static function authorize(): bool
    {
        return auth()->user()->can('roles.create');
    }
}
```

### 4.4 Modelos com Traits de Auditoria

#### 4.4.1 User Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class User extends Authenticatable implements Auditable
{
    use HasFactory, Notifiable, HasRoles, AuditableTrait;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $auditInclude = [
        'name',
        'email',
        'phone',
        'status',
    ];

    protected $auditExclude = [
        'password',
        'remember_token',
    ];
}
```

#### 4.4.2 SystemConfig Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class SystemConfig extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    protected $auditInclude = [
        'key',
        'value',
        'type',
        'description',
        'is_public',
    ];

    public static function get(string $key, $default = null)
    {
        $config = static::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    public static function set(string $key, $value, string $type = 'string', string $description = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );
    }
}
```

### 4.4 Views

#### 4.4.1 Dashboard Principal
```blade
{{-- resources/views/admin/dashboard/index.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alertas -->
            @if(count($alerts) > 0)
                <div class="mb-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Atenção Necessária
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @if($alerts['expiring_documents'] > 0)
                                            <li>{{ $alerts['expiring_documents'] }} documentos próximos do vencimento</li>
                                        @endif
                                        @if($alerts['overdue_payments'] > 0)
                                            <li>{{ $alerts['overdue_payments'] }} pagamentos em atraso</li>
                                        @endif
                                        @if($alerts['pending_processes'] > 0)
                                            <li>{{ $alerts['pending_processes'] }} processos pendentes há mais de 7 dias</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Métricas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total de Membros</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['total_members']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Membros Ativos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['active_members']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Inscrições Pendentes</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['pending_registrations']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Receita do Mês</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['monthly_revenue'], 2) }} MT</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Inscrições por Mês</h3>
                        <canvas id="registrationsChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Membros por Especialidade</h3>
                        <canvas id="specialtyChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Gráfico de Inscrições
            const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
            new Chart(registrationsCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($charts['registrations_by_month'], 'month')) !!},
                    datasets: [{
                        label: 'Inscrições',
                        data: {!! json_encode(array_column($charts['registrations_by_month'], 'count')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Gráfico de Especialidades
            const specialtyCtx = document.getElementById('specialtyChart').getContext('2d');
            new Chart(specialtyCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_column($charts['members_by_specialty'], 'specialty')) !!},
                    datasets: [{
                        data: {!! json_encode(array_column($charts['members_by_specialty'], 'count')) !!},
                        backgroundColor: [
                            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
                            '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    @endpush
</x-admin-layout>
```

---

## 5. CRONOGRAMA DE DESENVOLVIMENTO

### 5.1 Fase 1: Estrutura Base e Pacotes (Semana 1)
- [x] Instalação e configuração dos pacotes necessários
  - [x] spatie/laravel-permission
  - [x] owen-it/laravel-auditing
  - [x] spatie/laravel-backup
  - [x] barryvdh/laravel-dompdf
  - [x] maatwebsite/excel
- [x] Configuração de rotas e middleware
- [x] Criação da estrutura de diretórios
- [x] Implementação do layout administrativo
- [x] Configuração de permissões básicas
- [x] Criação dos modelos principais com traits de auditoria

### 5.2 Fase 2: Dashboard e Métricas (Semana 2)
- [x] Implementação do DashboardController
- [x] Desenvolvimento das views do dashboard
- [x] Integração com Chart.js
- [x] Implementação de alertas e notificações
- [x] Criação de widgets reutilizáveis

### 5.3 Fase 3: Gestão de Usuários (Semana 3)
- [x] Implementação do UserController
- [x] Criação das Actions (CreateUserAction, UpdateUserAction, DeleteUserAction)
- [x] Criação das Data Classes (UserData, RoleData)
- [x] Desenvolvimento das views CRUD
- [x] Integração com Spatie Laravel Permission

### 5.4 Fase 4: Roles e Permissões (Semana 4)
- [x] Implementação do RoleController
- [x] Criação do PermissionController
- [x] Desenvolvimento das views de gestão
- [x] Configuração do sistema RBAC com Spatie
- [x] Criação de roles e permissões predefinidos
- [x] Implementação de middleware de autorização

### 5.5 Fase 5: Configurações e Auditoria (Semana 5)
- [x] Implementação do SystemConfigController
- [x] Criação do AuditController
- [x] Desenvolvimento das views de configuração
- [x] Configuração do Laravel Auditing
- [x] Implementação de logs de auditoria
- [x] Sistema de backup com Spatie Laravel Backup

### 5.6 Fase 6: Relatórios e Finalização (Semana 6)
- [x] Implementação do ReportController
- [x] Criação de relatórios operacionais
- [x] Desenvolvimento de relatórios financeiros
- [x] Implementação de exportação (PDF com DomPDF, Excel com Maatwebsite)
- [x] Testes e otimizações
- [x] Documentação final

---

## 6. TESTES

### 6.1 Testes Unitários
- Testes para todos os Services
- Testes para validações de Form Requests
- Testes para métodos dos Controllers
- Cobertura mínima de 80%

### 6.2 Testes de Integração
- Testes de fluxos completos
- Testes de permissões e autorização
- Testes de integração com banco de dados
- Testes de APIs

### 6.3 Testes de Interface
- Testes de usabilidade
- Testes de responsividade
- Testes de acessibilidade
- Testes de performance

---

## 7. SEGURANÇA

### 7.1 Autenticação e Autorização
- Middleware de autenticação obrigatório
- Verificação de permissões em todas as ações
- Proteção contra CSRF
- Validação de entrada de dados

### 7.2 Auditoria e Logs
- Log de todas as ações administrativas
- Rastreabilidade completa
- Proteção contra alteração de logs
- Backup seguro dos logs

### 7.3 Proteção de Dados
- Criptografia de dados sensíveis
- Sanitização de entrada
- Validação rigorosa
- Proteção contra SQL Injection

---

## 8. DOCUMENTAÇÃO

### 8.1 Documentação Técnica
- Comentários no código
- Documentação de APIs
- Diagramas de arquitetura
- Guias de instalação

### 8.2 Documentação de Usuário
- Manual do administrador
- Guias de procedimentos
- Tutoriais em vídeo
- FAQ

---

## 9. CRITÉRIOS DE ACEITAÇÃO

### 9.1 Funcionalidades
- [ ] Dashboard com métricas em tempo real
- [ ] Gestão completa de usuários
- [ ] Sistema RBAC funcional
- [ ] Configurações do sistema
- [ ] Logs de auditoria completos
- [ ] Relatórios operacionais e financeiros

### 9.2 Performance
- [ ] Tempo de resposta < 2 segundos
- [ ] Suporte a 100 usuários simultâneos
- [ ] Carga de dados otimizada
- [ ] Cache implementado

### 9.3 Segurança
- [ ] Autenticação obrigatória
- [ ] Autorização por permissões
- [ ] Logs de auditoria
- [ ] Proteção contra vulnerabilidades

### 9.4 Usabilidade
- [ ] Interface intuitiva
- [ ] Responsiva para mobile
- [ ] Acessível (WCAG 2.1)
- [ ] Documentação completa

---

## 10. INSTALAÇÃO DOS PACOTES

### 10.1 Comandos de Instalação
```bash
# Pacotes principais
composer require spatie/laravel-permission
composer require owen-it/laravel-auditing
composer require spatie/laravel-backup
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel

# Pacotes de desenvolvimento
composer require spatie/laravel-query-builder
composer require spatie/laravel-data
composer require spatie/laravel-model-states
composer require spatie/laravel-sluggable
```

### 10.2 Configurações Necessárias
```bash
# Publicar configurações
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="OwenIt\Auditing\AuditingServiceProvider"
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
php artisan vendor:publish --provider="Spatie\LaravelData\LaravelDataServiceProvider"

# Executar migrações
php artisan migrate
```

### 10.3 Configuração de Traits nos Modelos
```php
// User Model
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class User extends Authenticatable implements Auditable
{
    use HasRoles, AuditableTrait;
    // ...
}
```

### 10.4 Configuração do Laravel Data
```php
// config/data.php
return [
    'transformers' => [
        // Configurações de transformação
    ],
    'rules' => [
        // Regras globais
    ],
];
```

### 10.5 Exemplo de Uso das Data Classes
```php
// No Controller
public function store(Request $request, CreateUserAction $action)
{
    $userData = UserData::from($request);
    $user = $action->execute($userData);
    
    return redirect()->route('admin.users.show', $user)
        ->with('success', 'Usuário criado com sucesso.');
}

// A mesma Data Class funciona para create e update
public function update(Request $request, User $user, UpdateUserAction $action)
{
    $userData = UserData::from($request);
    $action->execute($user, $userData);
    
    return redirect()->route('admin.users.show', $user)
        ->with('success', 'Usuário atualizado com sucesso.');
}
```

---

## 11. CONCLUSÃO

O Módulo de Administração é fundamental para o funcionamento do sistema OrMM, fornecendo todas as ferramentas necessárias para a gestão eficiente da ordem médica. Este plano detalha a implementação completa, desde a arquitetura até os testes finais, garantindo um sistema robusto, seguro e escalável.

A implementação seguirá as melhores práticas de desenvolvimento Laravel, utilizando pacotes especializados como Spatie Laravel Permission para RBAC e Laravel Auditing para auditoria automática. O Action Pattern será usado para encapsular lógica de negócio complexa, mantendo os controllers limpos e focados. As Laravel Data Classes substituirão os Form Requests tradicionais, oferecendo uma única classe para validação tanto em criação quanto em atualização, com validação automática e type safety.

O cronograma de 6 semanas permite uma entrega estruturada e testada, garantindo a qualidade do produto final com foco na segurança, performance e usabilidade.

---

**Documento elaborado em:** 27/01/2025  
**Versão:** 1.1  
**Status:** Aprovado para implementação  
**Próxima revisão:** Após conclusão da Fase 1
