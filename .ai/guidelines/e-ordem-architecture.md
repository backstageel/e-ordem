# OFFICIAL E-ORDEM PROJECT GUIDELINES  
**MANDATORY FOR ALL DEVELOPERS & AI AGENTS (Cursor, Copilot, Laravel Boost, Claude, etc.)**  
**This is the single source of truth. Any code that violates these rules will be rejected automatically.**

---

### 1. Project Overview
e-Ordem is the official integrated digital platform of the Ordem dos Médicos de Moçambique (OrMM).  
Built with **Laravel 12 + PostgreSQL + Laravel Sail (Docker)**, it fully automates registration, document validation, member management, exams, residency programs, payments (M-Pesa/mKesh/e-Mola), professional cards, notifications, archiving and auditing.

---

### 2. Core Architecture – Modular Monolith (nwidart/laravel-modules + DDD)

```bash
sail composer require nwidart/laravel-modules
sail artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
```

**NO Core module**  
All infrastructure (User, Roles, Permissions, Auth, Audit, global Enums, Layouts, Middleware) stays in the default Laravel `app/` folder.  
All business domains are real modules inside `modules/`.

#### Official Modules (2025–2026) – NO CORE MODULE

| Module        | Folder           | Responsibility                                               | Priority |
|---------------|------------------|--------------------------------------------------------------|----------|
| Registration  | Registration     | All registration types (provisória, efetiva, renovação, reinscrição) | 1        |
| Document      | Document         | Upload, checklist, validation, pareceres, private storage   | 1        |
| Member        | Member           | Member profile, quotas, status, history                      | 1        |
| Payment       | Payment          | Fees, M-Pesa/mKesh/e-Mola webhooks, receipts                 | 1        |
| Card          | Card             | Digital & physical card + QR generation                      | 1        |
| Exam          | Exam             | Exams, applications, results, lists                          | 2        |
| Residency     | Residency        | Residency programs, assignments, periodic evaluations        | 2        |
| Notification  | Notification     | Email/SMS/in-app, templates, opt-in/out                      | 2        |
| Archive       | Archive          | Auto-archive (>45 days), cancellation                        | 3        |
| Dashboard     | Dashboard        | KPIs, reports, statistics                                    | 3        |

---

### 2.1 Module Features Specification

#### Registration Module
**Responsibility:** All registration types (provisória, efetiva, renovação, reinscrição)

**Core Features:**
- **Registration Types:**
  - Provisional (Provisória): Formação, Intercâmbio, Missões, Cooperação, Setor Público, Setor Privado
  - Effective (Efetiva): Clínica Geral, Especialistas
  - Renewal (Renovação): Automated renewal process for provisional registrations
  - Re-registration (Reinscrição): For returning doctors, requires new documents

- **Workflow States:**
  - Rascunho (Draft) → Candidato can edit
  - Submetido (Submitted) → Awaiting analysis
  - Em Análise (Under Analysis) → Secretariado analyzing
  - Com Pendências (With Pending Items) → Missing documents or information
  - Aprovado (Approved) → Approved by council
  - Rejeitado (Rejected) → Registration rejected
  - Arquivado (Archived) → Inactive process (>45 days)

- **Technical Features:**
  - Dynamic forms per registration type
  - Automatic validation of required fields (BI, NUIT, dates, contacts)
  - Unique process number generation
  - QR code reference generation
  - Complete change history
  - List exports (CSV/XLS/PDF)
  - Automatic email/SMS notifications on state changes
  - Multi-step wizard for submission

- **Business Rules:**
  - Each registration type determines required documents and fees
  - Re-registration requires all new documents (even if previously validated)
  - Candidates must resolve pending items within X configurable days
  - Eligibility validation per registration type before submission

---

#### Document Module
**Responsibility:** Upload, checklist, validation, pareceres, private storage

**Core Features:**
- **Document Upload:**
  - Formats: PDF, JPEG, PNG
  - Configurable size limits
  - Automatic compression
  - Secure private storage (`Storage::disk('local')`)

- **Dynamic Checklist:**
  - Required documents per registration type
  - Document states: Pendente (Pending), Válido (Valid), Inválido (Invalid)
  - Automatic validation of required documents per registration type

- **Automatic Validation:**
  - Format verification
  - Size validation
  - Validity date checking
  - Duplicate detection
  - Expired documents marked as invalid

