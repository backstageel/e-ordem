# Plano de Migração - Projeto Legacy para Novo Projeto Laravel

## 📋 Sumário Executivo

Este documento detalha o plano completo para migração gradual do projeto Laravel legacy (localizado em `/legacy`) para o novo projeto Laravel mais estruturado (raiz do projeto). A migração será realizada de forma incremental, garantindo que o sistema continue funcional durante todo o processo.

**Data de Criação:** 2025-01-XX  
**Versão:** 1.0  
**Status:** Planejamento

---

## 🎯 Objetivos da Migração

1. **Modernizar a estrutura** do projeto seguindo as melhores práticas do Laravel 12
2. **Melhorar a organização** do código e facilitar manutenção futura
3. **Manter compatibilidade** durante a migração (sistema funcional)
4. **Migrar gradualmente** sem interrupção dos serviços
5. **Documentar** todo o processo para referência futura

---

## 📊 Análise Comparativa

### Projeto Legacy (Fonte)

**Estrutura:**
- Laravel 12 com arquitetura modular (`nwidart/laravel-modules`)
- 10 módulos principais: Archive, Card, Dashboard, Document, Exam, Member, Notification, Payment, Registration, Residency
- 76+ Models Eloquent
- 53 migrations
- 28 seeders
- 39 factories
- Estrutura organizada por roles (Admin, Member, Secretariat, Teacher, Guest)
- Helpers customizados (`app/helpers.php`)
- Sistema de permissões (Spatie Permission)
- MFA, auditoria, backups integrados

**Packages Composer Principais:**
- `nwidart/laravel-modules` - Sistema modular
- `livewire/livewire` - Componentes reativos
- `spatie/laravel-permission` - Permissões e roles
- `spatie/laravel-data` - Data Transfer Objects
- `spatie/laravel-backup` - Backups
- `spatie/laravel-medialibrary` - Gestão de media
- `owen-it/laravel-auditing` - Auditoria
- `maatwebsite/excel` - Exportação Excel
- `barryvdh/laravel-dompdf` - Geração PDF
- `pragmarx/google2fa-laravel` - MFA
- `laravel/telescope` - Debugging
- E muitos outros...

**Configurações Especiais:**
- Múltiplos arquivos de rotas (`admin.php`, `member.php`, `auth.php`)
- Configurações de módulos (`config/modules.php`)
- Helpers customizados para roles
- Integração com múltiplos serviços externos

### Projeto Novo (Destino)

**Estrutura Atual:**
- Laravel 12 básico (fresh install)
- Estrutura padrão do Laravel
- Apenas User model básico
- 3 migrations básicas (users, cache, jobs)
- Packages mínimos do Laravel

**Estrutura Desejada:**
- Arquitetura modular mantida (`nwidart/laravel-modules`)
- Mesma estrutura de módulos
- Melhor organização de código
- Testes mais abrangentes
- Documentação completa

---

## 🗺️ Estratégia de Migração

### Fase 1: Preparação e Infraestrutura Base (Semana 1-2)

#### 1.1 Configuração do Ambiente
- [ ] Verificar compatibilidade PHP 8.2+
- [ ] Configurar Docker/Sail (já configurado)
- [ ] Configurar variáveis de ambiente (`.env`)
- [ ] Configurar banco de dados (PostgreSQL)
- [ ] Configurar Redis para cache e filas
- [ ] Configurar storage e filesystems

#### 1.2 Instalação de Packages Base
- [ ] Instalar `nwidart/laravel-modules`
- [ ] Configurar sistema de módulos
- [ ] Instalar `spatie/laravel-permission`
- [ ] Instalar `livewire/livewire`
- [ ] Instalar `spatie/laravel-data`
- [ ] Instalar outros packages essenciais (ver lista completa abaixo)

#### 1.3 Estrutura de Diretórios
- [ ] Criar estrutura de módulos (`Modules/`)
- [ ] Configurar autoloading de módulos
- [ ] Criar estrutura de helpers (`app/helpers.php`)
- [ ] Configurar namespaces

