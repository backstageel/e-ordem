# Plano de Desenvolvimento do Módulo de Membros
## e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 2.0 (Atualizada)  
**Data:** 27 de Janeiro de 2025  
**Autor:** Equipe de Desenvolvimento MillPáginas  
**Base:** Especificação Técnica v2.0 - 20/11/2025

---

## 1. VISÃO GERAL DO MÓDULO DE MEMBROS

### 1.1 Objetivo
O Módulo de Gestão de Membros mantém registro completo de todos os membros da OrMM, gere quotas com cálculo automático de multas, emite cartões digitais com QR code, gera relatórios e implementa sistema de alertas automáticos.

**Responsabilidades Principais:**
- Gestão completa do cadastro de membros da OrMM
- Gestão de quotas com cálculo automático de multas (0,5 sobre valor em atraso)
- Emissão e gestão de cartões profissionais digitais com QR code
- Sistema de alertas automáticos (documentos, quotas, conformidade)
- Relatórios e análises estatísticas
- Portal de auto-serviço para membros

### 1.2 Base Contratual
- **TdR Seção 3.2**: Gestão de Membros
- **Especificação Técnica v2.0**: Requisitos funcionais detalhados (REQ-MEM-001 a REQ-MEM-006)
- **Anexo B**: Tabela Oficial de Taxas OrMM
- **Documento Requisitos MillPáginas**: FR-MEM-001 a FR-MEM-012

### 1.3 Escopo do Módulo
Este módulo implementa funcionalidades completas para:

1. **Registro Completo de Membros**
   - Dados pessoais, profissionais e de contacto
   - Formação académica e profissional
   - Documentos essenciais (BI, diplomas, certidões)
   - Histórico profissional completo

2. **Gestão de Estados**
   - 5 estados: ATIVO, SUSPENSO, INATIVO, IRREGULAR, CANCELADO
   - Transições automáticas e controladas
   - Restrições por estado
   - Histórico completo de mudanças

3. **Gestão de Quotas**
   - Quota anual: 4.000 MT (vigente 2020-2025)
   - Cálculo automático de atrasos e multas
   - Multa: 0,5 sobre valor da quota em atraso
   - Alertas automáticos (30 dias antes, vencimento, 30/60/90 dias após)
   - Mudança automática para IRREGULAR após 90 dias
   - Suspensão automática por inadimplência (configurável)

4. **Cartões Profissionais**
   - Cartão digital com QR code
   - Formato PDF descarregável + versão web
   - Verificação pública via eordem.ormm.co.mz/verifica/[NUMERO]
   - Emissão: 300 MT (inicial), 500 MT (renovação)
   - Histórico de emissões/re-emissões
   - Invalidação automática se suspenso/cancelado

5. **Relatórios e Filtros**
   - Filtros: província, especialidade, estado, nacionalidade, quotas
   - Relatórios: recebimentos, inadimplência, estatísticas
   - Exportação: Excel (CSV/XLS), PDF

6. **Sistema de Alertas Automáticos**
   - Documentos expirando (60 dias antes)
   - Quotas (30 dias antes, vencimento, 30/60/90 dias após)
   - Mudanças de estado
   - Atualização cadastral obrigatória

---

## 2. REQUISITOS FUNCIONAIS DETALHADOS

### 2.1 REQ-MEM-001: Registro Completo de Membros

**Descrição:** Sistema DEVE manter registro completo de todos os membros com todas as categorias de informação.

**Categorias de Informação:**

1. **Dados Pessoais**
   - Nome completo
   - Data de nascimento
   - Nacionalidade
   - Naturalidade (país, província, distrito)
   - Estado civil
   - Documentos de identificação (BI, DIRE, Passaporte)
   - NUIT (Número Único de Identificação Tributária)
   - Fotografia profissional

2. **Dados Profissionais**
   - Número de membro (único e sequencial)
   - Número de processo de inscrição
   - Data de registro
   - Categoria profissional (Grau A/B/C/D)
   - Especialidade e sub-especialidade
   - Anos de experiência
   - Local de trabalho
   - Endereço profissional
   - Contactos profissionais

3. **Dados de Contacto**
   - Email principal
   - Telefone principal
   - Telefone alternativo
   - Endereço de residência
   - Província e distrito de residência

4. **Formação**
   - Instituição de formação
   - País de formação
   - Data de graduação
   - Grau académico
   - Especialização (se aplicável)
   - Instituição de especialização
   - Ano de especialização
   - País de especialização
   - Outras qualificações

5. **Documentos**
   - BI/DIRE/Passaporte (válido)
   - Diploma de licenciatura
   - Certificado de especialidade (se aplicável)
   - Certidões profissionais
   - Outros documentos relevantes

6. **Histórico**
   - Histórico de status
   - Histórico de quotas
   - Histórico de pagamentos
   - Histórico de cartões
   - Histórico de alterações cadastrais