- **Sworn Translation:**
  - Support for foreign documents
  - Translation validation
  - Documents in languages other than PT/EN require sworn translations

- **Pareceres (Opinions/Reports):**
  - Template-based pareceres
  - Temporal stamp
  - SHA-256 hash for integrity
  - Document review history

- **Pending Management:**
  - Alerts for expired documents
  - Configurable limit of correction attempts (X attempts per process)
  - Automatic notifications
  - Documents expiring X days before expiration trigger alerts

- **Technical Features:**
  - Secure download with authentication
  - Document preview/viewing
  - Validation history
  - Checklist exports

- **Business Rules:**
  - Expired documents are invalid
  - Documents must be revalidated before resubmission if expired
  - Maximum X attempts to correct invalid documents per process

---

#### Member Module
**Responsibility:** Member profile, quotas, status, history

**Core Features:**
- **Complete Registration:**
  - Personal data (name, BI, NUIT, contact)
  - Professional data (specialty, education)
  - Essential documents (BI, diplomas, certificates)
  - Professional history

- **Member States:**
  - Ativo (Active)
  - Suspenso (Suspended)
  - Inativo (Inactive)
  - Irregular (Quotas in arrears)
  - Cancelado (Cancelled)

- **Quota Management:**
  - Automatic quota calculation
  - Arrears alerts
  - Inadimplency reports
  - Automatic suspension for arrears (after X days)
  - Pre-suspension notification (X days before)

- **Digital Card:**
  - QR code generation
  - Issuance/reissuance history
  - Download and print
  - Automatic validity control
  - Card issuance only for Active members with regular quotas

- **Filters and Reports:**
  - By specialty
  - By province
  - By state
  - By nationality
  - Exports (CSV/XLS/PDF)

- **Technical Features:**
  - Mandatory periodic cadastral updates (every X years or after significant changes)
  - Status change history
  - Integration with Payment module
  - Integration with Card module
  - Complete audit trail of changes

- **Business Rules:**
  - Card issuance requires Active status and regular quotas (no arrears)
  - Automatic suspension for quota arrears exceeding X days
  - Mandatory cadastral data updates every X years or after significant changes

---

#### Payment Module
**Responsibility:** Fees, M-Pesa/mKesh/e-Mola webhooks, receipts

**Core Features:**
- **Fee Configuration:**
  - Registration fees
  - Processing fees (tramitação)
  - Quotas and jóias
  - Exam fees
  - Card/badge issuance fees

- **Receipt Generation:**
  - PDF with QR code
  - Temporal stamp
  - Automatic email delivery
  - Digital signature

- **Payment Integrations:**
  - M-Pesa (Vodacom mobile wallet)
  - mKesh (Movitel mobile wallet)
  - e-Mola (Mcel mobile wallet)
  - Local banking systems (RESTful APIs)

- **Reconciliation:**
  - Webhook confirmation
  - Manual reconciliation
  - Financial reports
  - Transaction history

- **Payment Management:**
  - Payment history
  - Refunds (specific cases - only for system errors)
  - Report exports
  - Payment status tracking

- **Technical Features:**
  - Webhooks for payment confirmation
  - HMAC signature validation
  - Idempotency in callbacks
  - Anti-replay validation
  - Integration with process workflows

- **Business Rules:**
  - Processes advance only after confirmed payment (via webhook or manual confirmation)
  - Refunds limited to system errors, requires financial council approval within X business days

---

#### Card Module
**Responsibility:** Digital & physical card + QR generation

**Core Features:**
- **Digital/Physical Issuance:**
  - Personalized by category
  - Photo inclusion
  - Essential data
  - Professional degree and category
  - Customizable templates

- **Automatic Validity:**
  - Expiration control
  - Renewal alerts
  - Automatic validity checking

- **Tracking:**
  - Issuance/reissuance history
  - Blocks (e.g., irregular member)
  - Activation/Deactivation
  - Status changes logged

- **QR Code:**
  - Quick validation
  - Information access
  - Secure QR generation

- **Technical Features:**
  - QR code generation libraries
  - Customizable templates
  - Download and print
  - Integration with Member module

- **Business Rules:**
  - Card issuance only for Active members with regular quotas
  - Cards blocked automatically for irregular members

---

#### Exam Module
**Responsibility:** Exams, applications, results, lists