#### 1.4 Configurações Base
- [ ] Migrar configurações de `config/` (app, auth, database, etc.)
- [ ] Configurar service providers
- [ ] Configurar middleware
- [ ] Configurar rotas base

**Checklist de Packages a Instalar:**

```bash
# Core Packages
composer require nwidart/laravel-modules
composer require spatie/laravel-permission
composer require livewire/livewire
composer require spatie/laravel-data
composer require spatie/laravel-query-builder
composer require spatie/laravel-tags
composer require spatie/laravel-medialibrary
composer require spatie/laravel-backup

# Authentication & Security
composer require pragmarx/google2fa-laravel
composer require laravel/sanctum

# Utilities
composer require maatwebsite/excel
composer require openspout/openspout
composer require barryvdh/laravel-dompdf
composer require intervention/image
composer require cknow/laravel-money
composer require simplesoftwareio/simple-qrcode
composer require bacon/bacon-qr-code

# Auditing & Logging
composer require owen-it/laravel-auditing
composer require laravel/telescope

# UI & Frontend
composer require hostmoz/blade-bootstrap-components
composer require diglactic/laravel-breadcrumbs
composer require jantinnerezo/livewire-alert
composer require power-components/livewire-powergrid
composer require spatie/laravel-livewire-wizard
composer require mhmiton/laravel-modules-livewire

# Actions & Architecture
composer require lorisleiva/laravel-actions

# API & Documentation
composer require dedoc/scramble

# AWS & Storage
composer require aws/aws-sdk-php

# Development
composer require --dev laravel/boost
composer require --dev pestphp/pest
composer require --dev pestphp/pest-plugin-browser
composer require --dev pestphp/pest-plugin-drift
composer require --dev barryvdh/laravel-debugbar
composer require --dev beyondcode/laravel-er-diagram-generator
composer require --dev roave/security-advisories
```

---

### Fase 2: Migração de Infraestrutura Core (Semana 3-4)

#### 2.1 Models Base e Enums
- [ ] Migrar `app/Models/BaseModel.php`
- [ ] Migrar todos os Enums (`app/Enums/`)
  - [ ] ApplicationStatus
  - [ ] DocumentStatus
  - [ ] PaymentStatus
  - [ ] WorkflowStatus
  - [ ] Outros enums
- [ ] Migrar Models de infraestrutura:
  - [ ] User (atualizar com campos do legacy)
  - [ ] Role
  - [ ] Permission
  - [ ] SystemConfig
  - [ ] Audit (se usar owen-it/laravel-auditing)

#### 2.2 Helpers e Utilities
- [ ] Migrar `app/helpers.php` completo
- [ ] Verificar e ajustar funções helper
- [ ] Testar helpers após migração

#### 2.3 Migrations Base
- [ ] Migrar migrations de infraestrutura:
  - [ ] Genders, CivilStates, Continents, Countries, Provinces, Districts, Neighborhoods
  - [ ] IdentityDocuments, People
  - [ ] WorkInstitutions, AcademicInstitutions
  - [ ] WorkflowStates
  - [ ] UserProfiles
  - [ ] Permission tables (via Spatie)
- [ ] Executar migrations e verificar integridade

#### 2.4 Seeders Base
- [ ] Migrar seeders de dados base:
  - [ ] DatabaseSeeder principal
  - [ ] RoleSeeder
  - [ ] PermissionSeeder
  - [ ] SystemConfigSeeder
  - [ ] Outros seeders de referência

#### 2.5 Service Providers e Configurações
- [ ] Migrar AppServiceProvider customizado
- [ ] Configurar providers de módulos
- [ ] Configurar event listeners
- [ ] Configurar observers

---

### Fase 3: Migração de Módulos (Semana 5-12)

A migração dos módulos será feita **um por vez**, seguindo a ordem de prioridade:

#### 3.1 Módulo Dashboard (Prioridade 1)
- [ ] Copiar estrutura do módulo `legacy/Modules/Dashboard/`
- [ ] Migrar controllers
- [ ] Migrar views
- [ ] Migrar rotas
- [ ] Migrar migrations específicas
- [ ] Migrar seeders
- [ ] Testar funcionalidade completa
- [ ] Documentar mudanças

