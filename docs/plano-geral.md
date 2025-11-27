# Plano Geral de Desenvolvimento - e-Ordem
## Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.0  
**Data:** 2025-01-27  
**Autor:** Equipe de Desenvolvimento MillPáginas

---

Este documento compila os cronogramas e planos de testes de todos os módulos de desenvolvimento do e-Ordem.

---

## Módulo de Administração (ADM)

### CRONOGRAMA DE DESENVOLVIMENTO

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

### TESTES

### 6.1 Testes Unitários
- [x] Testes para Actions (CreateUserAction, UpdateUserAction, DeleteUserAction, CreateRoleAction, UpdateRoleAction, DeleteRoleAction)
- [x] Testes para Controllers (DashboardController, UserManagementController, SystemConfigController, AuditController, ReportController)
- [x] Testes de permissões e middleware
- [x] Cobertura parcial - continuar expandindo para atingir 80%

### 6.2 Testes de Integração
- [x] Testes de fluxos completos (AdminDashboardTest, AdminComponentsTest)
- [x] Testes de permissões e autorização (RolePermissionMiddlewareTest)
- [x] Testes de integração com banco de dados (SystemConfigAuditingTest)
- [x] Testes de APIs (básicos)
- [ ] Testes completos de APIs REST (pendente)

### 6.3 Testes de Interface
- Testes de usabilidade
- Testes de responsividade
- Testes de acessibilidade
- Testes de performance

---

## Módulo de Inscrições (INS)

### CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [x] Criação dos modelos principais (Registration, RegistrationType, RegistrationWorkflow)
- [x] Migrações e seeders para tipos de inscrição
- [x] Configuração de estados e transições (WorkflowStep/Status)
- [x] Sistema de auditoria e logs
- [x] Configuração de permissões para módulo de inscrições

### 6.2 Fase 2: Formulário Multi-Step (Semana 2)
- [x] Refatoração para Livewire Wizard (Spatie) com fluxo: Categoria → Tipo → Contactos → Dados Pessoais → Identificação & Morada → Académico & Profissional → Documentos → Revisão & Submissão
- [x] Implementação de tipos de inscrição (efetivas e provisórias) e seleção por categoria/tipo
- [x] Sistema de validação dinâmica por tipo (Efetiva Especialista exige especialidade; Provisórias exigem país de formação, anos de experiência e instituição atual)
- [x] Upload de documentos com checklist por tipo, upload individual com progresso, persistência por passo
- [x] Salvamento por passo (sem autosave) via `TemporaryRegistration` com retoma por email/telefone

### 6.3 Fase 3: Sistema de Pagamentos (Semana 3)
- [x] `registration_types.payment_type_code` mapeado por tipo e uso para determinar taxa
- [x] Criação automática de pagamento pendente na submissão (`payments` com `reference_number`, `amount`, `due_date`)
- [x] Exibição de referência/valor/instruções na página de sucesso
- [x] `payment_types` seed abrangente alinhado ao `quotas.md` (Seeder: `PaymentTypesAndMethodsSeeder`)
- [ ] Integração com carteiras (M-Pesa/mKesh/e-Mola) e webhooks
- [x] Comprovativo: geração de recibo disponível no módulo de pagamentos (área do membro)

### 6.4 Fase 4: Workflow de Aprovação (Semana 4)
- [x] Aprovação cria membro para efetivas (nº de membro e conta de utilizador quando necessário)
- [ ] Atribuição automática de revisores e decisões com histórico detalhado
- [x] Notificações automáticas por email (submissão, docs aprov./rej., pagamento validado, inscrição validada/aprovada/rejeitada)

### 6.5 Fase 5: Gestão Administrativa (Semana 5)
- [x] Exportações Excel (campos abrangentes; respeita filtros)
- [x] Exportação PDF por inscrição (detalhe)
- [x] Listagem com filtros estruturados (Query Builder) e ações
- [x] Página de detalhe com ações: validar pagamento, validar/aprovar/rejeitar inscrição, aprovar/rejeitar documentos (individual e em massa)
- [ ] Dashboard administrativo com métricas
- [ ] Gestão avançada de documentos (pareceres)
- [ ] Arquivamento automático

### 6.6 Fase 6: Renovações e Finalização (Semana 6)
- [ ] Sistema de renovações
- [ ] Sistema de reinscrições
- [ ] Suite de testes completa
- [ ] Otimizações de performance
- [ ] Documentação: manual Markdown atualizado e HTML sincronizado