**Core Features:**
- **Applications:**
  - Application submission
  - Eligibility validation
  - States: Pendente (Pending), Aprovada (Approved), Rejeitada (Rejected)

- **Scheduling:**
  - Integrated calendar
  - Email/SMS confirmation
  - Vacancy management
  - Time slot selection

- **Results:**
  - Result upload
  - Decisions: Aprovado (Approved)/Rejeitado (Rejected)
  - Admitted/excluded lists
  - Certificate generation

- **Appeals:**
  - Appeal submission
  - Appeal processing
  - Final decisions
  - Appeal deadline: X business days after publication

- **Integration:**
  - Payment integration (exam fees)
  - Member module integration
  - Notification integration

- **Technical Features:**
  - Official list generation
  - Result exports
  - Automatic notifications
  - Complete exam history
  - Statistics and reports

- **Business Rules:**
  - Appeals can be submitted within X business days after publication
  - Council review of appeals within X days

---

#### Residency Module
**Responsibility:** Residency programs, assignments, periodic evaluations

**Core Features:**
- **Programs:**
  - Program creation and management
  - Specialty definition
  - Duration and requirements
  - Program configuration

- **Applications:**
  - Application submission
  - Location assignment (configurable criteria)
  - Approval/rejection
  - Capacity and vacancy management

- **Monitoring:**
  - Resident progress tracking
  - Periodic reports
  - Tutor evaluations
  - Progress milestones

- **Training Locations:**
  - Location management
  - Resident assignment
  - Capacity and vacancies
  - Location-specific requirements

- **Evaluations:**
  - Periodic evaluations
  - Progress reports
  - Complete history
  - Evaluation templates

- **Completion:**
  - Final certificate issuance
  - Integration with Exam module
  - Completion requirements validation

- **Technical Features:**
  - Approval workflow
  - Complete history
  - Reports and statistics
  - Data exports

---

#### Notification Module
**Responsibility:** Email/SMS/in-app, templates, opt-in/out

**Core Features:**
- **Automatic Notifications:**
  - Editable templates
  - Pending items alerts
  - Approvals/rejections
  - Expiration warnings
  - State change notifications
  - Critical notifications sent via email + SMS simultaneously

- **Channels:**
  - Email (via Laravel Mail)
  - SMS (via Twilio or local gateway)
  - In-app notifications
  - Multi-channel support

- **Direct Communication:**
  - Messages with attachments
  - Conversation history
  - Replies
  - Thread management

- **Consent Management:**
  - Opt-in/opt-out
  - Consent logs
  - Delivery logs
  - User preferences

- **Technical Features:**
  - Asynchronous queues for sending
  - Automatic retry
  - Delivery logs
  - Open statistics
  - Customizable templates
  - Template editor

- **Business Rules:**
  - Critical notifications must be sent via email + SMS simultaneously
  - Users must opt-in during registration, can opt-out anytime
  - All notifications logged with delivery confirmation

---

#### Archive Module
**Responsibility:** Auto-archive (>45 days), cancellation

**Core Features:**
- **Automatic Archiving:**
  - Inactive processes (>45 days without action)
  - Pre-notification (7 days before)
  - Reopening requires formal council dispatch
  - Configurable archiving rules

- **Cancellation:**
  - By falsification (immediate, no appeal)
  - By incompleteness
  - Reason registration
  - Appeals (for incompleteness only)
  - Cancellation history

- **History:**
  - Archiving reasons
  - Cancellation reasons
  - Formal decisions
  - Complete audit trail

- **Technical Features:**
  - Scheduled jobs for archiving
  - Automatic notifications
  - Complete history
  - Archived data exports
  - Archive search and retrieval

- **Business Rules:**
  - Processes inactive for >X days automatically archived with 7-day pre-notification
  - Reopening requires formal council dispatch
  - Cancellation by falsification is immediate with no appeal option

---

#### Dashboard Module
**Responsibility:** KPIs, reports, statistics

**Core Features:**
- **Real-time KPIs:**
  - Operational metrics
  - Financial metrics
  - Registration statistics
  - Member statistics
  - Payment statistics
  - Exam statistics

- **Interactive Charts:**
  - Process status distribution
  - Registration trends
  - Financial trends
  - Member distribution by specialty/province
  - Approval/rejection rates

- **Reports:**
  - Operational reports
  - Financial reports
  - Strategic reports
  - Custom report builder
  - Scheduled reports