#### 3.2 Módulo Member (Prioridade 1)
- [ ] Copiar estrutura completa
- [ ] Migrar models: Member, MemberCard, MemberQuota, MemberStatusHistory, etc.
- [ ] Migrar controllers (Admin, Member, Guest)
- [ ] Migrar views
- [ ] Migrar migrations
- [ ] Migrar seeders e factories
- [ ] Migrar services e actions
- [ ] Testar CRUD completo
- [ ] Testar permissões por role

#### 3.3 Módulo Registration (Prioridade 1)
- [ ] Copiar estrutura completa (maior módulo - 173 arquivos)
- [ ] Migrar models de Registration
- [ ] Migrar Livewire components (42 arquivos)
- [ ] Migrar controllers
- [ ] Migrar views (57 arquivos)
- [ ] Migrar services
- [ ] Migrar migrations
- [ ] Migrar seeders e factories
- [ ] Testar workflow completo de inscrições
- [ ] Testar integração com outros módulos

#### 3.4 Módulo Document (Prioridade 1)
- [ ] Copiar estrutura completa
- [ ] Migrar models: Document, DocumentType, DocumentAttachment, DocumentChecklist, etc.
- [ ] Migrar controllers
- [ ] Migrar views
- [ ] Migrar services de upload e validação
- [ ] Migrar integração com storage
- [ ] Testar upload, validação e arquivamento

#### 3.5 Módulo Payment (Prioridade 1)
- [ ] Copiar estrutura completa
- [ ] Migrar models: Payment, PaymentMethod, PaymentReference, PaymentType, etc.
- [ ] Migrar controllers
- [ ] Migrar views
- [ ] Migrar integrações (M-Pesa, mKesh, e-Mola)
- [ ] Migrar webhooks
- [ ] Testar fluxo de pagamento completo
- [ ] Testar webhooks

#### 3.6 Módulo Card (Prioridade 1)
- [ ] Copiar estrutura completa
- [ ] Migrar models: Card, CardType, MemberCard, QrCode
- [ ] Migrar controllers
- [ ] Migrar views
- [ ] Migrar geração de QR codes
- [ ] Migrar geração de PDFs de cartões
- [ ] Testar geração e visualização de cartões

#### 3.7 Módulo Exam (Prioridade 2)
- [ ] Copiar estrutura completa
- [ ] Migrar models: Exam, ExamType, ExamApplication, ExamResult, ExamSchedule, etc.
- [ ] Migrar controllers
- [ ] Migrar views
- [ ] Migrar migrations
- [ ] Testar fluxo completo de exames

#### 3.8 Módulo Residency (Prioridade 2)
- [ ] Copiar estrutura completa
- [ ] Migrar models: ResidencyProgram, ResidencyApplication, ResidencyEvaluation, etc.
- [ ] Migrar controllers
- [ ] Migrar views
- [ ] Migrar migrations
- [ ] Testar gestão de residências

#### 3.9 Módulo Notification (Prioridade 2)
- [ ] Copiar estrutura completa
- [ ] Migrar models: Notification, NotificationTemplate
- [ ] Migrar notification classes
- [ ] Migrar controllers
- [ ] Migrar views
- [ ] Configurar canais (email, SMS)
- [ ] Testar envio de notificações

#### 3.10 Módulo Archive (Prioridade 2)
- [ ] Copiar estrutura completa
- [ ] Migrar models: ArchivedProcess
- [ ] Migrar controllers
- [ ] Migrar views
- [ ] Migrar lógica de arquivamento
- [ ] Testar arquivamento e recuperação

**Para cada módulo, seguir este checklist:**

