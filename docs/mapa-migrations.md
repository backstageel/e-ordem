# Mapa de Migrations - Organização por Módulos

Este documento mapeia todas as migrations e indica para qual módulo foram movidas.

**Data de Criação:** 2025-01-XX  
**Total de Migrations:** 53

---

## 📍 Migrations que Permanecem na Raiz (Core/Infraestrutura)

Estas migrations são compartilhadas entre módulos ou fazem parte da infraestrutura base do sistema.

### Laravel Core
- `0001_01_01_000000_create_users_table.php` - Tabela de usuários (Laravel padrão)
- `0001_01_01_000001_create_cache_table.php` - Tabela de cache (Laravel padrão)
- `0001_01_01_000002_create_jobs_table.php` - Tabela de jobs (Laravel padrão)

### Autenticação e Permissões
- `2025_07_01_200046_create_permission_tables.php` - Tabelas do Spatie Permission (roles, permissions, etc.)

### Sistema e Auditoria
- `2025_07_03_152715_create_telescope_entries_table.php` - Tabela do Laravel Telescope
- `2025_10_15_210114_create_audits_table.php` - Tabela de auditoria (owen-it/laravel-auditing)
- `2025_07_07_220316_create_system_configs_table.php` - Configurações do sistema

### Perfis e Workflow
- `2025_01_15_000008_create_user_profiles_table.php` - Perfis de usuário
- `2025_01_15_000001_create_workflow_states_table.php` - Estados de workflow

### Dados Geográficos e Referência
- `2024_08_30_054252_create_genders_table.php` - Gêneros
- `2024_09_03_152733_create_civil_states_table.php` - Estados civis
- `2024_09_03_152824_create_continents_table.php` - Continentes
- `2024_09_03_153001_create_countries_table.php` - Países
- `2024_09_03_153036_create_provinces_table.php` - Províncias
- `2024_09_03_153114_create_districts_table.php` - Distritos
- `2024_09_03_160655_create_neighborhoods_table.php` - Bairros
- `2024_09_03_161749_create_identity_documents_table.php` - Tipos de documentos de identidade
- `2024_09_03_161749_create_people_table.php` - Pessoas (tabela base)

### Instituições e Qualificações
- `2024_09_03_211350_create_work_institutions_table.php` - Instituições de trabalho
- `2024_09_03_211359_create_academic_institutions_table.php` - Instituições acadêmicas
- `2024_09_03_220012_create_work_experiences_table.php` - Experiências de trabalho
- `2024_09_03_220014_create_academic_qualifications_table.php` - Qualificações acadêmicas
- `2025_07_03_000001_add_work_and_academic_to_people_table.php` - Adiciona campos de trabalho e acadêmicos à tabela people

### Especialidades e Idiomas
- `2025_07_01_000015_create_specializations_table.php` - Especializações
- `2025_07_01_000024_create_languages_table.php` - Idiomas
- `2025_11_02_144221_create_medical_specialities_table.php` - Especialidades médicas

---

## 📋 Detalhamento de Migrations Movidas

### Document → `Modules/Document/database/migrations/`
1. `2025_07_01_000000_create_document_types_table.php`
2. `2025_07_01_000005_create_documents_table.php`
3. `2025_07_01_000010_create_document_checklists_table.php`
4. `2025_10_31_183220_create_document_reviews_table.php`

### Exam → `Modules/Exam/database/migrations/`
1. `2025_07_01_000001_create_exam_types_table.php`
2. `2025_07_01_000004_create_exams_table.php`
3. `2025_07_02_060526_create_exam_applications_table.php`
4. `2025_07_02_060659_create_exam_results_table.php`
5. `2025_11_02_202459_create_exam_schedules_table.php`
6. `2025_11_02_202500_create_exam_decisions_table.php`
7. `2025_11_02_202502_create_exam_appeals_table.php`
8. `2025_11_02_202731_add_exam_schedule_id_to_exam_applications_table.php`