**Critério de Aceitação:**
- Todos os campos obrigatórios implementados
- Validação de dados completa
- Upload de documentos funcional
- Histórico completo e rastreável

**Base:** TdR 3.2.1, MillPáginas FR-MEM-001

### 2.2 REQ-MEM-002: Estados de Membro

**Descrição:** Sistema DEVE implementar 5 estados com transições automáticas e controladas.

**Estados Obrigatórios:**

1. **ATIVO (ACTIVE)**
   - Membro em pleno exercício
   - Quotas regulares (sem atrasos)
   - Documentos válidos
   - Pode emitir cartão
   - Acesso completo ao sistema

2. **SUSPENSO (SUSPENDED)**
   - Suspensão por inadimplência (>90 dias de atraso)
   - Suspensão disciplinar
   - Suspensão voluntária (a pedido)
   - Cartões invalidados automaticamente
   - Acesso restrito ao sistema

3. **INATIVO (INACTIVE)**
   - Membro não exerce atividade
   - Suspensão temporária
   - Pode ser reativado
   - Cartões invalidados

4. **IRREGULAR (IRREGULAR)**
   - Quotas em atraso (>90 dias)
   - Mudança automática de ATIVO para IRREGULAR
   - Alertas de inadimplência enviados
   - Cartões bloqueados
   - Suspensão automática após X dias configuráveis

5. **CANCELADO (CANCELED)**
   - Cancelamento definitivo
   - Por pedido do membro
   - Por decisão disciplinar
   - Dados mantidos para auditoria
   - Cartões invalidados permanentemente

**Transições e Regras:**
- ATIVO → IRREGULAR: Automática após 90 dias de atraso de quota
- IRREGULAR → SUSPENSO: Automática após X dias configuráveis (padrão: 30 dias)
- ATIVO → SUSPENSO: Manual (disciplinar) ou automática (inadimplência)
- SUSPENSO → ATIVO: Manual após regularização
- IRREGULAR → ATIVO: Manual após pagamento de quotas em atraso
- QUALQUER → CANCELADO: Manual (irreversível)

**Restrições por Estado:**
- **ATIVO:** Acesso completo, pode emitir cartão, pode pagar quotas
- **IRREGULAR:** Acesso limitado, não pode emitir cartão, alertas de quota
- **SUSPENSO:** Acesso restrito, cartões invalidados, não pode pagar quotas online
- **INATIVO:** Acesso limitado, pode ser reativado
- **CANCELADO:** Acesso bloqueado, dados apenas para auditoria

**Critério de Aceitação:**
- Todos os 5 estados implementados
- Transições automáticas funcionando
- Restrições por estado aplicadas
- Histórico de mudanças de estado completo

**Base:** TdR 3.2.2, MillPáginas FR-MEM-002

### 2.3 REQ-MEM-003: Gestão de Quotas

**Descrição:** Sistema DEVE implementar gestão completa de quotas com cálculo automático de multas e alertas.

**Especificação Detalhada:**

1. **Quota Anual**
   - Valor: 4.000 MT (vigente entre 2020-2025)
   - Cobrança anual (pode ser dividida em mensalidades)
   - Geração automática no início do ano
   - Vencimento configurável (padrão: 31 de dezembro)

2. **Cálculo Automático de Atrasos**
   - Identificação automática de quotas em atraso
   - Cálculo de dias em atraso
   - Cálculo de multa: 0,5 sobre valor da quota em atraso
   - Exemplo: Quota de 4.000 MT em atraso = 2.000 MT de multa
   - Total devido: 4.000 MT + 2.000 MT = 6.000 MT

3. **Sistema de Alertas**
   - **30 dias antes do vencimento:** Lembrete de quota a vencer
   - **No vencimento:** Notificação de quota vencida
   - **30 dias após vencimento:** Alerta de inadimplência
   - **60 dias após vencimento:** Alerta crítico de inadimplência
   - **90 dias após vencimento:** Mudança automática para IRREGULAR + alerta de suspensão iminente
   - **X dias após vencimento (configurável):** Suspensão automática

4. **Mudança Automática para IRREGULAR**
   - Após 90 dias de atraso, membro muda automaticamente para IRREGULAR
   - Notificação automática da mudança de estado
   - Cartões bloqueados automaticamente
   - Relatório de inadimplência gerado

5. **Suspensão Automática**
   - Após X dias configuráveis (padrão: 120 dias) de atraso
   - Mudança automática de IRREGULAR para SUSPENSO
   - Notificação automática de suspensão
   - Cartões invalidados
   - Acesso restrito ao sistema

6. **Relatórios de Inadimplência**
   - Lista de membros com quotas em atraso
   - Valores devidos (quota + multa)
   - Dias em atraso
   - Exportação Excel/PDF
   - Filtros: por província, especialidade, dias de atraso