```
[ ] Copiar estrutura de diretórios
[ ] Migrar Models
[ ] Migrar Controllers (por role)
[ ] Migrar Views
[ ] Migrar Routes (web.php e api.php)
[ ] Migrar Migrations
[ ] Migrar Seeders
[ ] Migrar Factories
[ ] Migrar Services
[ ] Migrar Actions
[ ] Migrar Data Objects
[ ] Migrar Enums específicos
[ ] Migrar Livewire Components (se aplicável)
[ ] Migrar Jobs
[ ] Migrar Listeners
[ ] Migrar Notifications
[ ] Migrar Policies
[ ] Migrar Middleware específico
[ ] Atualizar Service Provider do módulo
[ ] Testar funcionalidades
[ ] Verificar permissões
[ ] Documentar módulo
```

---

### Fase 4: Migração de Componentes Compartilhados (Semana 13-14)

#### 4.1 Actions
- [ ] Migrar todas as Actions de `app/Actions/`
- [ ] Organizar por domínio (Admin, Exam, Member, Registration)
- [ ] Testar cada action

#### 4.2 Services
- [ ] Migrar todos os Services de `app/Services/`
- [ ] Verificar dependências
- [ ] Testar serviços

#### 4.3 Data Objects
- [ ] Migrar todos os Data Objects de `app/Data/`
- [ ] Verificar validações
- [ ] Testar serialização

#### 4.4 Jobs
- [ ] Migrar todos os Jobs de `app/Jobs/`
- [ ] Configurar filas
- [ ] Testar execução

#### 4.5 Listeners
- [ ] Migrar todos os Listeners de `app/Listeners/`
- [ ] Verificar eventos associados
- [ ] Testar listeners

#### 4.6 Mail
- [ ] Migrar classes Mail de `app/Mail/`
- [ ] Verificar templates
- [ ] Testar envio

#### 4.7 Notifications
- [ ] Migrar todas as Notifications de `app/Notifications/`
- [ ] Verificar canais
- [ ] Testar notificações

#### 4.8 Exports
- [ ] Migrar classes de Export de `app/Exports/`
- [ ] Testar exportação Excel/PDF

#### 4.9 Observers
- [ ] Migrar Observers de `app/Observers/`
- [ ] Registrar observers
- [ ] Testar observers

---

### Fase 5: Migração de Rotas e Middleware (Semana 15)

#### 5.1 Rotas
- [ ] Migrar `routes/web.php`
- [ ] Migrar `routes/admin.php`
- [ ] Migrar `routes/member.php`
- [ ] Migrar `routes/auth.php`
- [ ] Migrar `routes/console.php`
- [ ] Verificar rotas de módulos
- [ ] Testar todas as rotas

#### 5.2 Middleware
- [ ] Migrar middleware customizado
- [ ] Configurar middleware de MFA
- [ ] Configurar middleware de roles
- [ ] Testar middleware

#### 5.3 Controllers Compartilhados
- [ ] Migrar controllers de `app/Http/Controllers/`
- [ ] Organizar por namespace (Admin, Member, Guest, Teacher)
- [ ] Verificar dependências
- [ ] Testar controllers

---

### Fase 6: Migração de Views e Assets (Semana 16-17)

#### 6.1 Layouts e Components
- [ ] Migrar layouts principais
- [ ] Migrar Blade components
- [ ] Migrar componentes Livewire globais
- [ ] Verificar dependências de assets

#### 6.2 Views por Role
- [ ] Migrar views de Admin
- [ ] Migrar views de Member
- [ ] Migrar views de Guest
- [ ] Migrar views de Teacher
- [ ] Migrar views de Secretariat
- [ ] Verificar paths e includes

#### 6.3 Assets Frontend
- [ ] Migrar configuração Vite
- [ ] Migrar assets JavaScript
- [ ] Migrar assets CSS/SCSS
- [ ] Migrar imagens e recursos estáticos
- [ ] Configurar build process
- [ ] Testar frontend completo

#### 6.4 Traduções
- [ ] Migrar arquivos de tradução (`lang/pt/`)
- [ ] Migrar `lang/pt.json`
- [ ] Verificar todas as strings traduzidas
- [ ] Testar mudança de idioma

---

### Fase 7: Migração de Configurações e Integrações (Semana 18)