### Member → `Modules/Member/database/migrations/`
1. `2025_07_01_000003_create_members_table.php`
2. `2025_07_01_000025_create_member_languages_table.php`
3. `2025_11_02_144610_add_medical_speciality_id_to_members_table.php`
4. `2025_11_02_145710_create_member_quotas_table.php`
5. `2025_11_02_150020_create_member_status_histories_table.php`
6. `2025_11_04_135010_create_medical_speciality_member_table.php`
7. `2025_11_04_135013_remove_medical_speciality_id_from_members_table.php`

### Payment → `Modules/Payment/database/migrations/`
1. `2025_07_01_000008_create_payments_table.php` (cria: payments, payment_types, payment_methods)

### Card → `Modules/Card/database/migrations/`
1. `2025_07_01_000009_create_cards_table.php` (cria: cards, card_types)

### Registration → `Modules/Registration/database/migrations/`
**Movidas da raiz:**
1. `2025_07_01_000026_create_application_statuses_table.php`
2. `2025_07_01_000027_create_application_status_history_table.php`

**Já existentes no módulo:**
3. `2025_07_01_000001_create_registration_types_table.php`
4. `2025_07_01_000004_create_registrations_table.php`
5. `2025_10_19_174213_create_temporary_registrations_table.php`
6. `2025_10_21_055743_create_registration_workflows_table.php`
7. `2025_11_26_065055_create_certification_workflows_table.php`
8. `2025_11_26_065438_create_registration_fees_table.php`

### Notification → `Modules/Notification/database/migrations/`
1. `2025_07_02_193900_create_notification_templates_table.php`
2. `2025_07_02_193928_create_notifications_table.php`
3. `2025_07_02_194320_create_messages_table.php`

### Residency → `Modules/Residency/database/migrations/`
1. `2025_07_01_000007_create_medical_residency_table.php` (cria: residency_programs, residency_locations, residency_program_locations, residency_applications, residency_evaluations)

---

## 📦 Módulo: Document

**Localização:** `Modules/Document/database/migrations/`

### Migrations Movidas:
1. `2025_07_01_000000_create_document_types_table.php` - Tipos de documentos
2. `2025_07_01_000005_create_documents_table.php` - Documentos
3. `2025_07_01_000010_create_document_checklists_table.php` - Checklists de documentos
4. `2025_10_31_183220_create_document_reviews_table.php` - Revisões de documentos

**Total:** 4 migrations

---

## 📦 Módulo: Exam

**Localização:** `Modules/Exam/database/migrations/`

### Migrations Movidas:
1. `2025_07_01_000001_create_exam_types_table.php` - Tipos de exames
2. `2025_07_01_000004_create_exams_table.php` - Exames
3. `2025_07_02_060526_create_exam_applications_table.php` - Candidaturas a exames
4. `2025_07_02_060659_create_exam_results_table.php` - Resultados de exames
5. `2025_11_02_202459_create_exam_schedules_table.php` - Agendamentos de exames
6. `2025_11_02_202500_create_exam_decisions_table.php` - Decisões de exames
7. `2025_11_02_202502_create_exam_appeals_table.php` - Recursos de exames
8. `2025_11_02_202731_add_exam_schedule_id_to_exam_applications_table.php` - Adiciona exam_schedule_id a exam_applications

**Total:** 8 migrations

---

## 📦 Módulo: Member

**Localização:** `Modules/Member/database/migrations/`

### Migrations Movidas:
1. `2025_07_01_000003_create_members_table.php` - Membros
2. `2025_11_02_145710_create_member_quotas_table.php` - Quotas de membros
3. `2025_11_02_150020_create_member_status_histories_table.php` - Histórico de status de membros
4. `2025_11_02_144610_add_medical_speciality_id_to_members_table.php` - Adiciona medical_speciality_id a members
5. `2025_11_04_135010_create_medical_speciality_member_table.php` - Tabela pivot medical_speciality_member
6. `2025_11_04_135013_remove_medical_speciality_id_from_members_table.php` - Remove medical_speciality_id de members
7. `2025_07_01_000025_create_member_languages_table.php` - Idiomas de membros

**Total:** 7 migrations

---

## 📦 Módulo: Payment

**Localização:** `Modules/Payment/database/migrations/`

