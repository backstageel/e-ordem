# Resumo do Projeto e-Ordem
## Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.0  
**Data:** 2025-01-27  
**Propósito:** Documento de referência para estudo de reestruturação do projeto

---

## 📋 Índice

1. [Visão Geral do Projeto](#visão-geral-do-projeto)
2. [Tipos de Utilizadores](#tipos-de-utilizadores)
3. [Módulos e Funcionalidades](#módulos-e-funcionalidades)
4. [Estrutura Técnica Atual](#estrutura-técnica-atual)
5. [Arquitetura do Sistema](#arquitetura-do-sistema)
6. [Tecnologias Utilizadas](#tecnologias-utilizadas)
7. [Modelos de Dados Principais](#modelos-de-dados-principais)
8. [Fluxos de Trabalho Principais](#fluxos-de-trabalho-principais)

---

## 1. Visão Geral do Projeto

### 1.1 Objetivo
O e-Ordem é uma plataforma digital completa que moderniza e automatiza todos os processos administrativos e operacionais da OrMM, garantindo:
- Eficiência operacional
- Transparência institucional
- Rastreabilidade completa
- Conformidade regulamentar
- Digitalização de processos

### 1.2 Escopo
O sistema cobre 10 módulos funcionais principais:
1. Gestão de Inscrições
2. Submissão e Validação de Documentos
3. Gestão de Membros
4. Exames e Avaliações
5. Residência Médica
6. Pagamentos
7. Emissão de Cartões e Crachás
8. Notificações e Comunicação
9. Arquivamento e Cancelamento
10. Administração e Auditoria

---

## 2. Tipos de Utilizadores

### 2.1 Super Administrador (super-admin)
**Descrição:** Acesso total e irrestrito ao sistema. Responsável pela configuração global, gestão de usuários, roles e permissões, auditoria completa, backups e configurações críticas.

### 2.2 Administrador (admin)
**Descrição:** Gestão completa do sistema (exceto configurações críticas). Acesso a todos os módulos e funcionalidades administrativas, gestão de membros, inscrições, documentos, exames, pagamentos, residência médica, cartões, notificações e arquivos.

### 2.3 Secretariado (secretariat)
**Descrição:** Gestão de candidaturas e processos de inscrição. Responsável pela análise de processos, validação de documentos, gestão de membros e relatórios operacionais.

### 2.4 Validador Documental (validator)
**Descrição:** Validação de documentos e pareceres. Responsável pela revisão e validação de documentos submetidos, emissão de pareceres e gestão de checklists documentais.

### 2.5 Avaliador de Exames (evaluator)
**Descrição:** Gestão de exames e avaliações. Responsável pela criação de exames, gestão de candidaturas, avaliação de resultados, publicação de listas e geração de certificados.

### 2.6 Supervisor de Residência (supervisor)
**Descrição:** Supervisão de programas de residência médica. Responsável pelo acompanhamento de residentes, avaliações periódicas e gestão de programas de residência.

### 2.7 Tesouraria/Financeiro (treasury)
**Descrição:** Gestão de pagamentos e finanças. Responsável pelo processamento de pagamentos, reconciliação de transações, geração de comprovativos, relatórios financeiros e gestão de quotas.

### 2.8 Conselho/Decisor (council)
**Descrição:** Decisões finais e aprovações. Responsável pelas decisões estratégicas, aprovação/rejeição de inscrições, ativação/suspensão de membros e publicação de resultados de exames.

### 2.9 Auditor Externo (auditor)
**Descrição:** Auditoria e compliance (modo leitura). Acesso apenas para visualização de logs, relatórios e dados para fins de auditoria e conformidade.

### 2.10 Membro (member)
**Descrição:** Médico registado com inscrição ativa.

**Funcionalidades Disponíveis:**
- Visualizar e atualizar perfil pessoal
- Submeter e acompanhar inscrições
- Upload de documentos
- Visualizar histórico de pagamentos e quotas
- Gerar e baixar cartão digital
- Candidatar-se a exames
- Visualizar notificações

### 2.11 Candidato (candidate)
**Descrição:** Profissional em processo de inscrição.

**Funcionalidades Disponíveis:**
- Submeter processo de inscrição (wizard)
- Upload de documentos obrigatórios
- Acompanhar status do processo
- Realizar pagamentos
- Receber notificações

### 2.12 Professor (teacher)
**Descrição:** Docente com acesso limitado.

**Funcionalidades Disponíveis:**
- Acesso ao dashboard específico para professores

### 2.13 Público Geral (guest)
**Descrição:** Visitantes não autenticados. Acesso apenas a informações públicas, visualização de perfil público de membros (se configurado) e consulta de status de inscrição (com número de processo).

---

## 3. Módulos e Funcionalidades

### 3.1 Módulo de Gestão de Inscrição (INS)

#### Funcionalidades Principais:
- **Inscrições Provisórias:**
  - Formação
  - Intercâmbio
  - Missões
  - Cooperação
  - Setor Público
  - Setor Privado
  
- **Inscrições Efetivas:**
  - Clínica Geral
  - Especialistas
  
- **Renovações:**
  - Renovação de inscrição provisória
  - Processo automatizado
  
- **Reinscrições:**
  - Para médicos que retornam
  - Exige novos documentos

#### Workflow de Estados:
1. **Rascunho** → Candidato pode editar
2. **Submetido** → Aguardando análise
3. **Em Análise** → Secretariado analisando
4. **Com Pendências** → Documentos ou informações faltando
5. **Aprovado** → Inscrição aprovada pelo conselho
6. **Rejeitado** → Inscrição rejeitada
7. **Arquivado** → Processo inativo (>45 dias)

#### Funcionalidades Técnicas:
- Formulários dinâmicos por tipo de inscrição
- Validação automática de campos obrigatórios
- Geração de número de processo único
- QR code de referência
- Histórico completo de alterações
- Exportação de listas (CSV/XLS/PDF)
- Notificações automáticas por email/SMS
- Wizard multi-etapas para submissão

---

### 3.2 Módulo de Submissão e Validação de Documentos (DOC)

#### Funcionalidades Principais:
- **Upload de Documentos:**
  - Formatos: PDF, JPEG, PNG
  - Limite de tamanho configurável
  - Compressão automática
  - Armazenamento seguro (privado)
  
- **Checklist Dinâmico:**
  - Documentos obrigatórios por tipo de inscrição
  - Estados por documento: Pendente, Válido, Inválido
  
- **Validação Automática:**
  - Verificação de formatos
  - Validação de tamanhos
  - Verificação de validade
  - Detecção de duplicidade
  
- **Tradução Juramentada:**
  - Suporte para documentos estrangeiros
  - Validação de traduções
  
- **Pareceres:**
  - Emissão de pareceres com templates
  - Carimbo temporal
  - Hash SHA-256 para integridade
  
- **Gestão de Pendências:**
  - Alertas para documentos expirados
  - Limite de tentativas de correção
  - Notificações automáticas

#### Funcionalidades Técnicas:
- Armazenamento seguro (Storage::disk('local'))
- Download seguro com autenticação
- Visualização de documentos
- Histórico de validações
- Exportação de checklists

---

### 3.3 Módulo de Gestão de Membros (MEM)

#### Funcionalidades Principais:
- **Cadastro Completo:**
  - Dados pessoais (nome, BI, NUIT, contacto)
  - Dados profissionais (especialidade, formação)
  - Documentos essenciais
  - Histórico profissional
  
- **Estados do Membro:**
  - Ativo
  - Suspenso
  - Inativo
  - Irregular (quotas em atraso)
  - Cancelado
  
- **Gestão de Quotas:**
  - Cálculo automático de quotas
  - Alertas de inadimplência
  - Relatórios de inadimplência
  - Suspensão automática por atraso
  
- **Cartão Digital:**
  - Emissão com QR code
  - Histórico de emissões/reemissões
  - Download e impressão
  - Validade automática
  
- **Filtros e Relatórios:**
  - Por especialidade
  - Por província
  - Por estado
  - Por nacionalidade
  - Exportação (CSV/XLS/PDF)

#### Funcionalidades Técnicas:
- Atualização cadastral obrigatória periódica
- Histórico de alterações de status
- Integração com módulo de pagamentos
- Integração com módulo de cartões
- Auditoria completa de alterações

---

### 3.4 Módulo de Exames e Avaliações (EXA)

#### Funcionalidades Principais:
- **Candidaturas:**
  - Submissão de candidaturas
  - Validação de elegibilidade
  - Estados: Pendente, Aprovada, Rejeitada
  
- **Agendamento:**
  - Calendário integrado
  - Confirmação por email/SMS
  - Gestão de vagas
  
- **Resultados:**
  - Upload de resultados
  - Decisões: Aprovado/Rejeitado
  - Listas de admitidos/excluídos
  - Geração de certificados
  
- **Recursos:**
  - Submissão de recursos
  - Processamento de recursos
  - Decisões finais
  
- **Integração:**
  - Integração com pagamentos (taxas de exame)
  - Integração com módulo de membros

#### Funcionalidades Técnicas:
- Geração de listas oficiais
- Exportação de resultados
- Notificações automáticas
- Histórico completo de exames
- Estatísticas e relatórios

---

### 3.5 Módulo de Residência Médica (RES)

#### Funcionalidades Principais:
- **Programas:**
  - Criação e gestão de programas
  - Definição de especialidades
  - Duração e requisitos
  
- **Candidaturas:**
  - Submissão de candidaturas
  - Atribuição de locais (critérios configuráveis)
  - Aprovação/rejeição
  
- **Acompanhamento:**
  - Progresso do residente
  - Relatórios periódicos
  - Avaliações por tutores
  
- **Locais de Formação:**
  - Gestão de locais
  - Atribuição de residentes
  - Capacidade e vagas
  
- **Avaliações:**
  - Avaliações periódicas
  - Relatórios de progresso
  - Histórico completo
  
- **Conclusão:**
  - Emissão de certificado final
  - Integração com módulo de exames

#### Funcionalidades Técnicas:
- Workflow de aprovação
- Histórico completo
- Relatórios e estatísticas
- Exportação de dados

---

### 3.6 Módulo de Pagamentos (PAY)

#### Funcionalidades Principais:
- **Configuração de Taxas:**
  - Taxas de inscrição
  - Taxas de tramitação
  - Quotas
  - Taxas de exames
  - Taxas de cartões
  
- **Geração de Comprovativos:**
  - PDF com QR code
  - Carimbo temporal
  - Envio automático por email
  
- **Integrações:**
  - M-Pesa (carteira móvel)
  - mKesh (carteira móvel)
  - e-Mola (carteira móvel)
  - Sistemas bancários locais
  
- **Reconciliação:**
  - Confirmação via webhooks
  - Reconciliação manual
  - Relatórios financeiros
  
- **Gestão:**
  - Histórico de pagamentos
  - Reembolsos (casos específicos)
  - Exportação de relatórios

#### Funcionalidades Técnicas:
- Webhooks para confirmação
- Validação de assinaturas (HMAC)
- Idempotência em callbacks
- Validação anti-replay
- Integração com workflow de processos

---

### 3.7 Módulo de Emissão de Cartões e Crachás (CAR)

#### Funcionalidades Principais:
- **Emissão Digital/Física:**
  - Personalizada por categoria
  - Inclusão de fotos
  - Dados essenciais
  - Grau e categoria profissional
  
- **Validade Automática:**
  - Controle de expiração
  - Alertas de renovação
  
- **Rastreamento:**
  - Histórico de reemissões
  - Bloqueios (ex.: membro irregular)
  - Ativação/Desativação
  
- **QR Code:**
  - Validação rápida
  - Acesso a informações

#### Funcionalidades Técnicas:
- Geração de QR codes
- Templates personalizáveis
- Download e impressão
- Integração com módulo de membros

---

### 3.8 Módulo de Notificações e Comunicação (NTF)

#### Funcionalidades Principais:
- **Notificações Automáticas:**
  - Templates editáveis
  - Pendências
  - Aprovações
  - Vencimentos
  - Mudanças de estado
  
- **Canais:**
  - Email
  - SMS
  - Notificações in-app
  
- **Comunicação Direta:**
  - Mensagens com anexos
  - Histórico de conversas
  - Respostas
  
- **Gestão de Consentimento:**
  - Opt-in/opt-out
  - Logs de consentimento
  - Logs de entregas

#### Funcionalidades Técnicas:
- Filas assíncronas para envios
- Retry automático
- Logs de entrega
- Estatísticas de abertura
- Templates personalizáveis

---

### 3.9 Módulo de Arquivamento e Cancelamento (ARC)

#### Funcionalidades Principais:
- **Arquivamento Automático:**
  - Processos inativos (>45 dias)
  - Notificação prévia (7 dias)
  - Reabertura com despacho formal
  
- **Cancelamento:**
  - Por falsidade
  - Por incompletude
  - Registro de motivos
  - Recursos
  
- **Histórico:**
  - Motivos de arquivamento
  - Motivos de cancelamento
  - Decisões formais

#### Funcionalidades Técnicas:
- Jobs agendados para arquivamento
- Notificações automáticas
- Histórico completo
- Exportação de dados arquivados

---

### 3.10 Módulo Administrativo e de Auditoria (ADM)

#### Funcionalidades Principais:
- **Dashboard:**
  - KPIs em tempo real
  - Gráficos interativos
  - Métricas operacionais
  - Métricas financeiras
  
- **Gestão de Usuários:**
  - CRUD completo
  - Atribuição de roles
  - Gestão de permissões
  - Histórico de atividades
  
- **Roles e Permissões:**
  - Criação de roles
  - Atribuição de permissões
  - Hierarquia de roles
  
- **Auditoria:**
  - Logs completos de ações
  - Timestamps e IPs
  - Exportação de logs
  - Estatísticas de uso
  
- **Configurações:**
  - Configurações gerais
  - Configurações de negócio
  - Configurações de segurança
  - Configurações de notificações
  
- **Backups:**
  - Backups automáticos
  - Restauração
  - Agendamento
  - Retenção configurável

#### Funcionalidades Técnicas:
- Integração com Laravel Telescope
- Integração com Laravel Auditing
- Sistema de backups (Spatie Backup)
- Exportação de relatórios
- Estatísticas avançadas

---

## 4. Estrutura Técnica Atual

### 4.1 Estrutura de Diretórios

```
app/
├── Actions/              # Ações de negócio (por módulo)
│   ├── Admin/
│   ├── Exam/
│   ├── Member/
│   └── Registration/
├── Console/Commands/     # Comandos Artisan
├── Data/                 # Data Objects (Laravel Data Package)
│   ├── Admin/
│   ├── Exam/
│   └── Registration/
├── Documents/            # Ações relacionadas a documentos
├── Enums/                # Enumeradores
├── Exports/              # Exportações (Excel, PDF)
├── Http/
│   ├── Controllers/      # Controllers organizados por role
│   │   ├── Admin/
│   │   ├── Member/
│   │   ├── Guest/
│   │   └── Teacher/
│   └── Middleware/       # Middleware customizado
├── Jobs/                 # Jobs em fila
├── Listeners/            # Event listeners
├── Livewire/             # Componentes Livewire
├── Mail/                 # Classes de email
├── Models/               # Modelos Eloquent (80+ modelos)
├── Notifications/        # Classes de notificação
├── Observers/            # Model observers
├── Providers/            # Service providers
├── Services/              # Serviços de negócio
└── View/                 # View composers

resources/
├── views/
│   ├── admin/            # Views para Admin
│   ├── member/           # Views para Member
│   ├── guest/            # Views para Guest
│   ├── components/       # Blade components
│   └── layouts/          # Layouts (app, guest)

routes/
├── admin.php             # Rotas Admin
├── member.php            # Rotas Member
├── web.php               # Rotas gerais
└── auth.php              # Rotas de autenticação
```

### 4.2 Modelos Principais (80+ modelos)

**Gestão de Inscrições:**
- `Registration` - Inscrições
- `RegistrationType` - Tipos de inscrição
- `RegistrationWorkflow` - Workflow de inscrições
- `RegistrationStatus` (Enum) - Estados de inscrição
- `ProcessChecklist` - Checklist de processos

**Gestão de Membros:**
- `Member` - Membros
- `MemberQuota` - Quotas de membros
- `MemberCard` - Cartões de membros
- `MemberStatusHistory` - Histórico de status

**Documentos:**
- `Document` - Documentos
- `DocumentType` - Tipos de documentos
- `DocumentChecklist` - Checklist de documentos
- `DocumentReview` - Revisões de documentos
- `DocumentStatus` (Enum) - Estados de documentos

**Exames:**
- `Exam` - Exames
- `ExamType` - Tipos de exames
- `ExamApplication` - Candidaturas
- `ExamResult` - Resultados
- `ExamSchedule` - Agendamentos
- `ExamAppeal` - Recursos
- `ExamDecision` - Decisões

**Residência Médica:**
- `ResidencyProgram` - Programas
- `ResidencyApplication` - Candidaturas
- `ResidencyLocation` - Locais
- `ResidencyEvaluation` - Avaliações

**Pagamentos:**
- `Payment` - Pagamentos
- `PaymentType` - Tipos de pagamento
- `PaymentMethod` - Métodos de pagamento
- `PaymentReference` - Referências
- `PaymentIntegration` - Integrações
- `PaymentStatus` (Enum) - Estados de pagamento

**Cartões:**
- `Card` - Cartões
- `CardType` - Tipos de cartões
- `QrCode` - QR codes

**Notificações:**
- `Notification` - Notificações
- `NotificationTemplate` - Templates

**Sistema:**
- `User` - Usuários
- `UserProfile` - Perfis de usuário
- `SystemConfig` - Configurações
- `SystemKpi` - KPIs
- `BackupLog` - Logs de backup

**Auditoria:**
- `ArchivedProcess` - Processos arquivados
- `CancelledProcess` - Processos cancelados
- `ProcessHistory` - Histórico de processos
- `ApplicationStatusHistory` - Histórico de status

**Dados de Referência:**
- `Province`, `District`, `Neighborhood` - Localização
- `Country`, `Continent` - Geografia
- `MedicalSpeciality` - Especialidades médicas
- `Language` - Idiomas
- `Gender`, `CivilState` - Dados pessoais
- `AcademicInstitution` - Instituições acadêmicas
- `WorkInstitution` - Instituições de trabalho

### 4.3 Enums Principais

- `RegistrationStatus` - Estados de inscrição
- `RegistrationCategory` - Categorias de inscrição
- `RegistrationTypeCode` - Códigos de tipo
- `RegistrationPriority` - Prioridades
- `DocumentStatus` - Estados de documentos
- `PaymentStatus` - Estados de pagamento
- `WorkflowStatus` - Estados de workflow
- `WorkflowStep` - Etapas de workflow

---

## 5. Arquitetura do Sistema

### 5.1 Princípios Arquiteturais

1. **Separação por Roles:**
   - Controllers e Views organizados por perfis de usuário
   - Cada role tem seu próprio namespace
   - Rotas específicas por role

2. **Lógica Unificada:**
   - Models, Services e Data objects compartilhados
   - Business logic centralizada
   - Domain rules consistentes

3. **Separação de Responsabilidades:**
   - Presentation logic específica por role
   - Business logic role-agnostic
   - Data access centralizado

### 5.2 Padrões Utilizados

- **Repository Pattern:** (implícito através de Services)
- **Action Pattern:** Ações de negócio isoladas
- **Data Transfer Objects:** Laravel Data Package
- **Observer Pattern:** Model observers
- **Event-Driven:** Eventos e listeners
- **Queue Pattern:** Jobs assíncronos

### 5.3 Camadas da Aplicação

1. **Presentation Layer:**
   - Controllers (por role)
   - Views (por role)
   - Blade Components
   - Livewire Components

2. **Application Layer:**
   - Actions
   - Services
   - Data Objects
   - Jobs

3. **Domain Layer:**
   - Models
   - Enums
   - Business Rules
   - Validations

4. **Infrastructure Layer:**
   - Database
   - Storage
   - External Integrations
   - Notifications

---

## 6. Tecnologias Utilizadas

### 6.1 Backend Core

#### Framework e Linguagem
- **Laravel 12** - Framework PHP moderno
- **PHP 8.4.14** - Linguagem de programação
- **PostgreSQL** - Banco de dados principal (relacional)
- **Redis** - Cache e sistema de filas
- **Laravel Sail** - Ambiente Docker para desenvolvimento

#### Módulos Principais do Laravel 12
- **Eloquent ORM** - Mapeamento objeto-relacional
- **Migrations** - Versionamento de banco de dados
- **Seeders & Factories** - População e testes de dados
- **Routing** - Sistema de rotas (web, API, console)
- **Middleware** - Filtros de requisições HTTP
- **Authentication** - Sistema de autenticação nativo
- **Authorization** - Gates e Policies para autorização
- **Validation** - Validação de dados
- **Events & Listeners** - Sistema de eventos
- **Queues** - Processamento assíncrono de jobs
- **Mail** - Envio de emails
- **Notifications** - Sistema de notificações
- **Storage** - Sistema de arquivos (local, S3, etc.)
- **Cache** - Sistema de cache (Redis, Memcached, etc.)
- **Sessions** - Gestão de sessões
- **Logging** - Sistema de logs
- **Artisan** - CLI do Laravel
- **Testing** - Framework de testes integrado

### 6.2 Frontend

#### Frameworks e Bibliotecas
- **Bootstrap 5** - Framework CSS responsivo
- **Livewire 4** - Componentes reativos server-side
- **Alpine.js** - JavaScript reativo minimalista
- **Vite** - Build tool moderno (substitui Laravel Mix)
- **Blade** - Template engine do Laravel
- **Laravel Folio** - File-based routing (rotas baseadas em arquivos)

#### Componentes e UI
- **hostmoz/blade-bootstrap-components** - Componentes Blade Bootstrap
- **diglactic/laravel-breadcrumbs** - Breadcrumbs de navegação
- **jantinnerezo/livewire-alert** - Alertas para Livewire
- **power-components/livewire-powergrid** - Tabelas de dados para Livewire

### 6.3 Pacotes Laravel Principais

#### Autenticação e Autorização
- **laravel/sanctum** (v4.0) - API Authentication
- **spatie/laravel-permission** (v6.16) - RBAC (Role-Based Access Control)
- **pragmarx/google2fa** (v9.0) - Autenticação de dois fatores (2FA)
- **pragmarx/google2fa-laravel** (v2.3) - Integração 2FA com Laravel

#### Auditoria e Logging
- **owen-it/laravel-auditing** (v14.0) - Auditoria de modelos Eloquent
- **laravel/telescope** (v5.7) - Debug e monitoramento
- **laravel/pail** (v1.2.2) - Visualização de logs em tempo real

#### Dados e Validação
- **spatie/laravel-data** (v4.13) - Data Transfer Objects (DTOs)
- **spatie/laravel-query-builder** (v6.3) - Query builder avançado
- **spatie/laravel-tags** (v4.10) - Sistema de tags

#### Backup e Armazenamento
- **spatie/laravel-backup** (v9.3) - Backups automáticos
- **spatie/laravel-medialibrary** (v11.17) - Gestão de mídia
- **aws/aws-sdk-php** (v3.322) - SDK AWS (S3, etc.)

#### Documentos e Exportação
- **barryvdh/laravel-dompdf** (v3.1) - Geração de PDFs
- **maatwebsite/excel** (v3.1) - Importação/Exportação Excel
- **openspout/openspout** (v4.0) - Processamento de planilhas

#### Imagens e QR Codes
- **intervention/image** (v3.11) - Processamento de imagens
- **bacon/bacon-qr-code** (v2.0) - Geração de QR codes
- **simplesoftwareio/simple-qrcode** (v4.2) - QR codes simplificado

#### Formulários e Wizards
- **spatie/laravel-livewire-wizard** (dev-main) - Wizards multi-etapas
- **lorisleiva/laravel-actions** (v2.9) - Actions pattern

#### Notificações e Mensagens
- **laracasts/flash** (v3.2) - Mensagens flash
- **laravel/notifications** - Sistema nativo de notificações

#### Utilitários
- **cknow/laravel-money** (v8.4) - Gestão de valores monetários
- **fakerphp/faker** (v1.23) - Dados fake para testes
- **dedoc/scramble** (v0.12.19) - Documentação de API

#### Desenvolvimento e Qualidade
- **laravel/pint** (v1.13) - Code formatter (PSR-12)
- **laravel/tinker** (v2.10.1) - REPL interativo
- **laravel/boost** (v1.3) - Ferramentas de desenvolvimento
- **barryvdh/laravel-debugbar** (v3.13) - Debug bar (dev)

### 6.4 Testes

#### Framework de Testes
- **pestphp/pest** (v4.1) - Framework de testes moderno
- **pestphp/pest-plugin-browser** (v4.1) - Testes de browser
- **pestphp/pest-plugin-drift** (v4.0) - Migrações de testes
- **mockery/mockery** (v1.6) - Mocking para testes
- **nunomaduro/collision** (v8.6) - Tratamento de erros em testes

### 6.5 Integrações Externas

#### Pagamentos
- **M-Pesa** - Carteira móvel (Vodacom)
- **mKesh** - Carteira móvel (Movitel)
- **e-Mola** - Carteira móvel (Mcel)
- **Sistemas Bancários Locais** - Integração via APIs RESTful

#### Comunicação
- **Twilio** - Gateway SMS e Email
- **Laravel Mail** - Sistema nativo de emails
- **Laravel Notifications** - Sistema nativo de notificações

#### Armazenamento
- **AWS S3** (ou equivalente local) - Armazenamento de documentos
- **Storage Local** - Armazenamento privado no servidor

#### IA e APIs
- **openai-php/client** (v0.17.0) - Cliente OpenAI (para chat AI)
- **symfony/http-client** (v7.0) - Cliente HTTP

### 6.6 Infraestrutura

#### Containerização e Deploy
- **Docker** - Containerização
- **Laravel Sail** - Ambiente Docker para Laravel
- **deployer/deployer** (v7.4) - Ferramenta de deploy

#### Servidores
- **Nginx** - Web server
- **PHP-FPM** - Processador PHP
- **PostgreSQL 14+** - Banco de dados
- **Redis** - Cache e filas

#### Hospedagem
- **XCloud/MCNET** - Hospedagem local (soberania de dados)
- **Servidores Próprios** - Infraestrutura própria da OrMM

### 6.7 Ferramentas de Desenvolvimento

#### Documentação e Diagramas
- **beyondcode/laravel-er-diagram-generator** (v5.0) - Gerador de diagramas ER
- **recca0120/laravel-erd** (v0.4) - Diagramas de entidade-relacionamento

#### Qualidade de Código
- **Laravel Pint** - Formatação automática (PSR-12)
- **Pest** - Testes automatizados
- **Laravel Telescope** - Debug e profiling

### 6.8 Stack Tecnológico Completo

```
┌─────────────────────────────────────────┐
│         Frontend Layer                  │
│  Bootstrap 5 + Livewire 4 + Alpine.js  │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│      Application Layer (Laravel 12)     │
│  Controllers, Services, Actions, Jobs   │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│        Domain Layer                     │
│  Models, Enums, Business Rules          │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│      Infrastructure Layer               │
│  PostgreSQL + Redis + Storage + APIs    │
└─────────────────────────────────────────┘
```

### 6.9 Versões Principais

- **Laravel:** 12.x
- **PHP:** 8.4.14
- **PostgreSQL:** 14+
- **Redis:** Latest
- **Node.js:** 18+ (para Vite)
- **Docker:** Latest
- **Nginx:** Latest

---

## 7. Modelos de Dados Principais

### 7.1 Relacionamentos Principais

```
User
├── hasMany Registration
├── hasMany Member
├── belongsToMany Role
└── hasMany Audit

Member
├── belongsTo User
├── hasMany Registration
├── hasMany MemberQuota
├── hasMany MemberCard
├── hasMany Document
├── hasMany Payment
└── hasMany ExamApplication

Registration
├── belongsTo Member
├── belongsTo RegistrationType
├── hasMany Document
├── hasMany Payment
├── hasMany ProcessHistory
└── belongsTo RegistrationStatus (Enum)

Document
├── belongsTo Registration (ou Member)
├── belongsTo DocumentType
├── hasMany DocumentReview
└── belongsTo DocumentStatus (Enum)

Exam
├── hasMany ExamApplication
├── hasMany ExamResult
├── hasMany ExamSchedule
└── hasMany ExamDecision

Payment
├── belongsTo Member (ou Registration)
├── belongsTo PaymentType
├── belongsTo PaymentMethod
└── belongsTo PaymentStatus (Enum)
```

### 7.2 Entidades Principais

| Entidade | Campos Chave | Relacionamentos |
|----------|--------------|-----------------|
| User | id, email, name, password_hash, mfa_enabled | 1..* Registration, 1..* Member, *..* Role |
| Member | id, user_id, number, specialty, status | 1 User, 1..* Registration, 1..* Quota |
| Registration | id, member_id, type_id, status, process_number | 1 Member, 1..* Document, 1..* Payment |
| Document | id, registration_id, type_id, file_path, status | 1 Registration, 1..* Review |
| Payment | id, member_id, registration_id, amount, status | 1 Member (ou Registration), 1 Type |
| Exam | id, type_id, date, status | 1..* Application, 1..* Result |
| ResidencyProgram | id, specialty, duration | 1..* Application, 1..* Location |

---

## 8. Fluxos de Trabalho Principais

### 8.1 Fluxo de Inscrição (Candidato → Membro)

1. **Candidato acessa sistema** (guest)
2. **Inicia processo de inscrição** (wizard)
3. **Seleciona categoria e tipo** de inscrição
4. **Preenche formulário** com dados pessoais/profissionais
5. **Upload de documentos** obrigatórios
6. **Realiza pagamento** (taxa de inscrição)
7. **Submete processo** → Estado: "Submetido"
8. **Secretariado analisa** → Estado: "Em Análise"
9. **Validador valida documentos** → Pareceres
10. **Conselho aprova/rejeita** → Estado: "Aprovado" ou "Rejeitado"
11. **Se aprovado:** Membro criado → Estado: "Ativo"

### 8.2 Fluxo de Validação de Documentos

1. **Documento enviado** → Estado: "Pendente"
2. **Validador acessa checklist**
3. **Validador revisa documento:**
   - Verifica formato
   - Verifica validade
   - Verifica autenticidade
4. **Validador emite parecer:**
   - Aprova → Estado: "Válido"
   - Rejeita → Estado: "Inválido" (com motivo)
5. **Candidato notificado**
6. **Se rejeitado:** Candidato pode corrigir (até X tentativas)

### 8.3 Fluxo de Pagamento

1. **Sistema gera referência** de pagamento
2. **Candidato/Membro inicia pagamento:**
   - Seleciona método (M-Pesa, mKesh, e-Mola, Banco)
   - Redirecionado para gateway
3. **Gateway processa pagamento**
4. **Webhook confirma pagamento:**
   - Valida assinatura (HMAC)
   - Verifica idempotência
   - Atualiza estado do pagamento
5. **Sistema atualiza processo:**
   - Marca pagamento como confirmado
   - Avança workflow (se aplicável)
6. **Comprovativo gerado** e enviado por email

### 8.4 Fluxo de Exame

1. **Avaliador cria exame** (tipo, data, local)
2. **Membros candidatam-se** ao exame
3. **Avaliador valida candidaturas:**
   - Verifica elegibilidade
   - Aprova/Rejeita candidatura
4. **Agendamento:**
   - Candidatos selecionam horário
   - Confirmação por email/SMS
5. **Realização do exame**
6. **Avaliador upload resultados**
7. **Sistema processa resultados:**
   - Gera listas (admitidos/excluídos)
   - Notifica candidatos
8. **Recursos (se aplicável):**
   - Candidato submete recurso
   - Conselho processa recurso

### 8.5 Fluxo de Residência Médica

1. **Admin cria programa** de residência
2. **Admin define locais** de formação
3. **Membros candidatam-se** ao programa
4. **Supervisor avalia candidaturas:**
   - Atribui locais (critérios configuráveis)
   - Aprova/Rejeita
5. **Acompanhamento:**
   - Relatórios periódicos
   - Avaliações por tutores
6. **Conclusão:**
   - Certificado gerado
   - Integração com módulo de exames

---

## 9. Considerações para Reestruturação

### 9.1 Pontos Fortes da Estrutura Atual
- ✅ Separação clara por roles (Controllers/Views)
- ✅ Lógica de negócio unificada (Models/Services)
- ✅ Uso de Enums para consistência
- ✅ Auditoria completa
- ✅ Sistema de permissões robusto

### 9.2 Áreas de Melhoria Potenciais
- 🔄 Organização de Services (podem estar muito acoplados)
- 🔄 Estrutura de Actions (pode ser mais modular)
- 🔄 Separação de concerns em Controllers
- 🔄 Organização de Data Objects
- 🔄 Estrutura de testes (Feature/Unit)

### 9.3 Sugestões de Reestruturação
1. **Domain-Driven Design (DDD):**
   - Organizar por domínios (Registration, Member, Exam, etc.)
   - Cada domínio com suas próprias camadas

2. **Modular Architecture:**
   - Separar em módulos independentes
   - Cada módulo com sua própria estrutura

3. **Clean Architecture:**
   - Camadas bem definidas
   - Dependências invertidas
   - Testabilidade melhorada

4. **Event Sourcing (opcional):**
   - Para auditoria mais robusta
   - Histórico completo de eventos

---

## 10. Métricas e Estatísticas

### 10.1 Complexidade Atual
- **Modelos:** 80+
- **Controllers:** 48+
- **Views:** 222+
- **Rotas:** 200+
- **Enums:** 8
- **Services:** 17
- **Actions:** 19+
- **Jobs:** 6
- **Notifications:** 16

### 10.2 Cobertura de Testes
- **Feature Tests:** 44
- **Unit Tests:** 10
- **Browser Tests:** 1
- **Cobertura Alvo:** ≥80%

---

## 11. Conclusão

Este documento apresenta uma visão completa do projeto e-Ordem, incluindo:
- Todos os tipos de utilizadores e suas permissões
- Todas as funcionalidades por módulo
- Estrutura técnica atual
- Arquitetura do sistema
- Tecnologias utilizadas
- Modelos de dados principais
- Fluxos de trabalho principais

Este resumo serve como base para estudos de reestruturação, permitindo:
- Identificar áreas de melhoria
- Propor novas arquiteturas
- Avaliar impacto de mudanças
- Planejar refatorações

---

**Documento criado em:** 2025-01-27  
**Última atualização:** 2025-01-27  
**Versão:** 1.0