#### 7.1 Configurações
- [ ] Migrar todas as configurações de `config/`
  - [ ] audit.php
  - [ ] backup.php
  - [ ] data.php
  - [ ] dompdf.php
  - [ ] exams.php
  - [ ] excel.php
  - [ ] livewire.php
  - [ ] members.php
  - [ ] mfa.php
  - [ ] modules.php
  - [ ] permission.php
  - [ ] telescope.php
  - [ ] Outras configurações

#### 7.2 Integrações Externas
- [ ] Configurar integração M-Pesa
- [ ] Configurar integração mKesh
- [ ] Configurar integração e-Mola
- [ ] Configurar AWS S3 (se usado)
- [ ] Configurar serviços de email
- [ ] Configurar serviços de SMS
- [ ] Testar todas as integrações

#### 7.3 Console Commands
- [ ] Migrar todos os commands de `app/Console/Commands/`
- [ ] Testar cada command
- [ ] Documentar uso

---

### Fase 8: Testes e Validação (Semana 19-20)

#### 8.1 Testes Unitários
- [ ] Migrar testes unitários
- [ ] Corrigir testes quebrados
- [ ] Adicionar novos testes
- [ ] Executar suite completa

#### 8.2 Testes de Feature
- [ ] Migrar testes de feature
- [ ] Testar fluxos principais
- [ ] Testar por role
- [ ] Testar integrações

#### 8.3 Testes de Integração
- [ ] Testar integração entre módulos
- [ ] Testar webhooks
- [ ] Testar filas
- [ ] Testar notificações

#### 8.4 Testes de Performance
- [ ] Verificar queries N+1
- [ ] Otimizar queries lentas
- [ ] Verificar cache
- [ ] Testar carga

#### 8.5 Testes de Segurança
- [ ] Verificar permissões
- [ ] Testar autenticação
- [ ] Testar MFA
- [ ] Verificar CSRF
- [ ] Verificar XSS
- [ ] Verificar SQL injection

---

### Fase 9: Documentação e Finalização (Semana 21-22)

#### 9.1 Documentação Técnica
- [ ] Documentar arquitetura
- [ ] Documentar módulos
- [ ] Documentar APIs
- [ ] Documentar integrações
- [ ] Criar diagramas

#### 9.2 Documentação de Usuário
- [ ] Atualizar manuais
- [ ] Criar guias de uso
- [ ] Documentar workflows

#### 9.3 Limpeza
- [ ] Remover código não utilizado
- [ ] Limpar comentários desnecessários
- [ ] Organizar imports
- [ ] Aplicar code style (Pint)

#### 9.4 Preparação para Deploy
- [ ] Configurar ambiente de produção
- [ ] Preparar scripts de deploy
- [ ] Configurar backups
- [ ] Preparar rollback plan

---

## 📝 Checklist de Migração por Categoria

### Models (76+ models)

**Infraestrutura:**
- [ ] BaseModel
- [ ] User
- [ ] UserProfile
- [ ] Role
- [ ] Permission
- [ ] SystemConfig
- [ ] SystemKpi
- [ ] WorkflowState
- [ ] Message
- [ ] IntegrationLog
- [ ] BackupLog

**Geográficos:**
- [ ] Continent
- [ ] Country
- [ ] Province
- [ ] District
- [ ] Neighborhood

**Pessoas:**
- [ ] Person
- [ ] Gender
- [ ] CivilState
- [ ] IdentityDocument
- [ ] Language
- [ ] MemberLanguage

**Instituições:**
- [ ] WorkInstitution
- [ ] AcademicInstitution
- [ ] WorkExperience
- [ ] PreviousWorkExperience
- [ ] AcademicQualification
- [ ] LiteraryQualification
- [ ] ProfessionalQualification
- [ ] ProfessionalEvolution
- [ ] ProfessionalReference

**Membros:**
- [ ] Member
- [ ] MemberCard
- [ ] MemberQuota
- [ ] MemberStatusHistory
- [ ] MemberDocument
- [ ] MemberChild
- [ ] MedicalSpeciality
- [ ] Specialization