### Migrations Movidas:
1. `2025_07_01_000008_create_payments_table.php` - Pagamentos (inclui payment_types e payment_methods)

**Total:** 1 migration (mas cria 3 tabelas: payments, payment_types, payment_methods)

---

## 📦 Módulo: Card

**Localização:** `Modules/Card/database/migrations/`

### Migrations Movidas:
1. `2025_07_01_000009_create_cards_table.php` - Cartões (inclui card_types)

**Total:** 1 migration (mas cria 2 tabelas: cards, card_types)

---

## 📦 Módulo: Registration

**Localização:** `Modules/Registration/database/migrations/`

### Migrations Movidas (da raiz):
1. `2025_07_01_000026_create_application_statuses_table.php` - Status de inscrições
2. `2025_07_01_000027_create_application_status_history_table.php` - Histórico de status de inscrições

### Migrations Já Existentes no Módulo:
3. `2025_07_01_000001_create_registration_types_table.php` - Tipos de inscrição
4. `2025_07_01_000004_create_registrations_table.php` - Inscrições
5. `2025_10_19_174213_create_temporary_registrations_table.php` - Inscrições temporárias
6. `2025_10_21_055743_create_registration_workflows_table.php` - Workflows de inscrição
7. `2025_11_26_065055_create_certification_workflows_table.php` - Workflows de certificação
8. `2025_11_26_065438_create_registration_fees_table.php` - Taxas de inscrição

**Total:** 8 migrations (2 movidas + 6 já existentes)

---

## 📦 Módulo: Notification

**Localização:** `Modules/Notification/database/migrations/`

### Migrations Movidas:
1. `2025_07_02_193900_create_notification_templates_table.php` - Templates de notificações
2. `2025_07_02_193928_create_notifications_table.php` - Notificações
3. `2025_07_02_194320_create_messages_table.php` - Mensagens

**Total:** 3 migrations

---

## 📦 Módulo: Residency

**Localização:** `Modules/Residency/database/migrations/`

### Migrations Movidas:
1. `2025_07_01_000007_create_medical_residency_table.php` - Residência médica (inclui: residency_programs, residency_locations, residency_program_locations, residency_applications, residency_evaluations)

**Total:** 1 migration (mas cria 5 tabelas)

---

## 📊 Resumo por Módulo

| Módulo | Quantidade de Migrations | Observações |
|--------|-------------------------|-------------|
| **Core/Raiz** | 25 | Infraestrutura compartilhada |
| **Document** | 4 | - |
| **Exam** | 8 | - |
| **Member** | 7 | - |
| **Payment** | 1 | Cria 3 tabelas |
| **Card** | 1 | Cria 2 tabelas |
| **Registration** | 8 | 2 movidas + 6 já existentes no módulo |
| **Notification** | 3 | - |
| **Residency** | 1 | Cria 5 tabelas |
| **Archive** | 0 | Sem migrations específicas |
| **Dashboard** | 0 | Sem migrations específicas |
| **Total** | 53 | - |

---

## 📝 Notas Importantes

1. **Ordem de Execução:** As migrations dos módulos devem ser executadas após as migrations core, pois dependem de tabelas como `users`, `people`, `countries`, etc.

2. **Dependências:**
   - Módulos dependem de tabelas core (people, users, countries, etc.)
   - Registration depende de application_statuses
   - Member depende de people, medical_specialities
   - Payment depende de members, people
   - Card depende de members
   - Document depende de document_types, people, members
   - Exam depende de exam_types, users
   - Notification depende de notification_templates

3. **Migrations que Criam Múltiplas Tabelas:**
   - `create_payments_table.php` cria: payments, payment_types, payment_methods
   - `create_cards_table.php` cria: cards, card_types
   - `create_medical_residency_table.php` cria: residency_programs, residency_locations, residency_program_locations, residency_applications, residency_evaluations

4. **Próximos Passos:**
   - Verificar se os módulos têm migrations próprias que precisam ser integradas
   - Executar `php artisan module:publish-migration` se necessário
   - Testar ordem de execução das migrations

---

**Última Atualização:** 2025-01-XX