- **Statistics:**
  - Registration statistics
  - Member statistics
  - Payment statistics
  - Exam statistics
  - Residency statistics

- **Technical Features:**
  - Real-time data updates
  - Export capabilities (CSV/XLS/PDF)
  - Customizable dashboards per role
  - Data visualization libraries

---

### 3. Role-Based Access Control (spatie/laravel-permission)

| Role (exact string) | Description                                  | Typical Access |
|---------------------|----------------------------------------------|----------------|
| super-admin         | God mode                                     | Everything     |
| admin               | General administrator                        | All except critical configs |
| secretariat         | Handles registrations & documents            | Registration + Document (full) |
| validator           | Document validator only                      | Document validation only |
| evaluator           | Exam evaluator                               | Exam module    |
| supervisor          | Residency supervisor                         | Residency evaluations |
| treasury            | Finance / payments                           | Payment + quotas |
| council             | Final decision maker                         | Approvals / rejections |
| auditor             | Read-only + logs                             | All modules read-only |
| member              | Registered doctor                            | Own profile, card, quotas |
| candidate           | In registration process                      | Only own registration |
| teacher             | Tutor in residency                           | Own residents only |
| guest               | Public visitor (not authenticated)           | Public info, member public profiles, registration status lookup |

---

### 4. Exact Folder Structure per Module (MANDATORY)

```text
modules/Member/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── web.php        → all web routes organized by role/middleware
│   └── api.php        → all API routes (optional)
├── resources/
│   └── views/
│       ├── admin/         → full CRUD + advanced filters
│       ├── treasury/      → quotas & payments only
│       ├── council/       → approvals / rejections
│       ├── auditor/       → read-only tables (no forms)
│       ├── member/        → simple personal dashboard
│       ├── guest/         → public views (if module has public access)
│       └── components/    → shared Blade components (only for this module)
├── src/
│   ├── Actions/           → pure business logic (e.g. SuspendMemberAction)
│   ├── Data/              → spatie/laravel-data DTOs (MANDATORY for all input!)
│   ├── Enums/             → module-specific enums
│   ├── Events/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── Treasury/
│   │   │   ├── Council/
│   │   │   ├── Auditor/
│   │   │   └── Member/
│   │   └── Requests/      → ONLY if absolutely necessary (prefer Data classes)
│   ├── Models/            → domain models (Member.php, MemberQuota.php, etc.)
│   ├── Policies/          → ONE neutral policy per module
│   ├── Services/          → pure business logic (never check roles here)
│   └── MemberServiceProvider.php
├── tests/
└── module.json
```

---

### 5. What stays in the default Laravel `app/` folder (global)

| Functionality              | Location                                      |
|----------------------------|-----------------------------------------------|
| User model + Spatie traits | `app/Models/User.php`                         |
| User Management            | `app/Http/Controllers/Admin/UserController.php` (CRUD for users) |
| Roles & Permissions        | `app/Models/Role.php`, `Permission.php` (Spatie models) |
| Roles & Permissions UI     | `app/Http/Controllers/Admin/RoleController.php`, `PermissionController.php` |
| Global Enums               | `app/Enums/` (e.g. RegistrationStatus)        |
| Auth (Sanctum/Fortify)     | `app/` standard                               |
| Global Middleware          | `app/Http/Middleware/` (e.g. readonly)        |
| Global Layouts             | `resources/views/components/layouts/`         |
| Global Service Providers   | `app/Providers/`                              |
| System Configuration       | `app/Http/Controllers/Admin/SystemConfigController.php` |
| Audit Logs                 | `app/Http/Controllers/Admin/AuditController.php` |
| Backups                    | Managed via Spatie Backup (commands in `app/Console/Commands/`) |
| Auditing (owen-it)         | Applied on models (app or module)             |

---

### 6. NON-NEGOTIABLE RULES