**Inscrições:**
- [ ] ApplicationStatus
- [ ] ApplicationStatusHistory
- [ ] ProcessChecklist
- [ ] ProcessHistory
- [ ] CancelledProcess
- [ ] CancellationReason
- [ ] ArchivedProcess

**Documentos:**
- [ ] Document
- [ ] DocumentType
- [ ] DocumentAttachment
- [ ] DocumentChecklist
- [ ] DocumentChecklistItem
- [ ] DocumentReview

**Exames:**
- [ ] Exam
- [ ] ExamType
- [ ] ExamApplication
- [ ] ExamResult
- [ ] ExamSchedule
- [ ] ExamDecision
- [ ] ExamAppeal

**Residência:**
- [ ] ResidencyProgram
- [ ] ResidencyApplication
- [ ] ResidencyEvaluation
- [ ] ResidencyLocation
- [ ] ResidencyProgramLocation

**Pagamentos:**
- [ ] Payment
- [ ] PaymentType
- [ ] PaymentMethod
- [ ] PaymentReference
- [ ] PaymentIntegration
- [ ] PaymentIntegrationLog

**Cartões:**
- [ ] Card
- [ ] CardType
- [ ] QrCode

**Notificações:**
- [ ] Notification
- [ ] NotificationTemplate

**Outros:**
- [ ] Homologation

### Migrations (53 migrations)

**Ordem de Migração:**
1. [ ] Tabelas base do Laravel (users, cache, jobs)
2. [ ] Tabelas geográficas (continents, countries, provinces, districts, neighborhoods)
3. [ ] Tabelas de pessoas (genders, civil_states, identity_documents, people)
4. [ ] Tabelas de instituições (work_institutions, academic_institutions)
5. [ ] Tabelas de qualificações (work_experiences, academic_qualifications)
6. [ ] Tabelas de workflow (workflow_states, user_profiles)
7. [ ] Tabelas de tipos (document_types, exam_types)
8. [ ] Tabelas principais (members, exams, documents)
9. [ ] Tabelas de relacionamento (member_languages, medical_speciality_member)
10. [ ] Tabelas de processos (application_statuses, application_status_history)
11. [ ] Tabelas de permissões (permission_tables)
12. [ ] Tabelas de exames (exam_applications, exam_results)
13. [ ] Tabelas de notificações (notification_templates, notifications, messages)
14. [ ] Tabelas de sistema (system_configs, audits)
15. [ ] Tabelas de documentos (document_reviews)
16. [ ] Tabelas de especialidades (medical_specialities, member_quotas, member_status_histories)
17. [ ] Tabelas de exames avançadas (exam_schedules, exam_decisions, exam_appeals)

### Seeders (28 seeders)

- [ ] DatabaseSeeder (principal)
- [ ] RoleSeeder
- [ ] PermissionSeeder
- [ ] UserSeeder
- [ ] GenderSeeder
- [ ] CivilStateSeeder
- [ ] ContinentSeeder
- [ ] CountrySeeder
- [ ] ProvinceSeeder
- [ ] DistrictSeeder
- [ ] NeighborhoodSeeder
- [ ] IdentityDocumentSeeder
- [ ] WorkInstitutionSeeder
- [ ] AcademicInstitutionSeeder
- [ ] DocumentTypeSeeder
- [ ] ExamTypeSeeder
- [ ] SystemConfigSeeder
- [ ] Outros seeders específicos

### Factories (39 factories)

- [ ] UserFactory
- [ ] MemberFactory
- [ ] PersonFactory
- [ ] DocumentFactory
- [ ] PaymentFactory
- [ ] ExamFactory
- [ ] Outras factories

---

## 🔧 Comandos Úteis Durante a Migração

### Composer
```bash
# Instalar packages
composer require package/name

# Atualizar autoload
composer dump-autoload

# Verificar dependências
composer check-platform-reqs
```