**Critério de Aceitação:**
- Cálculo automático de multas funcionando (0,5 sobre valor em atraso)
- Alertas enviados nos prazos corretos
- Mudança automática para IRREGULAR após 90 dias
- Suspensão automática após X dias configuráveis
- Relatórios de inadimplência funcionais

**Base:** TdR 3.2.3, MillPáginas FR-MEM-003

### 2.4 REQ-MEM-004: Cartão Digital com QR Code

**Descrição:** Sistema DEVE emitir cartões digitais com QR code para verificação pública.

**Especificação Detalhada:**

1. **Formato do Cartão**
   - **PDF descarregável:** Para impressão e arquivo
   - **Versão web:** Visualização online no portal do membro
   - Template personalizado por categoria (Grau A/B/C/D)
   - Design profissional com elementos de segurança

2. **Conteúdo Obrigatório**
   - Fotografia do membro
   - Nome completo
   - Número de membro
   - Especialidade (se aplicável)
   - Categoria (Grau A/B/C/D)
   - Data de emissão
   - Data de validade
   - QR code único
   - Elementos de segurança (marca d'água, selo)

3. **QR Code**
   - Código único por cartão
   - Link: `eordem.ormm.co.mz/verifica/[NUMERO_MEMBRO]`
   - Verificação pública (sem autenticação)
   - Exibe: nome, número, status, validade, especialidade
   - Validação de autenticidade do cartão

4. **Emissão e Renovação**
   - **Emissão inicial:** 300 MT (no ato da inscrição)
   - **Renovação:** 500 MT (renovação de cartão)
   - **Re-emissão:** 500 MT (perda, extravio, inutilização)
   - Geração automática após aprovação de inscrição efetiva
   - Geração manual pelo administrador

5. **Histórico de Emissões**
   - Todas as emissões registradas
   - Data de emissão
   - Data de validade
   - Motivo da emissão (inicial, renovação, re-emissão)
   - Status do cartão (ativo, invalidado, expirado)
   - Usuário que emitiu

6. **Invalidação Automática**
   - Cartões invalidados automaticamente se membro suspenso
   - Cartões invalidados automaticamente se membro cancelado
   - Cartões invalidados automaticamente se quotas irregulares
   - Notificação ao membro sobre invalidação

7. **Validade do Cartão**
   - Baseada em status do membro
   - Baseada em validade de quotas
   - Renovação automática quando quotas regularizadas
   - Expiração automática se quotas não pagas

**Critério de Aceitação:**
- Cartões gerados em PDF e versão web
- QR codes funcionais e verificáveis publicamente
- Histórico completo de emissões
- Invalidação automática funcionando
- Integração com módulo Card

**Base:** TdR 3.2.4, MillPáginas FR-MEM-004

### 2.5 REQ-MEM-005: Relatórios e Filtros

**Descrição:** Sistema DEVE implementar relatórios completos com filtros avançados e exportação.

**Filtros Disponíveis:**

1. **Por Província**
   - Filtro por província de residência
   - Filtro por província de trabalho
   - Múltiplas províncias

2. **Por Especialidade**
   - Filtro por especialidade médica
   - Filtro por sub-especialidade
   - Múltiplas especialidades

3. **Por Estado**
   - ATIVO, SUSPENSO, INATIVO, IRREGULAR, CANCELADO
   - Múltiplos estados

4. **Por Nacionalidade**
   - Moçambicanos
   - Estrangeiros
   - Por país específico

5. **Por Quotas**
   - Quotas regulares
   - Quotas em atraso
   - Quotas pagas
   - Quotas pendentes
   - Por período (ano, mês)

6. **Outros Filtros**
   - Por data de registro
   - Por categoria (Grau A/B/C/D)
   - Por faixa etária
   - Por anos de experiência
   - Por local de trabalho

**Relatórios Disponíveis:**

1. **Relatório de Recebimentos**
   - Quotas recebidas por período
   - Multas recebidas
   - Total de recebimentos
   - Gráficos de tendências
   - Exportação Excel/PDF

2. **Relatório de Inadimplência**
   - Membros com quotas em atraso
   - Valores devidos (quota + multa)
   - Dias em atraso
   - Distribuição por província/especialidade
   - Exportação Excel/PDF

3. **Relatório Estatístico**
   - Total de membros por estado
   - Distribuição por província
   - Distribuição por especialidade
   - Distribuição por categoria (Grau)
   - Distribuição por nacionalidade
   - Gráficos e visualizações
   - Exportação Excel/PDF

4. **Relatório de Membros**
   - Lista completa de membros
   - Com filtros aplicados
   - Campos selecionáveis
   - Exportação Excel/PDF/CSV

**Critério de Aceitação:**
- Todos os filtros funcionando
- Relatórios gerados corretamente
- Exportação Excel/PDF/CSV funcional
- Gráficos e visualizações implementados

**Base:** TdR 3.2.5, MillPáginas FR-MEM-005

### 2.6 REQ-MEM-006: Sistema de Alertas Automáticos

**Descrição:** Sistema DEVE implementar alertas automáticos para documentos, quotas e mudanças de estado.

**Tipos de Alertas:**

1. **Alertas de Documentos**
   - **Documentos expirando:** Alerta 60 dias antes da expiração
   - **Documentos expirados:** Notificação imediata de expiração
   - **Documentos pendentes:** Lembrete de documentos não validados
   - **Documentos rejeitados:** Notificação com motivo da rejeição

2. **Alertas de Quotas**
   - **30 dias antes do vencimento:** Lembrete de quota a vencer
   - **No vencimento:** Notificação de quota vencida
   - **30 dias após vencimento:** Alerta de inadimplência
   - **60 dias após vencimento:** Alerta crítico de inadimplência
   - **90 dias após vencimento:** Alerta de mudança para IRREGULAR
   - **X dias após vencimento:** Alerta de suspensão iminente

3. **Alertas de Mudanças de Estado**
   - Notificação quando muda para IRREGULAR
   - Notificação quando muda para SUSPENSO
   - Notificação quando muda para CANCELADO
   - Notificação quando reativado para ATIVO

4. **Alertas de Conformidade**
   - **Atualização cadastral obrigatória:** Lembrete de atualização a cada X anos (configurável)
   - **Dados incompletos:** Notificação de campos pendentes
   - **Status irregular:** Alerta de mudança de status

**Canais de Notificação:**
- **Email:** Notificações padrão para todos os alertas
- **SMS:** Alertas críticos (suspensão, cancelamento, quotas críticas)
- **In-app:** Notificações no portal do membro
- **Portal administrativo:** Alertas para gestores

**Critério de Aceitação:**
- Alertas enviados nos prazos corretos
- Canais de notificação funcionando
- Templates personalizáveis
- Logs de entrega
- Retry automático em caso de falha

**Base:** TdR 3.2.6, MillPáginas FR-MEM-006

---

## 3. REGRAS DE NEGÓCIO

### 3.1 Regras de Quotas

1. **Quota Anual**
   - Valor fixo: 4.000 MT (vigente 2020-2025)
   - Cobrança anual (pode ser dividida em mensalidades)
   - Geração automática no início do ano

2. **Cálculo de Multas**
   - Multa: 0,5 (50%) sobre valor da quota em atraso
   - Exemplo: Quota de 4.000 MT em atraso = 2.000 MT de multa
   - Total devido: Quota + Multa

3. **Mudança para IRREGULAR**
   - Automática após 90 dias de atraso
   - Notificação automática
   - Cartões bloqueados

4. **Suspensão Automática**
   - Após X dias configuráveis (padrão: 120 dias) de atraso
   - Mudança de IRREGULAR para SUSPENSO
   - Notificação automática
   - Cartões invalidados

### 3.2 Regras de Cartões

1. **Emissão de Cartão**
   - Requisitos: Status ATIVO + Quotas regulares + Documentos válidos
   - Taxa: 300 MT (inicial), 500 MT (renovação/re-emissão)

2. **Validade do Cartão**
   - Baseada em status do membro
   - Baseada em validade de quotas
   - Renovação automática quando quotas regularizadas

3. **Invalidação**
   - Automática se status SUSPENSO
   - Automática se status CANCELADO
   - Automática se quotas irregulares
   - Manual pelo administrador

### 3.3 Regras de Estados

1. **Transições Automáticas**
   - ATIVO → IRREGULAR: 90 dias de atraso de quota
   - IRREGULAR → SUSPENSO: X dias configuráveis após mudança para IRREGULAR
   - Qualquer → CANCELADO: Manual (irreversível)

2. **Restrições por Estado**
   - ATIVO: Acesso completo, pode emitir cartão
   - IRREGULAR: Acesso limitado, não pode emitir cartão
   - SUSPENSO: Acesso restrito, cartões invalidados
   - INATIVO: Acesso limitado, pode ser reativado
   - CANCELADO: Acesso bloqueado

### 3.4 Regras de Atualização Cadastral

1. **Atualização Obrigatória**
   - A cada X anos configuráveis (padrão: 5 anos)
   - Após mudanças significativas
   - Lembrete automático antes do prazo

2. **Validação de Dados**
   - Verificação de completude
   - Validação de documentos
   - Aprovação administrativa se necessário

---

## 4. ARQUITETURA E ESTRUTURA

### 4.1 Estrutura de Diretórios do Módulo

```
Modules/Member/
├── config/
│   └── config.php
├── database/
│   ├── migrations/
│   │   ├── create_members_table.php
│   │   ├── create_member_quotas_table.php
│   │   ├── create_member_status_history_table.php
│   │   └── create_member_cards_table.php
│   ├── seeders/
│   │   └── MemberDatabaseSeeder.php
│   └── factories/
├── routes/
│   ├── web.php (rotas organizadas por role)
│   └── api.php
├── resources/
│   └── views/
│       ├── admin/
│       │   └── members/
│       │       ├── index.blade.php
│       │       ├── show.blade.php
│       │       ├── edit.blade.php
│       │       ├── quotas.blade.php
│       │       ├── reports.blade.php
│       │       └── cards.blade.php
│       ├── member/
│       │   ├── dashboard.blade.php
│       │   ├── profile/
│       │   │   ├── index.blade.php
│       │   │   └── edit.blade.php
│       │   ├── quotas/
│       │   │   ├── index.blade.php
│       │   │   └── payment.blade.php
│       │   ├── cards/
│       │   │   ├── index.blade.php
│       │   │   └── view.blade.php
│       │   └── documents/
│       │       └── index.blade.php
│       └── components/
│           └── members/
│               ├── quota-status-badge.blade.php
│               ├── member-status-badge.blade.php
│               └── member-card.blade.php
├── src/
│   ├── Actions/
│   │   ├── CreateMemberAction.php
│   │   ├── UpdateMemberAction.php
│   │   ├── SuspendMemberAction.php
│   │   ├── ReactivateMemberAction.php
│   │   ├── GenerateMemberCardAction.php
│   │   ├── CalculateQuotasAction.php
│   │   └── CheckMemberComplianceAction.php
│   ├── Data/
│   │   ├── MemberData.php
│   │   ├── MemberProfileData.php
│   │   └── MemberQuotaData.php
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       │   └── MemberController.php
│   │       └── Member/
│   │           ├── DashboardController.php
│   │           ├── ProfileController.php
│   │           ├── QuotaController.php
│   │           └── CardController.php
│   ├── Models/
│   │   ├── Member.php
│   │   ├── MemberQuota.php
│   │   ├── MemberStatusHistory.php
│   │   └── MemberCard.php
│   ├── Services/
│   │   ├── MemberQuotaService.php
│   │   ├── MemberComplianceService.php
│   │   ├── MemberAlertService.php
│   │   ├── MemberCardService.php
│   │   └── MemberReportService.php
│   └── Providers/
│       └── MemberServiceProvider.php
└── tests/
    ├── Feature/
    │   ├── Admin/
    │   │   └── MemberControllerTest.php
    │   ├── Member/
    │   │   └── MemberPortalTest.php
    │   └── Quotas/
    │       └── QuotaManagementTest.php
    └── Unit/
```

### 4.2 Modelos Principais

#### 4.2.1 Member (Expandido)
```php
class Member extends BaseModel implements Auditable
{
    // Estados via constantes
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_IRREGULAR = 'irregular';
    const STATUS_CANCELED = 'canceled';
    
    // Relacionamentos
    public function person(): BelongsTo
    public function quotaHistory(): HasMany
    public function statusHistory(): HasMany
    public function cards(): HasMany
    public function documents(): HasMany
    public function payments(): HasMany
    
    // Métodos principais
    public function isQuotaRegular(): bool
    public function hasPendingDocuments(): bool
    public function canGenerateCard(): bool
    public function getQuotaStatus(): string
    public function totalQuotaDue(): float
    public function shouldSuspendForQuotas(): bool
    public function transitionTo(string $status, ?string $reason = null): void
}
```

#### 4.2.2 MemberQuota (Expandido)
```php
class MemberQuota extends Model
{
    // Status
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_WAIVED = 'waived';
    
    // Campos
    protected $fillable = [
        'member_id',
        'year',
        'amount',
        'due_date',
        'payment_date',
        'status',
        'payment_id',
        'penalty_amount',
        'days_overdue',
        'notes',
    ];
    
    // Métodos
    public function isOverdue(): bool
    public function calculatePenalty(): float
    public function getTotalDue(): float
}
```

#### 4.2.3 MemberStatusHistory (Novo)
```php
class MemberStatusHistory extends Model
{
    protected $fillable = [
        'member_id',
        'previous_status',
        'new_status',
        'changed_by',
        'reason',
        'notes',
        'effective_date',
        'automatic', // true se mudança automática
    ];
    
    public function member(): BelongsTo
    public function changedBy(): BelongsTo
}
```

#### 4.2.4 MemberCard (Novo/Expandido)
```php
class MemberCard extends Model
{
    protected $fillable = [
        'member_id',
        'card_number',
        'qr_code_path',
        'issue_date',
        'expiry_date',
        'status', // active, invalidated, expired
        'card_type_id',
        'revoked_at',
        'revoked_reason',
    ];
    
    public function member(): BelongsTo
    public function isActive(): bool
    public function isExpired(): bool
    public function invalidate(?string $reason = null): void
}
```

### 4.3 Services Principais

#### 4.3.1 MemberQuotaService
```php
class MemberQuotaService
{
    // Geração de quotas
    public function generateQuotasForMember(Member $member, int $year): void
    public function generateQuotasForAllMembers(int $year): void
    
    // Cálculo de multas
    public function calculatePenalty(MemberQuota $quota): float
    public function calculateOverdueAmount(Member $member): float
    
    // Verificação de status
    public function checkMemberQuotaStatus(Member $member): string
    public function shouldSuspendForQuotas(Member $member): bool
    public function getOverdueQuotas(Member $member): Collection
    
    // Alertas
    public function getQuotasExpiringSoon(int $days = 30): Collection
    public function getOverdueQuotasByDays(int $days): Collection
}
```

#### 4.3.2 MemberComplianceService
```php
class MemberComplianceService
{
    // Verificação de conformidade
    public function checkMemberCompliance(Member $member): array
    public function getExpiredDocuments(Member $member): Collection
    public function getMissingRequiredDocuments(Member $member): array
    public function needsProfileUpdate(Member $member): bool
    
    // Validação
    public function validateMemberData(Member $member): array
    public function canGenerateCard(Member $member): bool
}
```

#### 4.3.3 MemberAlertService
```php
class MemberAlertService
{
    // Alertas de quotas
    public function sendQuotaReminders(): void
    public function sendOverdueAlerts(): void
    public function sendSuspensionWarnings(): void
    
    // Alertas de documentos
    public function sendDocumentExpiryAlerts(): void
    public function sendDocumentPendingAlerts(): void
    
    // Alertas de conformidade
    public function sendComplianceReminders(): void
    public function sendProfileUpdateReminders(): void
    
    // Alertas de estado
    public function sendStatusChangeAlerts(Member $member, string $newStatus): void
}
```

#### 4.3.4 MemberCardService
```php
class MemberCardService
{
    // Geração de cartões
    public function generateCard(Member $member): MemberCard
    public function generateQRCode(Member $member): string
    public function generateCardPDF(MemberCard $card): string
    
    // Validação
    public function canGenerateCard(Member $member): bool
    public function validateCard(MemberCard $card): bool
    
    // Invalidação
    public function invalidateCards(Member $member, ?string $reason = null): void
    public function revokeCard(MemberCard $card, ?string $reason = null): void
}
```

#### 4.3.5 MemberReportService
```php
class MemberReportService
{
    // Relatórios
    public function generateReceiptsReport(array $filters): Collection
    public function generateInadimplencyReport(array $filters): Collection
    public function generateStatisticsReport(array $filters): array
    
    // Exportação
    public function exportToExcel(Collection $members, array $columns): string
    public function exportToPDF(Collection $members): string
    public function exportToCSV(Collection $members): string
}
```

---

## 5. IMPLEMENTAÇÃO TÉCNICA

### 5.1 Actions (Action Pattern)

#### 5.1.1 CreateMemberAction
```php
class CreateMemberAction
{
    public function execute(MemberData $memberData): Member
    {
        // Criação de membro a partir de inscrição aprovada
        // Geração de número de membro único
        // Criação de conta de utilizador (se necessário)
        // Geração de quotas iniciais
        // Criação de histórico de status
    }
}
```

#### 5.1.2 SuspendMemberAction
```php
class SuspendMemberAction
{
    public function execute(Member $member, string $reason, bool $automatic = false): void
    {
        // Mudança de status para SUSPENSO
        // Criação de histórico
        // Invalidação de cartões
        // Notificação ao membro
        // Bloqueio de acesso
    }
}
```

#### 5.1.3 CalculateQuotasAction
```php
class CalculateQuotasAction
{
    public function execute(Member $member, int $year): void
    {
        // Geração de quota anual
        // Cálculo de data de vencimento
        // Criação de registro de quota
        // Notificação de geração
    }
}
```

### 5.2 Jobs e Commands

#### 5.2.1 CheckMemberComplianceJob
```php
class CheckMemberComplianceJob implements ShouldQueue
{
    public function handle(): void
    {
        // Verifica conformidade de todos os membros ativos
        // Identifica membros com quotas em atraso
        // Identifica membros com documentos expirados
        // Identifica membros que precisam atualizar perfil
        // Dispara alertas apropriados
    }
}
```

#### 5.2.2 SuspendMembersForQuotasCommand
```php
class SuspendMembersForQuotasCommand extends Command
{
    protected $signature = 'members:suspend-for-quotas';
    
    public function handle(): void
    {
        // Identifica membros IRREGULARES há X dias
        // Suspende automaticamente
        // Notifica membros
        // Gera relatório
    }
}
```

#### 5.2.3 SendMemberAlertsCommand
```php
class SendMemberAlertsCommand extends Command
{
    protected $signature = 'members:send-alerts';
    
    public function handle(): void
    {
        // Envia alertas de quotas
        // Envia alertas de documentos
        // Envia alertas de conformidade
        // Logs de entrega
    }
}
```

### 5.3 Portal do Membro (Auto-Serviço)

#### 5.3.1 Dashboard do Membro
- Resumo de status (ativo, quotas, documentos)
- Gráficos de histórico de pagamentos
- Alertas e notificações importantes
- Ações rápidas (pagar quota, atualizar perfil, baixar cartão)
- Links para seções principais

#### 5.3.2 Gestão de Perfil
- Visualização de dados pessoais e profissionais
- Edição de dados (com validação)
- Upload de documentos
- Histórico de alterações
- Atualização de foto profissional

#### 5.3.3 Gestão de Quotas
- Listagem de todas as quotas
- Status de cada quota (pendente, pago, em atraso)
- Valores devidos (quota + multa)
- Iniciação de pagamento
- Histórico de pagamentos
- Download de comprovativos

#### 5.3.4 Gestão de Cartões
- Visualização de cartão digital
- Download de PDF
- Histórico de emissões
- Solicitação de re-emissão
- Status do cartão

---

## 6. INTEGRAÇÕES ENTRE MÓDULOS

### 6.1 INT-004: Membros → Pagamentos
- Geração automática de quotas no módulo Payment
- Criação de referências de pagamento
- Confirmação de pagamento atualiza status de quota
- Reconciliação automática
- Geração de comprovativos

### 6.2 INT-005: Membros → Notificações
- Qualquer evento dispara notificação via módulo Notification
- Templates personalizáveis por tipo de evento
- Canais: Email, SMS, In-app
- Logs de entrega e retry automático

### 6.3 INT-006: Membros → Cartões
- Geração de cartões via módulo Card
- QR codes gerados e validados
- Invalidação sincronizada
- Histórico de emissões

### 6.4 INT-007: Membros → Documentos
- Upload de documentos via módulo Document
- Validação de documentos
- Alertas de expiração
- Histórico de documentos

### 6.5 INT-003: Inscrição → Membros
- Inscrição efetiva aprovada cria registro no módulo Member
- Dados migrados automaticamente
- Número de membro gerado
- Conta de utilizador criada (se necessário)

---

## 7. CRONOGRAMA DE DESENVOLVIMENTO

### 7.1 Fase 1: Estrutura Base e Modelos (Semana 1-2)
- [ ] Expandir modelo `Member` com novos campos e métodos
- [ ] Expandir modelo `MemberQuota` com cálculo de multas
- [ ] Criar modelo `MemberStatusHistory` para rastreabilidade
- [ ] Criar modelo `MemberCard` para gestão de cartões
- [ ] Migrações para novos campos e tabelas
- [ ] Seeders para dados de teste
- [ ] Configuração do módulo (config/members.php)

### 7.2 Fase 2: Gestão de Quotas Avançada (Semana 3-4)
- [ ] Service `MemberQuotaService` completo
- [ ] Cálculo automático de multas (0,5 sobre valor em atraso)
- [ ] Geração automática de quotas anuais
- [ ] Identificação automática de quotas em atraso
- [ ] Mudança automática para IRREGULAR após 90 dias
- [ ] Suspensão automática após X dias configuráveis
- [ ] Command `SuspendMembersForQuotasCommand`
- [ ] Command `GenerateQuotaPaymentsCommand` (expandido)

### 7.3 Fase 3: Sistema de Alertas (Semana 5)
- [ ] Service `MemberAlertService` completo
- [ ] Alertas de quotas (30 dias antes, vencimento, 30/60/90 após)
- [ ] Alertas de documentos (60 dias antes, expirados)
- [ ] Alertas de mudanças de estado
- [ ] Alertas de conformidade (atualização cadastral)
- [ ] Command `SendMemberAlertsCommand`
- [ ] Job `CheckMemberComplianceJob`
- [ ] Integração com módulo Notification

### 7.4 Fase 4: Cartões Profissionais (Semana 6)
- [ ] Service `MemberCardService` completo
- [ ] Geração de cartão digital (PDF + web)
- [ ] Geração de QR code único
- [ ] Verificação pública de cartões
- [ ] Histórico de emissões
- [ ] Invalidação automática
- [ ] Integração com módulo Card
- [ ] Action `GenerateMemberCardAction`

### 7.5 Fase 5: Portal do Membro (Semana 7)
- [ ] Dashboard do membro completo
- [ ] Gestão de perfil (visualização e edição)
- [ ] Gestão de quotas (listagem, pagamento, histórico)
- [ ] Gestão de cartões (visualização, download)
- [ ] Gestão de documentos (upload, status)
- [ ] Notificações do membro
- [ ] Componentes Livewire (opcional)

### 7.6 Fase 6: Gestão Administrativa (Semana 8)
- [ ] Listagem administrativa com filtros avançados
- [ ] Detalhe de membro completo (tabs: dados, quotas, documentos, histórico)
- [ ] Gestão de status com histórico
- [ ] Gestão de quotas administrativa
- [ ] Relatórios administrativos (dashboard, análises)
- [ ] Exportações avançadas (Excel, PDF, CSV)
- [ ] Sistema de busca avançada

### 7.7 Fase 7: Relatórios e Finalização (Semana 9-10)
- [ ] Service `MemberReportService` completo
- [ ] Relatório de recebimentos
- [ ] Relatório de inadimplência
- [ ] Relatório estatístico
- [ ] Gráficos e visualizações
- [ ] Exportações (Excel, PDF, CSV)
- [ ] Integrações finais com módulos
- [ ] Suite de testes completa (≥80% cobertura)
- [ ] Documentação atualizada
- [ ] Otimizações de performance

---

## 8. TESTES

### 8.1 Testes Unitários
- Testes para todos os Services
- Testes para todas as Actions
- Testes para modelos e relacionamentos
- Testes para cálculos de quotas e multas
- Testes para validações
- **Cobertura alvo: ≥80%**

### 8.2 Testes de Integração
- Testes de fluxo completo de criação de membro
- Testes de suspensão automática por quotas
- Testes de geração de cartões
- Testes de cálculo e geração de quotas
- Testes de alertas e notificações
- Testes de integração com módulos

### 8.3 Testes de Interface
- Testes do dashboard do membro
- Testes do portal administrativo
- Testes de responsividade
- Testes de usabilidade
- Testes de acessibilidade (WCAG 2.1)

### 8.4 Testes de Performance
- Testes de carga na listagem de membros
- Testes de geração de relatórios
- Testes de exportações grandes
- Otimização de queries N+1

---

## 9. CRITÉRIOS DE ACEITAÇÃO

### 9.1 Critérios Funcionais
- [ ] **Registro completo de membros** funcional (todas as categorias de informação)
- [ ] **5 estados implementados** (ATIVO, SUSPENSO, INATIVO, IRREGULAR, CANCELADO)
- [ ] **Gestão de quotas automatizada** (cálculo, multas, alertas)
- [ ] **Suspensão automática** por inadimplência funcionando
- [ ] **Sistema de alertas** funcionando (documentos, quotas, conformidade)
- [ ] **Cartões digitais** com QR code funcionando
- [ ] **Portal do membro** completo e funcional
- [ ] **Relatórios** com filtros e exportação funcionais

### 9.2 Critérios de Qualidade
- [ ] Interface responsiva (desktop/tablet/mobile)
- [ ] Tempo de resposta < 2 segundos
- [ ] Disponibilidade 99%
- [ ] Segurança: encriptação, controle de acesso
- [ ] Auditoria: histórico imutável
- [ ] Código documentado, testes (cobertura ≥70%)
- [ ] Manual utilizador em português
- [ ] Treino utilizadores OrMM

### 9.3 Critérios Específicos de Quotas
- [ ] Cálculo de multas correto (0,5 sobre valor em atraso)
- [ ] Mudança automática para IRREGULAR após 90 dias
- [ ] Suspensão automática após X dias configuráveis
- [ ] Alertas enviados nos prazos corretos
- [ ] Relatórios de inadimplência funcionais

### 9.4 Critérios Específicos de Cartões
- [ ] Cartões gerados em PDF e versão web
- [ ] QR codes funcionais e verificáveis publicamente
- [ ] Histórico completo de emissões
- [ ] Invalidação automática funcionando
- [ ] Taxas corretas (300 MT inicial, 500 MT renovação)

---

## 10. CONCLUSÃO

O Módulo de Gestão de Membros é essencial para a gestão eficiente da OrMM, permitindo controle completo sobre o cadastro de médicos, quotas, documentos e conformidade. Este plano detalha a implementação completa, garantindo:

- **Conformidade total** com a Especificação Técnica v2.0
- **Gestão completa de quotas** com cálculo automático de multas
- **Sistema de alertas automáticos** para todos os eventos críticos
- **Cartões profissionais** com QR code e verificação pública
- **Portal de auto-serviço** completo para membros
- **Relatórios avançados** com filtros e exportação
- **Integração completa** com todos os módulos relacionados

A implementação seguirá as melhores práticas de desenvolvimento Laravel, utilizando Action Pattern para lógica de negócio, Services para operações complexas, e Jobs/Commands para automação. O sistema garantirá transparência, rastreabilidade e eficiência em todos os processos.

O cronograma de 10 semanas permite uma entrega estruturada e testada, com foco na qualidade, segurança e conformidade total com os requisitos contratuais.

---

**Documento elaborado em:** 27/01/2025  
**Versão:** 2.0  
**Status:** Aprovado para implementação  
**Base:** Especificação Técnica v2.0 - 20/11/2025  
**Próxima revisão:** Após conclusão da Fase 1