| Topic                     | Rule                                                                                             |
|---------------------------|--------------------------------------------------------------------------------------------------|
| Docker / Sail             | **ALWAYS** `sail artisan`, `sail composer`, `sail pint`, `sail test`                             |
| Validation                | **ONLY** spatie/laravel-data → **NEVER** FormRequest or inline rules                             |
| Flash Messages            | **ALWAYS** `flash('Message in English', 'success|error|warning|info')`                           |
| Blade Layouts             | **ALWAYS** `<x-layouts.app>` with `<x-slot name="header">`                                        |
| Form Components           | **ALWAYS** use `hostmoz/blade-bootstrap-components` if component exists → `<x-bootstrap::form.input />`, `<x-bootstrap::form.select />`, etc. |
| Enums in Views            | **ALWAYS** `Enum::cases()` and `->label()` – never hardcode values                               |
| Array Cast Fields         | Use directly → **NO** json_decode()                                                              |
| Keywords / Tags           | Single comma-separated input → convert to array in controller                                    |
| Translations              | `lang/pt.json` only. Keys = English strings. Use `{{ __('English text') }}`                      |
| Sensitive Files           | **ALL** documents → `Storage::disk('local')` (private) + secure download route                   |
| Routes                    | Always with role middleware: `->middleware(['auth', 'role:admin|super-admin'])`                  |
| Policies                  | One neutral policy per module                                                                    |
| Auditor Mode              | Global `readonly` middleware removes POST/PUT/DELETE for role `auditor`                          |
| Notifications             | Fire events → Notification module listens and sends                                              |
| Payments                  | Webhooks must be idempotent + HMAC validation                                                    |
| Tests                     | Pest PHP only → ≥80% coverage per module                                                         |
| Migrations / Seeders      | Always inside the module                                                                         |
| Table Columns             | Maximum 7 columns in desktop tables (Código, Nome, Contacto, Data, Tipo, Status, Ações)          |
| Table Actions             | "Ver Detalhes" button outside dropdown, other actions (Editar, Rejeitar, Apagar) inside dropdown |
| Pagination                | **ALWAYS** use `<x-pagination-enhanced>` component. Includes: "Mostrando X a Y de Z registos", per-page selector (10/25/50/100), first/last buttons, preserves filters |
| Status Badges             | **ALWAYS** use `<x-status-badge>` component with icons for accessibility. Never hardcode badge colors or labels |
| Status Legend             | Use `<x-status-legend>` component to display status explanations. Add to detail views when helpful |

---

### 6.1. Form Components (hostmoz/blade-bootstrap-components) – MANDATORY

**ALWAYS use components from `hostmoz/blade-bootstrap-components` package when available.**

The package provides comprehensive Bootstrap 5 form components that handle:
- Automatic old input population
- Validation error display
- Form method spoofing (PUT, PATCH, DELETE)
- ARIA attributes for accessibility
- Tooltips/help text support
- Consistent styling

**Available Components:**
- `<x-bootstrap::form.input />` - Text inputs (text, email, number, etc.)
- `<x-bootstrap::form.select />` - Select dropdowns
- `<x-bootstrap::form.textarea />` - Textarea fields
- `<x-bootstrap::form.checkbox />` - Checkboxes
- `<x-bootstrap::form.radio />` - Radio buttons
- `<x-bootstrap::form.file />` - File uploads
- `<x-bootstrap::form.password />` - Password with toggle
- `<x-bootstrap::form.submit />` - Submit buttons
- `<x-bootstrap::form.label />` - Form labels
- `<x-bootstrap::form.group />` - Form groups
- `<x-bootstrap::form.form />` - Form wrapper
- `<x-bootstrap::form.errors />` - Error display

**Advanced Components:**
- `<x-bootstrap::form.date-picker />` - Date picker
- `<x-bootstrap::form.date-time-picker />` - DateTime picker
- `<x-bootstrap::form.select2 />` - Select2 integration
- `<x-bootstrap::form.summer-note />` - SummerNote editor
- `<x-bootstrap::form.trix-editor />` - Trix editor
- `<x-bootstrap::form.tags />` - Tag input
- `<x-bootstrap::form.auto-complete />` - Autocomplete
- `<x-bootstrap::form.dual-listbox />` - Dual listbox

**Example Usage:**
```blade
<x-bootstrap::form.form method="POST" action="{{ route('admin.members.store') }}">
    <x-bootstrap::form.input 
        name="full_name" 
        label="Nome Completo" 
        required 
        value="{{ old('full_name') }}"
        help="Insira o nome completo do membro"
    />
    
    <x-bootstrap::form.select 
        name="status" 
        label="Status"
        :options="['active' => 'Ativo', 'inactive' => 'Inativo']"
        required
        help="Selecione o status do membro"
    />
    
    <x-bootstrap::form.textarea 
        name="notes" 
        label="Notas"
        rows="5"
        help="Adicione notas adicionais sobre o membro"
    />
    
    <x-bootstrap::form.submit value="Guardar" />
</x-bootstrap::form.form>
```