### TESTES

### 7.1 Testes Unitários
- [x] Testes para Actions principais: criação, upsert (interno), validação/aprovação/rejeição, validação de pagamento
- [x] Testes para validações de formulários e regras por tipo
- [x] Testes do Wizard (RegistrationWizardTest)
- [x] Testes de fluxo completo (GuestRegistrationTest, RegistrationManagementTest)
- [x] Cobertura parcial - Wizard bem testado, continuar expandindo para atingir 100% no Wizard e ≥ 80% no restante

### 7.2 Testes de Integração
- [x] Testes de fluxos completos do Wizard (inclui retoma por email/telefone) - RegistrationWizardTest
- [x] Testes de criação com documentos e pagamento inicial - GuestRegistrationTest, RegistrationManagementTest
- [x] Testes de página de detalhe: PDF, aprovar/rejeitar documentos (individual/em massa), validar pagamento, validar/aprovar/rejeitar inscrição - RegistrationDetailActionsTest
- [x] Testes de notificações emitidas em cada ação administrativa (parcial)

### 7.3 Testes de Interface
- Testes de usabilidade do formulário multi-step
- Testes de responsividade
- Testes de acessibilidade
- Testes de performance

---

## Módulo de Documentos (DOC)

### CRONOGRAMA DE DESENVOLVIMENTO

### 9.1 Semana 1 – Estrutura e Modelo de Dados
- [x] Modelos/Migrações (`Document`, `DocumentType`, `DocumentChecklistItem`)
- [x] Seeds de `DocumentType` por categorias comuns
- [x] Serviços de storage e hash

### 9.2 Semana 2 – Upload e Checklist
- [x] `UploadForm` (Livewire) e `DocumentController@store` com validação servidor
- [x] Geração/sincronização de checklist por tipo de inscrição
- [x] Visualização e download seguro
- [x] Suporte a traduções juramentadas e vínculo ao documento original
- [x] Compressão automática de ficheiros no upload

### 9.3 Semana 3 – Validação Automática e Pareceres
- [x] `DocumentValidationService` (formato, tamanho, validade, assinatura básica)
- [x] `ValidationPanel` (Livewire) e emissão de parecer (PDF) com templates
- [x] Validação por avaliadores (fluxo, perfis e registo de decisões)
- [x] Verificação de duplicidade (hash/metadata/conteúdo)

### 9.4 Semana 4 – Expiração e Notificações
- [x] Job diário de expiração e alertas (`CheckDocumentExpiration` job - diário às 02:00)
- [x] Integração com NTF e com estados do INS (`documents_pending`)
- [x] Gestão de pendências documentais (solicitação/correção e re-submissão)
- [x] Command `SyncDocumentChecklists` para sincronização de checklists

### 9.5 Semana 5 – UI/Admin e Exportações
- [x] Index com filtros, DataTables server-side, exportações XLSX/PDF
- [x] Auditoria detalhada (activitylog) e permissões

### 9.6 Semana 6 – Testes e Endurecimento
- [x] Suite de testes (unit/feature/browser), hardening de segurança, documentação final

### TESTES

### 7.1 Testes Unitários
- [x] Testes unitários para DocumentValidationService
- [x] Testes de feature para DocumentController (Admin e Member)
- [x] Testes de expiração (DocumentExpirationJobTest)
- [x] Testes de exportação (DocumentExportTest)
- [x] Testes de alertas (DocumentAlertServiceTest)
- [x] Testes de pendências (DocumentPendenciesActionTest)
- [x] Cobertura alvo: ≥ 85% no módulo (parcialmente atingido)

### 7.2 Testes de Integração
- [x] Feature: fluxo completo de submissão→validação→integração com INS/NTF - DocumentManagementTest
- [x] Downloads com verificação de hash - DocumentControllerTest (Admin e Member)
- [x] Testes de integração com módulo de inscrições

### 7.3 Testes de Interface
- [x] Testes básicos de UI - DocumentControllerTest (Admin e Member)
- [ ] Browser (Pest v4): upload UI, validação cliente, filtros da lista, visualização embutida (pendente - pode ser expandido)