### Artisan
```bash
# Criar módulo
php artisan module:make ModuleName

# Publicar migrations de módulo
php artisan module:publish-migration ModuleName

# Publicar configurações
php artisan vendor:publish --tag=config

# Limpar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Executar migrations
php artisan migrate
php artisan migrate:status

# Executar seeders
php artisan db:seed
php artisan db:seed --class=SpecificSeeder

# Criar factories
php artisan make:factory ModelFactory
```

### Módulos
```bash
# Listar módulos
php artisan module:list

# Ativar módulo
php artisan module:enable ModuleName

# Desativar módulo
php artisan module:disable ModuleName

# Publicar assets de módulo
php artisan module:publish-assets ModuleName
```

### Testes
```bash
# Executar testes
php artisan test

# Executar testes específicos
php artisan test --filter=TestName

# Com coverage
php artisan test --coverage
```

---

## ⚠️ Riscos e Mitigações

### Riscos Identificados

1. **Quebra de Funcionalidades**
   - **Risco:** Funcionalidades podem quebrar durante migração
   - **Mitigação:** Testes extensivos após cada fase, ambiente de staging

2. **Perda de Dados**
   - **Risco:** Dados podem ser perdidos durante migração
   - **Mitigação:** Backups completos antes de cada fase, scripts de rollback

3. **Dependências Quebradas**
   - **Risco:** Packages podem ter incompatibilidades
   - **Mitigação:** Testar packages em ambiente isolado primeiro

4. **Performance Degradada**
   - **Risco:** Sistema pode ficar mais lento
   - **Mitigação:** Otimização de queries, uso de cache, testes de performance

5. **Tempo de Migração**
   - **Risco:** Migração pode levar mais tempo que o esperado
   - **Mitigação:** Planejamento realista, priorização, migração incremental

### Plano de Rollback

1. **Backup Completo**
   - Backup de banco de dados
   - Backup de arquivos
   - Backup de configurações

2. **Versionamento**
   - Git tags para cada fase
   - Branches separadas por fase

3. **Documentação**
   - Documentar cada mudança
   - Manter changelog

---

## 📅 Cronograma Estimado

| Fase | Duração | Descrição |
|------|---------|-----------|
| Fase 1 | 2 semanas | Preparação e Infraestrutura Base |
| Fase 2 | 2 semanas | Migração de Infraestrutura Core |
| Fase 3 | 8 semanas | Migração de Módulos (1 por semana) |
| Fase 4 | 2 semanas | Componentes Compartilhados |
| Fase 5 | 1 semana | Rotas e Middleware |
| Fase 6 | 2 semanas | Views e Assets |
| Fase 7 | 1 semana | Configurações e Integrações |
| Fase 8 | 2 semanas | Testes e Validação |
| Fase 9 | 2 semanas | Documentação e Finalização |
| **Total** | **22 semanas** | **~5.5 meses** |

**Nota:** O cronograma pode variar dependendo da complexidade encontrada e da disponibilidade da equipe.

---

## ✅ Critérios de Aceitação

A migração será considerada completa quando:

1. ✅ Todos os módulos foram migrados e testados
2. ✅ Todas as funcionalidades do legacy estão funcionando
3. ✅ Todos os testes estão passando
4. ✅ Performance é igual ou melhor que o legacy
5. ✅ Documentação está completa
6. ✅ Código segue os padrões definidos
7. ✅ Sistema está pronto para produção

---

## 📚 Referências e Recursos

### Documentação
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Laravel Modules Documentation](https://nwidart.com/laravel-modules/)
- [Spatie Packages](https://spatie.be/docs)
- [Livewire Documentation](https://livewire.laravel.com)

### Arquivos de Referência no Legacy
- `legacy/docs/` - Documentação do projeto
- `legacy/.ai/guidelines/` - Guidelines do projeto
- `legacy/README.md` - README principal

---

## 📝 Notas Finais

- Este plano deve ser atualizado conforme a migração progride
- Cada fase deve ser revisada antes de avançar para a próxima
- Comunicação constante entre a equipe é essencial
- Testes devem ser executados após cada mudança significativa
- Backups devem ser feitos regularmente

---

**Última Atualização:** 2025-01-XX  
**Próxima Revisão:** Após conclusão da Fase 1