**Package Customization:**
- Components are edited **directly** in `vendor/hostmoz/blade-bootstrap-components/`
- Package is under our control (VCS repository) and changes are committed to the repository
- **DO NOT publish views** - edit directly in vendor folder
- Assets are published via `sail artisan vendor:publish --tag=bootstrap-assets` (if needed)

**If a component doesn't exist in the package:**
- Create a custom component in `resources/views/components/`
- Follow the same patterns and conventions
- Consider contributing to the package if it's a common use case

---

### 7. Validation Example (spatie/laravel-data) – MANDATORY

```php
// modules/Member/Data/UpdateMemberData.php
use Spatie\LaravelData\Data;

class UpdateMemberData extends Data
{
    public function __construct(
        public string $full_name,
        public ?string $specialty = null,
        public ?string $phone = null,
    ) {}

    public static function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'specialty' => ['nullable', 'string'],
            'phone'     => ['nullable', 'regex:/^8[2-7][0-9]{7}$/'],
        ];
    }
}
```

---

### 8. Routes Structure (nwidart/laravel-modules)

#### Global Routes (in `routes/` folder)
These routes are registered globally and handle authentication and public access:

- **`routes/auth.php`** - Authentication routes (login, register, password reset, etc.)
- **`routes/web.php`** - Global web routes (home, public pages, guest access)
- **`routes/admin.php`** - Global admin routes (user management, roles, permissions, system config, audit logs, backups, dashboard)

#### Module Routes (in `modules/{Module}/routes/`)
Each module has its own route files: `web.php` (for all web routes) and `api.php` (for API routes). Routes are organized by role/middleware within these files. Routes are automatically registered by the module's ServiceProvider.

**Example structure:**
```php
// modules/Dashboard/routes/web.php
<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Modules\Dashboard\Http\Controllers\Member\DashboardController as MemberDashboardController;
use Modules\Dashboard\Http\Controllers\Secretariat\DashboardController as SecretariatDashboardController;

// Admin Dashboard routes
Route::middleware(['auth', 'verified', 'mfa.verified', 'role:admin|super-admin'])
    ->prefix('admin/dashboard')
    ->as('admin.dashboard.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
    });

// Member Dashboard routes
Route::middleware(['auth', 'verified', 'mfa.verified', 'role:member'])
    ->prefix('member/dashboard')
    ->as('member.dashboard.')
    ->group(function () {
        Route::get('/', [MemberDashboardController::class, 'index'])->name('index');
    });

// Secretariat Dashboard routes
Route::middleware(['auth', 'verified', 'mfa.verified', 'role:secretariat'])
    ->prefix('secretariat/dashboard')
    ->as('secretariat.dashboard.')
    ->group(function () {
        Route::get('/', [SecretariatDashboardController::class, 'index'])->name('index');
    });
```

**Route Registration:**
- Module routes are automatically loaded by `nwidart/laravel-modules`
- Each module's `RouteServiceProvider` registers `web.php` and `api.php` from `routes/` folder
- Route names use `->as()` method to define the prefix, NOT `->name()` with module prefix

**Route Naming Convention:**
- **ALWAYS use `->as()` method** to define route name prefix: `->as('{role}.{resource}.')`
- Route names follow pattern: `{role}.{resource}.{action}` (e.g., `admin.dashboard.index`, `member.dashboard.index`)
- **DO NOT use module prefix** in route names (e.g., `dashboard::admin.index` is WRONG)
- Global routes: `{role}.{resource}.{action}` (e.g., `admin.users.index`)
- Final route name = `{prefix from ->as()}{name from ->name()}` (e.g., `admin.dashboard.` + `index` = `admin.dashboard.index`)

**Middleware:**
- Always use role middleware: `->middleware(['auth', 'role:admin|super-admin'])`
- Guest routes: `->middleware(['guest'])`
- Auditor routes: Use `readonly` middleware to block POST/PUT/DELETE

---

### 9. Useful Commands

```bash
sail artisan module:make Registration
sail artisan module:make Document
sail artisan module:migrate Registration
sail artisan module:seed Member
sail pint modules/Member/src/Models/Member.php
sail test --filter Member
```

---

**This document is LAW starting today – 27 January 2025.**  
All future code must follow these guidelines exactly.