### 7.4 Testes de Segurança
- [x] Testes básicos de autorização por perfil - DocumentControllerTest
- [x] Testes de acesso a ficheiros privados - DocumentControllerTest
- [ ] Testes de URLs com assinatura temporária (pendente)

Cobertura alvo: ≥ 85% no módulo

---

## Módulo de Membros (MEM)

### CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos Expandidos (Semana 1)
- [x] Expandir modelo `Member` com novos métodos e relacionamentos
- [x] Criar modelo `MemberQuota` para histórico de quotas
- [x] Criar modelo `MemberStatusHistory` para rastreabilidade
- [x] Migrações para novas tabelas
- [x] Seeders para dados de teste
- [x] Configuração de parâmetros do módulo (config/members.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [x] `CreateMemberAction` - Criação completa de membros
- [x] `UpdateMemberAction` - Atualização com validação
- [x] `SuspendMemberAction` - Suspensão automática/manual
- [x] `ReactivateMemberAction` - Reativação de membros
- [x] `GenerateMemberCardAction` - Geração de cartões
- [x] `MemberQuotaService` - Lógica completa de quotas
- [x] `MemberComplianceService` - Verificação de conformidade
- [x] `MemberAlertService` - Sistema de alertas

### 6.3 Fase 3: Gestão de Quotas Avançada (Semana 3)
- [x] Expansão do comando `GenerateQuotaPayments`
- [x] Cálculo automático de multas e atrasos
- [x] Suspensão automática por inadimplência
- [x] Jobs para verificação periódica de quotas
- [x] Relatórios de quotas (dashboard e exportações)
- [x] Integração com módulo de pagamentos
- [x] Notificações de quotas (lembrete, atraso, suspensão)

### 6.4 Fase 4: Portal do Membro Completo (Semana 4)
- [x] Dashboard do membro expandido com métricas
- [x] Gestão completa de perfil (visualização e edição)
- [x] Módulo de quotas do membro (listagem, pagamento, histórico)
- [x] Upload e gestão de documentos
- [x] Visualização e download de cartões
- [x] Notificações do membro
- [ ] Componentes Livewire para interatividade (opcional - pode ser adicionado posteriormente se necessário)

### 6.5 Fase 5: Gestão Administrativa Avançada (Semana 5)
- [x] Listagem administrativa com filtros avançados
- [x] Detalhe de membro expandido (tabs: dados, quotas, documentos, histórico)
- [x] Gestão de status com histórico
- [x] Gestão de quotas administrativa
- [x] Relatórios administrativos (dashboard, análises)
- [x] Exportações avançadas (Excel, PDF, CSV)
- [x] Sistema de busca avançada

### 6.6 Fase 6: Alertas, Jobs e Finalização (Semana 6)
- [x] Sistema completo de alertas (quotas, documentos, conformidade)
- [x] Jobs agendados para verificação automática:
  - [x] `ProcessQuotaOverdue` (diário às 03:00)
  - [x] `ProcessQuotaReminders` (diário às 08:00)
  - [x] `ProcessAutoSuspension` (diário às 04:00)
- [x] Commands:
  - [x] `members:send-alerts` (diário às 09:00)
  - [x] `members:check-compliance` (semanal segunda-feira às 06:00)
  - [x] `members:generate-quotas` (mensal dia 1 às 01:00)
  - [x] `members:update-quota-penalties` (diário às 03:30)
  - [x] `AutoSuspendMembers` (comando de suspensão automática)
- [ ] Suite de testes completa (separar em tarefa futura)
- [x] Documentação atualizada (manual já existe)
- [ ] Otimizações de performance (separar em tarefa futura)

### TESTES

### 7.1 Testes Unitários
- [x] Testes para Actions (CreateMemberAction, UpdateMemberAction, SuspendMemberAction, ReactivateMemberAction, GenerateMemberCardAction)
- [x] Testes para Services (MemberQuotaService, MemberComplianceService, MemberAlertService)
- [x] Testes para modelos (Member, MemberQuota, MemberCard, MemberStatusHistory)
- [x] Testes para cálculos de quotas e multas (GenerateQuotaPaymentsCommandTest)
- [x] Testes de feature (MemberManagementTest, MemberActionsTest, MemberServicesTest)
- [x] Cobertura parcial - continuar expandindo para atingir ≥ 80%

### 7.2 Testes de Integração
- [x] Testes de fluxo completo de criação de membro - MemberManagementTest, MemberActionsTest
- [x] Testes de suspensão automática por quotas - GenerateQuotaPaymentsCommandTest
- [x] Testes de geração de cartões - MemberActionsTest
- [x] Testes de cálculo e geração de quotas - GenerateQuotaPaymentsCommandTest
- [x] Testes de alertas e notificações (parcial)

### 7.3 Testes de Interface
- Testes do dashboard do membro
- Testes do portal administrativo
- Testes de responsividade
- Testes de usabilidade

### 7.4 Testes de Performance
- Testes de carga na listagem de membros
- Testes de geração de relatórios
- Testes de exportações grandes
- Otimização de queries N+1

---

## Módulo de Exames e Avaliações (EXA)

### CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [x] Criar modelos `Exam`, `ExamType`, `ExamApplication`, `ExamSchedule`, `ExamResult`, `ExamDecision`, `ExamAppeal`
- [x] Migrações para todas as tabelas
- [x] Seeders para tipos de exame e dados de teste
- [x] Relacionamentos entre modelos
- [x] Configuração de parâmetros do módulo (config/exams.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [x] `CreateExamAction` - Criação completa de exames
- [x] `SubmitApplicationAction` - Submissão de candidaturas
- [x] `ExamEligibilityService` - Validação de elegibilidade
- [x] `ExamSchedulingService` - Gestão de agendamento
- [x] `ScheduleExamAction` - Agendamento de candidatos
- [x] Integração básica com módulo de pagamentos

### 6.3 Fase 3: Gestão de Resultados (Semana 3)
- [x] `UploadResultsAction` - Upload de resultados
- [x] `ProcessResultsAction` - Processamento de resultados
- [x] `ExamResultService` - Lógica de processamento
- [x] Interface administrativa para upload e revisão
- [x] Geração de listas de admitidos/excluídos
- [x] Sistema de pareceres e decisões

### 6.4 Fase 4: Portal e Interfaces Públicas (Semana 4)
- [x] Interface pública de exames disponíveis
- [x] Formulário de candidatura para candidatos
- [x] Calendário de agendamento interativo
- [x] Portal de resultados para candidatos
- [x] Sistema de recursos e submissão
- [x] Notificações automáticas (email/SMS)

### 6.5 Fase 5: Gestão Administrativa Avançada (Semana 5)
- [x] Dashboard administrativo completo
- [x] Gestão de exames (CRUD completo)
- [x] Gestão de candidaturas e aprovações
- [x] Painel de resultados e decisões
- [x] Sistema de recursos e revisões
- [x] Relatórios administrativos
- [x] Exportações (Excel, PDF, CSV)

### 6.6 Fase 6: Integrações e Finalização (Semana 6)
- [x] Integração completa com módulo de pagamentos
- [x] Jobs agendados para lembretes e notificações (`SendExamRemindersJob`, `CheckExamPaymentsJob`)
- [x] Commands para processamento em lote (`SendExamReminders`, `ProcessExamResults`)
- [x] Suite de testes completa
- [x] Documentação atualizada
- [x] Otimizações de performance

### TESTES

### 7.1 Testes Unitários
- [x] Testes para Actions (CreateExamAction, SubmitApplicationAction, ScheduleExamAction, UploadResultsAction, ProcessResultsAction)
- [x] Testes para Services (ExamEligibilityService, ExamSchedulingService, ExamResultService)
- [x] Testes para modelos e relacionamentos (Exam, ExamApplication, ExamResult, ExamSchedule)
- [x] Testes de feature (ExamManagementTest, Exam/ExamManagementTest)
- [x] Cobertura parcial - continuar expandindo para atingir ≥ 80%

### 7.2 Testes de Integração
- [x] Testes de fluxo completo de candidatura - ExamManagementTest
- [x] Testes de agendamento e confirmação - ExamManagementTest
- [x] Testes de upload e processamento de resultados - ExamManagementTest
- [x] Testes de integração com pagamentos - ExamManagementTest
- [x] Testes de notificações (parcial)

### 7.3 Testes de Interface
- Testes do formulário de candidatura
- Testes do calendário de agendamento
- Testes do painel administrativo
- Testes de responsividade

### 7.4 Testes de Performance
- Testes de carga no agendamento
- Testes de processamento de resultados em lote
- Testes de exportações grandes

---

## Módulo de Residência Médica (RES)

### CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [x] Criar modelos `ResidencyProgram`, `ResidencyApplication`, `ResidencyLocation`, `ResidencyEvaluation`, `ResidencyProgramLocation`
- [x] Migrações para todas as tabelas
- [x] Seeders para especialidades e dados de teste
- [x] Relacionamentos entre modelos
- [x] Configuração de parâmetros do módulo (config/residency.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [x] Estrutura básica de programas (ResidencyProgram model)
- [x] Estrutura básica de candidaturas (ResidencyApplication model)
- [x] Controller `ResidencyController` para gestão administrativa
- [ ] `CreateProgramAction` - Criação completa de programas (pendente)
- [ ] `SubmitApplicationAction` - Submissão de candidaturas (pendente)
- [ ] `ResidencyEligibilityService` - Validação de elegibilidade (pendente)
- [ ] `ResidencyAssignmentService` - Lógica de atribuição (pendente)
- [ ] Integração básica com módulo de exames (pendente)

### 6.3 Fase 3: Acompanhamento e Progresso (Semana 3)
- [ ] `RegisterProgressAction` - Registro de progresso
- [ ] `ResidencyProgressService` - Gestão de progresso
- [ ] Interface de registro de atividades
- [ ] Timeline de progresso
- [ ] Sistema de relatórios periódicos
- [ ] Aprovação por tutores

### 6.4 Fase 4: Avaliações e Certificação (Semana 4)
- [ ] `SubmitEvaluationAction` - Submissão de avaliações
- [ ] Sistema de tutores e atribuição
- [ ] Formulários de avaliação configuráveis
- [ ] `IssueCertificateAction` - Emissão de certificados
- [ ] Geração de certificado em PDF
- [ ] Validação de requisitos de conclusão

### 6.5 Fase 5: Portal e Interfaces Públicas (Semana 5)
- [ ] Interface pública de programas disponíveis
- [ ] Formulário de candidatura para candidatos
- [ ] Portal de acompanhamento de progresso
- [ ] Visualização de certificados
- [ ] Notificações automáticas (email/SMS)
- [ ] Dashboard administrativo completo

### 6.6 Fase 6: Relatórios, Regime Disciplinar e Finalização (Semana 6)
- [ ] `ResidencyReportService` - Relatórios e análises
- [ ] Sistema de regime disciplinar (infrações leves, moderadas, graves)
- [ ] Gestão de sanções disciplinares
- [ ] Dashboard com métricas
- [ ] Exportações (Excel, PDF, CSV)
- [ ] Jobs agendados para lembretes e verificação de elegibilidade
- [ ] Sistema de vinculação pós-formação
- [ ] Suite de testes completa
- [ ] Documentação atualizada

### TESTES

### 7.1 Testes Unitários
- Testes para todas as Actions
- Testes para Services principais
- Testes para modelos e relacionamentos
- Testes para validação de elegibilidade
- Testes para algoritmo de atribuição
- Cobertura alvo: ≥ 80%

### 7.2 Testes de Integração
- Testes de fluxo completo de candidatura
- Testes de atribuição de locais
- Testes de registro de progresso
- Testes de avaliações
- Testes de emissão de certificado
- Testes de integração com exames

### 7.3 Testes de Interface
- Testes do formulário de candidatura
- Testes do acompanhamento de progresso
- Testes do painel administrativo
- Testes de responsividade

### 7.4 Testes de Performance
- Testes de algoritmo de atribuição
- Testes de processamento em lote
- Testes de exportações grandes

---

## Módulo de Pagamentos (PAY)

### CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [x] Criar modelos `Payment`, `PaymentType`, `PaymentMethod`, `PaymentIntegration`, `PaymentReference`
- [x] Migrações para todas as tabelas
- [x] Seeders para tipos de pagamento e dados de teste (`PaymentTypesAndMethodsSeeder`)
- [x] Relacionamentos entre modelos
- [x] Configuração de parâmetros do módulo (config/payments.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [x] `CreatePaymentAction` - Criação completa de pagamentos (no módulo de exames)
- [x] `PaymentServiceInterface` - Interface comum para gateways
- [x] `PaymentServiceFactory` - Factory para criação de serviços
- [x] `PaymentReceiptService` - Geração de comprovativos (parcial)
- [x] Integração básica com gateways (M-Pesa, e-Mola, Bank Transfer)

### 6.3 Fase 3: Integrações com Gateways (Semana 3)
- [x] `MPesaService` - Integração M-Pesa (estrutura básica)
- [ ] `MkeshGatewayService` - Integração mKesh (pendente)
- [x] `EMolaService` - Integração e-Mola (estrutura básica)
- [x] `BankTransferService` - Integração bancária (estrutura básica)
- [ ] `PaymentWebhookController` - Sistema de webhooks (pendente)
- [ ] Validação de assinaturas HMAC (pendente)

### 6.4 Fase 4: Processamento e Confirmação (Semana 4)
- [x] Estrutura básica de processamento (PaymentServiceInterface, PaymentServiceFactory)
- [x] Processamento básico via services (MPesaService, EMolaService, BankTransferService)
- [ ] `ProcessPaymentAction` - Processamento de pagamentos (pendente)
- [ ] `ConfirmPaymentAction` - Confirmação via webhook (pendente)
- [ ] Sistema de webhooks robusto (pendente)
- [x] Notificações automáticas (básicas via Laravel)
- [ ] Atualização de status em tempo real (pendente)

### 6.5 Fase 5: Reconciliação e Dashboard (Semana 5)
- [ ] `PaymentReconciliationService` - Lógica de reconciliação
- [ ] `ReconcilePaymentAction` - Processamento de reconciliação
- [ ] Dashboard financeiro completo
- [ ] `PaymentReportService` - Relatórios e análises
- [ ] Exportações (Excel, PDF, CSV)
- [ ] Jobs agendados para reconciliação

### 6.6 Fase 6: Reembolsos e Finalização (Semana 6)
- [ ] `ProcessRefundAction` - Processamento de reembolsos
- [ ] Sistema completo de reembolsos
- [ ] Suite de testes completa
- [ ] Documentação atualizada
- [ ] Otimizações de performance
- [ ] Hardening de segurança

### TESTES

### 7.1 Testes Unitários
- [x] Testes básicos para Payment model (PaymentControllerTest)
- [ ] Testes para Actions (CreatePaymentAction - pendente)
- [x] Testes para Services de gateway (mocks - estrutura criada)
- [x] Testes para modelos (Payment, PaymentType, PaymentMethod, PaymentIntegration)
- [ ] Testes para geração de comprovativos (pendente)
- [ ] Cobertura parcial - expandir testes para atingir ≥ 80%

### 7.2 Testes de Integração
- Testes de fluxo completo de pagamento
- Testes de integração com gateways (sandbox)
- Testes de webhooks
- Testes de reconciliação
- Testes de reembolsos

### 7.3 Testes de Interface
- Testes do formulário de pagamento
- Testes do dashboard financeiro
- Testes do painel de reconciliação
- Testes de responsividade

### 7.4 Testes de Segurança
- Testes de validação de assinaturas
- Testes de proteção contra replay attacks
- Testes de idempotência
- Testes de autorização

### 7.5 Testes de Performance
- Testes de processamento em lote
- Testes de reconciliação
- Testes de geração de relatórios

---

## Módulo de Cartões e Crachás (CAR)

### CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [x] Expandir modelo `MemberCard` com novos campos
- [x] Criar modelos `Card`, `CardType`, `QrCode`
- [x] Migrações para todas as tabelas
- [x] Seeders para tipos de cartão e dados de teste
- [x] Relacionamentos entre modelos
- [x] Configuração de parâmetros do módulo (config/cards.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [x] `GenerateMemberCardAction` - Geração completa de cartões
- [x] Lógica de geração de cartões (no Action)
- [x] Geração de QR codes (integrado)
- [x] Validação básica de cartões (via relacionamentos)
- [x] Sistema básico de templates (via CardType)

### 6.3 Fase 3: Templates e Personalização (Semana 3)
- [ ] `CardTemplateService` - Gestão de templates
- [ ] Editor de templates
- [ ] Preview em tempo real
- [ ] Personalização por tipo e categoria
- [ ] Geração de PDFs e imagens

### 6.4 Fase 4: Validação e Perfil Público (Semana 4)
- [ ] `ValidateCardAction` - Validação via QR code
- [ ] Página pública de validação
- [ ] Perfil público do membro
- [ ] Sistema de validação em tempo real
- [ ] Histórico de validações

### 6.5 Fase 5: Bloqueio, Revogação e Crachás (Semana 5)
- [ ] `BlockCardAction` - Bloqueio de cartões
- [ ] `RevokeCardAction` - Revogação de cartões
- [ ] `GenerateBadgeAction` - Geração de crachás
- [ ] Sistema de bloqueio automático
- [ ] Jobs agendados para verificação
- [ ] Notificações automáticas

### 6.6 Fase 6: Relatórios e Finalização (Semana 6)
- [ ] `CardReportService` - Relatórios e análises
- [ ] Dashboard administrativo
- [ ] Exportações (Excel, PDF, CSV)
- [ ] Suite de testes completa
- [ ] Documentação atualizada
- [ ] Otimizações de performance

### TESTES

### 7.1 Testes Unitários
- [x] Testes para Actions (GenerateMemberCardAction - integrado em MemberActionsTest)
- [x] Testes para modelos (MemberCard, Card, CardType)
- [ ] Testes específicos para geração de cartões (pendente)
- [ ] Testes para validação via QR code (pendente)
- [x] Testes básicos de relacionamentos
- [ ] Cobertura parcial - expandir testes para atingir ≥ 80%

### 7.2 Testes de Integração
- Testes de fluxo completo de emissão
- Testes de reemissão
- Testes de bloqueio e revogação
- Testes de validação via QR code
- Testes de integração com membros

### 7.3 Testes de Interface
- Testes do gerador de cartões
- Testes do visualizador
- Testes do perfil público
- Testes de responsividade

### 7.4 Testes de Performance
- Testes de geração em lote
- Testes de validação
- Testes de renderização de templates

---

## Módulo de Notificações e Comunicação (NTF)

### CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [x] Criar modelos `Notification`, `NotificationTemplate`, `Message`
- [x] Migrações para todas as tabelas
- [x] Seeders para templates padrão e dados de teste
- [x] Relacionamentos entre modelos (básicos)
- [ ] `NotificationPreference`, `NotificationConsent` (pendente para LGPD)
- [x] Configuração de parâmetros do módulo (config/notifications.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [x] Sistema de notificações Laravel (via `Notification` class)
- [x] `GeneralNotification` - Notificação base
- [x] Notificações por email (Laravel Mail)
- [ ] `SMSNotificationService` - Notificações por SMS (pendente)
- [x] `MemberAlertService` - Alertas para membros
- [x] `DocumentAlertService` - Alertas para documentos
- [x] Sistema básico de templates (NotificationTemplate model)

### 6.3 Fase 3: Templates e Editor (Semana 3)
- [ ] `TemplateEditor` - Editor de templates
- [ ] Sistema de variáveis dinâmicas
- [ ] Preview em tempo real
- [ ] Validação de templates
- [ ] Múltiplos idiomas
- [ ] Versionamento de templates

### 6.4 Fase 4: Comunicação Direta (Semana 4)
- [ ] `MessageService` - Serviço de mensagens
- [ ] `SendDirectMessageAction` - Envio de mensagens
- [ ] Sistema de anexos
- [ ] Histórico de conversas
- [ ] Notificações de novas mensagens
- [ ] Interface de mensagens

### 6.5 Fase 5: Consentimento e Analytics (Semana 5)
- [ ] `ConsentService` - Gestão de consentimento
- [ ] Sistema de opt-in/opt-out
- [ ] `NotificationAnalyticsService` - Analytics
- [ ] Dashboard de métricas
- [ ] Relatórios de entregas
- [ ] Logs de consentimento

### 6.6 Fase 6: Integrações e Finalização (Semana 6)
- [x] Integração básica com módulos (MemberAlertService, DocumentAlertService)
- [x] Notificações automáticas funcionais (email via Laravel Mail)
- [x] Commands de teste (`SendTestMessage`, `SendTestNotification`)
- [ ] Jobs agendados para processamento em lote (pendente)
- [ ] Commands para limpeza e manutenção (pendente)
- [ ] Suite de testes completa (pendente)
- [ ] Documentação atualizada (pendente)
- [ ] Otimizações de performance (pendente)

### TESTES

### 7.1 Testes Unitários
- [x] Testes básicos de notificações (SendTestNotificationCommandTest)
- [x] Testes para modelos (Notification, NotificationTemplate, Message)
- [ ] Testes para Actions (SendNotificationAction - pendente)
- [ ] Testes para Services (NotificationService, EmailNotificationService, SMSNotificationService - pendente)
- [ ] Testes para templates e variáveis (pendente)
- [ ] Cobertura parcial - expandir testes para atingir ≥ 80%

### 7.2 Testes de Integração
- Testes de envio de notificações
- Testes de templates e variáveis
- Testes de mensagens diretas
- Testes de consentimento
- Testes de integração com módulos

### 7.3 Testes de Interface
- Testes do centro de notificações
- Testes do editor de templates
- Testes do compositor de mensagens
- Testes de responsividade

### 7.4 Testes de Performance
- Testes de envio em lote
- Testes de filas e priorização
- Testes de rate limiting

---

## Módulo de Arquivamento e Cancelamento (ARC)

### CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [x] Criar modelos `ArchivedProcess`, `CancelledProcess`, `CancellationReason`
- [x] Migrações para todas as tabelas
- [x] Seeders para dados de teste
- [x] Relacionamentos entre modelos
- [ ] `ArchiveRule`, `ProcessAppeal` (pendente)
- [x] Configuração de parâmetros do módulo (config/archive.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [x] Estrutura básica de arquivamento (ArchivedProcess model)
- [x] Estrutura básica de cancelamento (CancelledProcess, CancellationReason)
- [x] Controller `ArchiveController` para gestão administrativa
- [ ] `ArchiveProcessAction` - Arquivamento manual (pendente)
- [ ] `CancelProcessAction` - Cancelamento (pendente)
- [ ] `CancellationService` - Lógica de cancelamento (pendente)

### 6.3 Fase 3: Arquivamento Automático (Semana 3)
- [ ] `AutoArchiveService` - Lógica de arquivamento automático (pendente)
- [ ] `AutoArchiveProcessesAction` - Execução de arquivamento (pendente)
- [ ] Sistema de regras configuráveis (pendente)
- [ ] `AutoArchiveProcessesJob` - Job agendado (pendente)
- [ ] Notificações prévias (pendente)
- [ ] Relatórios de execução (pendente)
- [x] Estrutura básica de arquivamento (ArchivedProcess model e ArchiveController)

### 6.4 Fase 4: Sistema de Recursos (Semana 4)
- [ ] `AppealService` - Gestão de recursos
- [ ] `SubmitAppealAction` - Submissão de recursos
- [ ] `ProcessAppealAction` - Processamento de recursos
- [ ] Interface de submissão
- [ ] Interface de revisão
- [ ] Notificações de recursos

### 6.5 Fase 5: Restauração e Histórico (Semana 5)
- [ ] `RestoreProcessAction` - Restauração
- [ ] Sistema de histórico completo
- [ ] Interface de restauração
- [ ] Rastreabilidade avançada
- [ ] Dashboard administrativo
- [ ] Relatórios e análises

### 6.6 Fase 6: Integrações e Finalização (Semana 6)
- [ ] Integração com todos os módulos
- [ ] Efeitos colaterais de arquivamento/cancelamento
- [ ] `ArchiveReportService` - Relatórios
- [ ] Exportações (Excel, PDF, CSV)
- [ ] Suite de testes completa
- [ ] Documentação atualizada

### TESTES

### 7.1 Testes Unitários
- [x] Testes básicos de modelos (ArchivedProcess, CancelledProcess, CancellationReason)
- [x] Testes de feature (ResidenceManagementTest, ResidencyModelsTest)
- [ ] Testes para Actions (ArchiveProcessAction, CancelProcessAction - pendente)
- [ ] Testes para Services (ArchiveService, CancellationService - pendente)
- [ ] Testes para arquivamento automático (pendente)
- [ ] Testes para recursos (pendente)
- [ ] Cobertura parcial - expandir testes para atingir ≥ 80%

### 7.2 Testes de Integração
- Testes de fluxo completo de arquivamento
- Testes de cancelamento com aprovação
- Testes de recursos e revisão
- Testes de restauração
- Testes de integração com módulos

### 7.3 Testes de Interface
- Testes do formulário de cancelamento
- Testes do formulário de recurso
- Testes do painel administrativo
- Testes de responsividade

### 7.4 Testes de Performance
- Testes de arquivamento em lote
- Testes de processamento de recursos
- Testes de relatórios

