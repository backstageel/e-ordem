<laravel-boost-guidelines>
=== .ai/laravel-core rules ===

{{-- Laravel Core Guidelines for e-Ordem --}}

## ⚠️ CRITICAL: Laravel Sail Usage

**THIS PROJECT USES LARAVEL SAIL**

- **ALWAYS use `sail artisan`** instead of `php artisan`
- **ALWAYS use `sail composer`** instead of `composer`
- **ALWAYS use `sail pint`** instead of `vendor/bin/pint` or `php vendor/bin/pint`
- **ALWAYS use `sail npm`** instead of `npm` (if needed)
- **NEVER use `php artisan`**, `php artisan`, `composer`, `vendor/bin/pint`, etc. directly
- **This is EXTREMELY IMPORTANT** - the project is containerized with Docker/Sail

Examples:
- ✅ `sail artisan migrate`
- ✅ `sail artisan make:model Member`
- ✅ `sail composer install`
- ✅ `sail pint app/Models/Member.php`
- ❌ `php artisan migrate` (WRONG!)
- ❌ `composer install` (WRONG!)
- ❌ `vendor/bin/pint` (WRONG!)

## Laravel 12 Specific Guidelines

### Application Structure
- Follow Laravel 12's streamlined file structure
- Use `bootstrap/app.php` for middleware and exception handling
- Use `bootstrap/providers.php` for service providers
- No `app/Console/Kernel.php` - use `bootstrap/app.php` or `routes/console.php`

### Database & Eloquent
- Always use proper Eloquent relationship methods with return type hints
- Use Eloquent models and relationships before raw queries
- Avoid `DB::`; prefer `Model::query()`
- Use eager loading to prevent N+1 query problems
- Use Laravel's query builder for complex database operations

### Model Creation
- Use `sail artisan make:model` with appropriate options
- Create factories and seeders for new models
- Use proper relationships with return type hints
- Implement proper casts using `casts()` method

### Controllers & Validation
- **ALWAYS use Laravel Data Package** for validation
- Create Data objects that extend `Spatie\LaravelData\Data`
- Use constructor property promotion for clean Data objects
- Define validation rules in the `rules()` method
- Use `$request->validate(DataObject::rules())` in controllers
- **Portuguese validation**: Configure `config/app.php` with `'locale' => 'pt'`
- **JSON translations**: Use `lang/pt.json` for Portuguese translations
- **Source code in English**: All source code strings must be in English
- **Enum validation**: Use enum values in validation rules for type safety

### Flash Messages
- **ALWAYS use Laracasts/Flash package** for flash messages
- Use `flash('message', 'success')` for successful operations
- Use `flash('message', 'error')` for errors
- Use `flash('message', 'warning')` for warnings
- Use `flash('message', 'info')` for informational messages

### File Storage
- **Private storage**: Use `Storage::disk('local')` for sensitive files
- **Upload**: Use `$file->storeAs('directory', $filename, 'local')` for private storage
- **Download**: Create dedicated download routes with authentication
- **Security**: Implement file existence validation before download
- **Error handling**: Return 404 for non-existent files
- **NEVER use public storage** for sensitive medical documents

### Translations
- **JSON format**: Use `lang/pt.json` for all translations
- **Source code**: All strings in source code must be in English
- **String keys**: Use English strings themselves as translation keys
- **Blade templates**: Use `{{ __('English string') }}` for translations
- **Controllers**: Use `__('English string')` for flash messages
- **Validation**: Use `__('English string')` for field names
- **Structure**: Flat JSON structure with English strings as keys

### Testing
- **ALWAYS use Pest for testing** instead of PHPUnit
- Use `sail artisan make:test <name>` to create new tests
- Use factories for test data creation
- Test both success and failure scenarios
- Use descriptive test names

### Commands
- **ALWAYS use `sail artisan`** instead of `php artisan`
- Use `sail artisan make:` commands for creating new files
- Pass `--no-interaction` to all Artisan commands
- Use `sail artisan test` to run tests

### Code Quality
- **ALWAYS run `sail pint --dirty`** before finalizing changes (NEVER use `vendor/bin/pint` or `php vendor/bin/pint`)
- Use explicit return type declarations
- Use constructor property promotion
- Use proper type hints for method parameters

### Configuration
- Use environment variables only in configuration files
- Never use `env()` function directly outside config files
- Use `config('app.name')` instead of `env('APP_NAME')`
- Cache configuration in production

### URL Generation
- Use named routes and the `route()` function
- Generate absolute URLs using `get-absolute-url` tool
- Use proper route naming conventions

### Authentication & Authorization
- Use Laravel's built-in authentication features
- Implement proper authorization with gates and policies
- Use role-based access control for medical features
- Implement proper user permission management

### Queues
- Use queued jobs for time-consuming operations
- Implement proper error handling for queued jobs
- Use proper retry mechanisms for failed jobs
- Implement proper logging for queued operations

### Caching
- Use proper caching for frequently accessed data
- Implement cache invalidation strategies
- Use proper cache tags for related data
- Implement proper cache warming strategies

### Logging
- Use proper logging levels for different types of events
- Implement structured logging for medical operations
- Use proper log rotation and cleanup
- Implement proper error logging and monitoring


=== .ai/testing rules ===

{{-- Testing Guidelines for e-Ordem --}}

## ⚠️ CRITICAL: Laravel Sail Usage

**THIS PROJECT USES LARAVEL SAIL**

- **ALWAYS use `sail artisan`** instead of `php artisan`
- **ALWAYS use `sail composer`** instead of `composer`
- **ALWAYS use `sail pint`** instead of `vendor/bin/pint` or `php vendor/bin/pint`
- **ALWAYS use `sail npm`** instead of `npm` (if needed)
- **NEVER use `php artisan`**, `php artisan`, `composer`, `vendor/bin/pint`, etc. directly
- **This is EXTREMELY IMPORTANT** - the project is containerized with Docker/Sail

Examples:
- ✅ `sail artisan test`
- ✅ `sail artisan make:test MemberTest`
- ✅ `sail composer install`
- ✅ `sail pint tests/Feature/MemberTest.php`
- ❌ `php artisan test` (WRONG!)
- ❌ `composer install` (WRONG!)
- ❌ `vendor/bin/pint` (WRONG!)

## Pest Testing Framework

### Test Structure
- **ALWAYS use Pest for testing** instead of PHPUnit
- Use `sail artisan make:test <name>` to create new tests
- Use descriptive test names that explain the scenario
- Group related tests using `describe()` blocks
- Use `beforeEach()` and `afterEach()` for setup and cleanup

### Test Categories
- **Feature Tests**: Test complete user workflows and medical processes
- **Unit Tests**: Test individual methods and medical logic
- **Integration Tests**: Test medical system integrations
- **Browser Tests**: Test medical user interfaces
- **Form Validation Tests**: Test Portuguese validation messages and styling
- **File Storage Tests**: Test private file upload and download functionality
- **Enum Validation Tests**: Test enum-based validation and status consistency
- **Translation Tests**: Test JSON translation files and Portuguese translations

### Medical Testing Patterns

#### Form Validation Testing
```php
it('shows Portuguese validation messages for required fields', function () {
    $response = $this->post(route('registration.store'), []);
    $response->assertSessionHasErrors(['member_name', 'specialty', 'status']);
    // Verify Portuguese error messages
});

it('shows required field indicators in forms', function () {
    $response = $this->get(route('registration.create'));
    $response->assertSee('form-label required');
    $response->assertSee('Nome do Membro');
    $response->assertSee('Especialidade');
});
```

#### File Storage Testing
```php
it('can upload files to private storage', function () {
    Storage::fake('local');
    $file = UploadedFile::fake()->create('certificate.pdf');

    $response = $this->post(route('registration.store'), [
        'certificate_file' => $file,
        // other required fields
    ]);

    Storage::disk('local')->assertExists('medical_certificates/' . $file->hashName());
});

it('can download private files securely', function () {
    $registration = Registration::factory()->create([
        'certificate_file' => 'medical_certificates/test.pdf'
    ]);
    Storage::disk('local')->put('medical_certificates/test.pdf', 'content');

    $response = $this->get(route('registration.download-certificate', $registration->id));
    $response->assertStatus(200);
    $response->assertHeader('Content-Disposition');
});
```

#### Enum Validation Testing
```php
it('validates registration status using enum values', function () {
    $validStatus = RegistrationStatus::PENDING->value;
    $registrationData = [
        'status' => $validStatus,
        // other required fields
    ];

    $response = $this->post(route('registration.store'), $registrationData);
    $response->assertRedirect();
    $this->assertDatabaseHas('registrations', ['status' => $validStatus]);
});

it('rejects invalid status values', function () {
    $registrationData = [
        'status' => 'Invalid Status',
        // other required fields
    ];

    $response = $this->post(route('registration.store'), $registrationData);
    $response->assertSessionHasErrors(['status']);
});
```

#### Translation Testing
```php
it('displays Portuguese translations in forms', function () {
    $response = $this->get(route('registration.create'));
    $response->assertSee(__('Create Registration'));
    $response->assertSee(__('Member Name'));
    $response->assertSee(__('Specialty'));
});

it('shows Portuguese validation messages', function () {
    $response = $this->post(route('registration.store'), []);
    $response->assertSessionHasErrors(['member_name', 'specialty', 'status']);

    $errors = session('errors')->getBag('default');
    $this->assertStringContainsString('obrigatório', $errors->first('member_name'));
});

it('uses English strings as translation keys', function () {
    // Verify that source code uses English strings as keys
    $this->assertTrue(__('Create Registration') !== 'Create Registration');
    $this->assertTrue(__('Member Name') !== 'Member Name');
    $this->assertTrue(__('Specialty') !== 'Specialty');
});
```

#### Member Testing
```php
it('can create a member with required fields', function () {
    // Test member creation
    // Verify medical requirements
    // Check audit trail creation
});

it('can update member status through proper workflow', function () {
    // Test status transitions
    // Verify medical rules
    // Check notification triggers
});
```

#### Registration Testing
```php
it('can validate registration conditions before approval', function () {
    // Test registration validation
    // Verify medical requirements
    // Check approval workflow
});

it('can track registration document changes', function () {
    // Test document versioning
    // Verify audit trail
    // Check medical tracking
});
```

#### Examination Testing
```php
it('can process new examination updates', function () {
    // Test examination processing
    // Verify medical impact
    // Check notification system
});

it('can track medical requirements per examination', function () {
    // Test medical tracking
    // Verify requirement mapping
    // Check status updates
});
```

### Test Data Management

#### Factories
- Use factories for all test data creation
- Create realistic medical scenarios
- Use factory states for different medical statuses
- Implement proper data relationships

#### Database Testing
- Use database transactions for test isolation
- Implement proper cleanup after tests
- Use realistic medical data in tests
- Test database constraints and relationships

### Medical Test Scenarios

#### Happy Path Testing
- Test successful medical operations
- Verify proper data creation and updates
- Check successful status transitions
- Verify proper notifications

#### Failure Path Testing
- Test validation failures
- Verify error handling
- Check proper error messages
- Test edge cases and boundary conditions

#### Medical Workflow Testing
- Test complete medical processes
- Verify proper audit trails
- Check notification systems
- Test escalation procedures

### Performance Testing

#### Medical Data Performance
- Test large medical datasets
- Verify proper pagination
- Check query performance
- Test caching mechanisms

#### Audit Trail Performance
- Test audit trail creation performance
- Verify proper indexing
- Check cleanup procedures
- Test large audit datasets

### Security Testing

#### Access Control Testing
- Test user permission enforcement
- Verify role-based access
- Check authorization policies
- Test data access restrictions

#### Medical Data Security
- Test sensitive data protection
- Verify proper encryption
- Check audit trail security
- Test data retention policies

### Integration Testing

#### External System Integration
- Test medical system integrations
- Verify proper error handling
- Check retry mechanisms
- Test notification systems

#### API Integration Testing
- Test medical API endpoints
- Verify proper authentication
- Check rate limiting
- Test error responses

### Test Organization

#### Test Structure
```
tests/
├── Feature/
│   ├── Member/
│   │   ├── MemberManagementTest.php
│   │   ├── MemberRegistrationTest.php
│   │   └── MemberWorkflowTest.php
│   ├── Registration/
│   │   ├── RegistrationManagementTest.php
│   │   └── RegistrationProcessTest.php
│   └── Examination/
│       ├── ExaminationTrackingTest.php
│       └── MedicalTest.php
└── Unit/
    ├── Models/
    ├── Services/
    └── Enums/
```

#### Test Naming Conventions
- Use descriptive test names
- Include the medical scenario being tested
- Use proper test grouping
- Follow consistent naming patterns

### Test Execution

#### Running Tests
- Use `sail artisan test` to run all tests
- Use `sail artisan test --filter=testName` for specific tests
- Use `sail artisan test tests/Feature/Audit/` for feature tests
- Use `sail artisan test --parallel` for parallel execution

#### Test Coverage
- Aim for high test coverage on medical logic
- Focus on critical medical workflows
- Test all medical status transitions
- Verify proper error handling

### Test Maintenance

#### Test Updates
- Update tests when medical requirements change
- Maintain test data consistency
- Keep tests up to date with code changes
- Remove obsolete tests

#### Test Documentation
- Document complex medical test scenarios
- Explain medical workflow tests
- Document test data requirements
- Maintain test execution documentation


=== .ai/e-ordem-project rules ===

{{-- e-Ordem Project-Specific AI Guidelines --}}

## ⚠️ CRITICAL: Laravel Sail Usage

**THIS PROJECT USES LARAVEL SAIL**

- **ALWAYS use `sail artisan`** instead of `php artisan`
- **ALWAYS use `sail composer`** instead of `composer`
- **ALWAYS use `sail pint`** instead of `vendor/bin/pint` or `php vendor/bin/pint`
- **ALWAYS use `sail npm`** instead of `npm` (if needed)
- **NEVER use `php artisan`**, `php artisan`, `composer`, `vendor/bin/pint`, etc. directly
- **This is EXTREMELY IMPORTANT** - the project is containerized with Docker/Sail

Examples:
- ✅ `sail artisan migrate`
- ✅ `sail artisan make:model Member`
- ✅ `sail composer install`
- ✅ `sail pint app/Models/Member.php`
- ❌ `php artisan migrate` (WRONG!)
- ❌ `composer install` (WRONG!)
- ❌ `vendor/bin/pint` (WRONG!)

## Project Overview
e-Ordem is a comprehensive digital platform for the Medical Association of Mozambique (OrMM) built with Laravel 12, focusing on:
- Medical member registration and management
- Professional registration and licensing
- Medical examination management
- Medical residency program management
- Payment and financial management
- Document and certificate management
- Professional card and badge management

## Code Style & Conventions

### Naming Conventions
- Use descriptive names for medical and administrative entities
- Prefix medical-related classes with appropriate prefixes (e.g., `MedicalRegistration`, `ResidencyProgram`)
- Use camelCase for methods and variables
- Use PascalCase for classes and enums

### Data Validation
- **ALWAYS use Laravel Data Package** for validation instead of Form Requests or inline validation
- **MANDATORY**: All validation MUST be done using Laravel Data classes
- Create separate Data objects for create and update operations (e.g., `RegistrationData`, `RegistrationUpdateData`)
- Use constructor property promotion for clean, readable Data objects
- Define validation rules in the `rules()` method, not as attributes
- **NEVER use inline validation** in controllers - always use Data classes
- **NEVER use Form Requests** - always use Laravel Data Package
- **Validation messages in Portuguese**: All validation messages must be in Portuguese
- Use JSON translation files: `lang/pt.json` for Portuguese translations
- Configure `config/app.php` with `'locale' => 'pt'` for Portuguese as default language
- **Source code in English**: All source code strings must be in English
- **Translation keys**: Use descriptive translation keys in English

### Laravel Data Package Usage
- **ALWAYS use Laravel Data Package** for all validation in this project
- **Controller Pattern**:
  ```php
  // ✅ CORRECT: Use Data classes
  $validated = $request->validate(LegislationData::rules());

  // ❌ WRONG: Inline validation
  $validated = $request->validate([
      'title' => 'required|string|max:255',
      // ...
  ]);
  ```
- **Data Class Structure**:
  ```php
  class RegistrationData extends Data
  {
      public function __construct(
          public string $member_name,
          public ?string $specialty,
          // ... other properties
      ) {}

      public static function rules(): array
      {
          return [
              'member_name' => ['required', 'string', 'max:255'],
              'specialty' => ['nullable', 'string'],
              // ... other rules
          ];
      }
  }
  ```
- **Update Operations**: Use separate Data classes with ID parameter
  ```php
  // For updates, pass the model ID to rules method
  $validated = $request->validate(RegistrationUpdateData::rules($id));
  ```
- **Benefits**: Type safety, reusability, centralized validation, better testing

### Flash Messages
- **ALWAYS use Laracasts/Flash package** for user feedback
- Use `flash('message', 'success')` for successful operations
- Use `flash('message', 'error')` for errors
- Use `flash('message', 'warning')` for warnings
- Use `flash('message', 'info')` for informational messages

### Blade Templates & Layouts
- **ALWAYS use Blade Components** for layouts instead of `@extends` and `@section`
- Use `<x-layouts.app>` instead of `@extends('layouts.app')`
- Use `<x-slot name="header">` instead of `@section('title')`
- Use `<x-slot name="content">` instead of `@section('content')`
- **Layout Structure**:
  ```blade
  <x-layouts.app>
      <x-slot name="header">
          <h2>{{ __('Page Title') }}</h2>
      </x-slot>

      <div class="py-4">
          <div class="container-fluid">
              <!-- Page content -->
          </div>
      </div>
  </x-layouts.app>
  ```
- **Available Layouts**: `components/layouts/app.blade.php`, `components/layouts/guest.blade.php`
- **Component Structure**: All layouts are in `resources/views/components/layouts/`

### Enum Usage in Views
- **ALWAYS use Enums in Blade templates** instead of hardcoded values
- Use `@foreach(\App\Enums\EnumName::cases() as $item)` to iterate over enum cases
- **Example for select options**:
  ```blade
  <select name="field">
      @foreach(\App\Enums\RegistrationStatus::cases() as $status)
          <option value="{{ $status->value }}" {{ old('field', $model->field) === $status->value ? 'selected' : '' }}>
              {{ $status->label() }}
          </option>
      @endforeach
  </select>
  ```
- **Displaying enum values in views**:
  ```blade
  <!-- For enums with label() method -->
  {{ $model->enumField->label() }}

  <!-- For enums without label() method -->
  {{ $model->enumField->value }}

  <!-- NEVER use ucfirst() or other string functions on enums -->
  <!-- ❌ Wrong: {{ ucfirst($model->enumField) }} -->
  <!-- ✅ Correct: {{ $model->enumField->label() }} -->
  ```
- **Benefits**: Automatic synchronization, no hardcoded values, easier maintenance
- **Enum Methods**: Use `label()` method for display labels when available

### Array Cast Fields
- **ALWAYS check if field is cast as array** before using `json_decode()`
- **For array cast fields**: Use directly as array, no `json_decode()` needed
- **Example for keywords field**:
  ```blade
  <!-- ❌ Wrong: json_decode() on array cast field -->
  @foreach(json_decode($model->keywords) as $keyword)

  <!-- ✅ Correct: Use array directly -->
  @foreach($model->keywords as $keyword)
  ```
- **Check model casts**: Verify `protected $casts = ['field' => 'array']` in model
- **Common array fields**: `keywords`, `tags`, `categories`, `metadata`

### Keywords Field Standardization
- **ALWAYS use single input field** for keywords with comma separation
- **User Input**: `"palavra1, palavra2, palavra3"`
- **Controller Processing**: Convert string to array automatically
- **View Display**: Show as comma-separated string in forms
- **Example Implementation**:
  ```blade
  <!-- View: Single input field -->
  <input type="text" name="keywords" value="{{ old('keywords', is_array($model->keywords) ? implode(', ', $model->keywords) : $model->keywords) }}" placeholder="palavra1, palavra2, palavra3">
  <small class="form-text text-muted">Separe as palavras-chave por vírgula</small>

  <!-- Controller: Convert string to array -->
  if (isset($validated['keywords']) && is_string($validated['keywords'])) {
      $validated['keywords'] = array_filter(array_map('trim', explode(',', $validated['keywords'])));
  }
  ```
- **Benefits**: Consistent UX, easier data entry, automatic array conversion

### Form Validation & UI
- **Form validation indicators**: Use `form-label required` class for required fields
- **Visual validation feedback**: Implement red borders and background for invalid fields
- **CSS styling**: Use direct CSS in `public/assets/css/style.css` (no Vite compilation)
- **Required field indicators**: Use asterisk (*) in red for required fields
- **Validation messages**: Display Portuguese validation messages with proper styling

## Translation Guidelines

### JSON Translation Files
- **Use JSON files**: Store translations in `lang/pt.json` format
- **Source code in English**: All strings in source code must be in English
- **String keys**: Use the English strings themselves as translation keys
- **Structure**: Flat JSON structure with English strings as keys

### Translation Patterns
- **Blade templates**: Use `{{ __('English string') }}` for translations
- **Controllers**: Use `__('English string')` for flash messages and responses
- **Validation**: Use `__('English string')` for field names
- **String keys**: Use the English strings themselves as translation keys

### Translation File Structure
```json
{
  "Legislation": "Legislação",
  "Create Legislation": "Criar Legislação",
  "Edit Legislation": "Editar Legislação",
  "Number": "Número",
  "Title": "Título",
  "Description": "Descrição",
  "The :attribute field is required.": "O campo :attribute é obrigatório.",
  "The :attribute field must be unique.": "O campo :attribute já está em uso."
}
```

## Medical-Specific Patterns

### Member Management
- All member-related models must implement proper status tracking
- Use enums for registration statuses (e.g., `RegistrationStatus`, `RegistrationPriority`)
- Implement proper audit trails for member tracking
- Use soft deletes for member data retention

### Professional Registration
- Registration conditions must be properly validated
- Registration documents should be stored securely
- Registration processes must be tracked with timestamps
- Use proper relationships between registrations and members

### Medical Examination Management
- Examination schedules must be properly managed
- Use proper scoping for examination types
- Implement notification systems for examination updates
- Track examination results and compliance requirements
- **Registration Status**: Use enum `RegistrationStatus` for consistent status validation
- **Status validation**: Views must use `@foreach(\App\Enums\RegistrationStatus::cases() as $status)` for dynamic options
- **Status consistency**: Never hardcode status values in views - always use enum values

## File Storage Guidelines

### Private File Storage
- **ALWAYS use private storage** for sensitive documents (medical certificates, registration documents, etc.)
- Store files in `storage/app/private/` using `Storage::disk('local')`
- **NEVER use public storage** for sensitive medical documents
- Implement secure download methods for private files

### File Upload & Download
- **Upload**: Use `$file->storeAs('directory', $filename, 'local')` for private storage
- **Download**: Create dedicated download routes with proper authentication
- **Security**: Implement file existence validation before download
- **Error handling**: Return 404 for non-existent files
- **File validation**: Validate file types and sizes in Data objects

### File Access Patterns
- **Download routes**: Use `route('resource.download-document', $id)` for file downloads
- **Authentication**: Ensure only authenticated users can download files
- **Validation**: Check file existence before allowing download
- **Headers**: Use proper Content-Disposition headers for downloads

## Frontend & CSS Guidelines

### CSS Architecture
- **Direct CSS**: Use `public/assets/css/style.css` for all styling (no Vite compilation)
- **No SCSS compilation**: Avoid SCSS files and compilation tools
- **CSS variables**: Use CSS custom properties for consistent theming
- **Form styling**: Implement comprehensive form validation styles

### Form Validation Styling
- **Required fields**: Use `form-label required` class with red asterisk
- **Invalid fields**: Red borders, background, and box-shadow for validation errors
- **Valid fields**: Green borders and check icons for successful validation
- **Error messages**: Styled error messages with warning icons
- **Animations**: Shake animation for invalid fields on form submission

### UI Components
- **Bootstrap integration**: Use Bootstrap 5 classes with custom CSS overrides
- **Responsive design**: Ensure mobile-friendly form layouts
- **Accessibility**: Proper form labels and ARIA attributes
- **Visual feedback**: Clear indicators for form states and validation

## Database Guidelines

### Models
- All models must use soft deletes for data retention
- Implement proper relationships with return type hints
- Use enums for status fields
- Implement proper audit trails

### Migrations
- Use descriptive migration names
- Include proper indexes for performance
- Use foreign key constraints for data integrity
- Include proper timestamps and soft delete columns

## Testing Requirements

### Test Coverage
- All new features must have Pest tests
- Test both success and failure scenarios
- Use descriptive test names that explain the medical scenario
- Test audit trails and member tracking
- **Form validation tests**: Test Portuguese validation messages
- **File upload tests**: Test private file storage and download functionality
- **Status validation tests**: Test enum-based status validation
- **UI component tests**: Test form validation indicators and styling

### Test Data
- Use factories for test data creation
- Create realistic medical scenarios in tests
- Test edge cases for medical requirements
- Verify proper status transitions
- **File testing**: Use `Storage::fake()` for file upload tests
- **Enum testing**: Test all enum values for proper validation
- **Form testing**: Test required field indicators and validation styling

## Security & Compliance

### Data Protection
- Sensitive medical data must be properly encrypted
- Implement proper access controls for member data
- Use proper authorization for medical operations
- Implement audit logging for medical actions

### User Permissions
- Use Laravel's built-in authorization features
- Implement role-based access for medical features
- Use policies for complex authorization logic
- Implement proper user role management

## API Guidelines

### Medical APIs
- Use Laravel Data Package for API validation
- Implement proper error handling for medical operations
- Use API resources for consistent responses
- Implement proper rate limiting for medical APIs

### Documentation
- Document all medical-related APIs
- Include proper error codes and messages
- Document medical workflows
- Include examples for medical operations

## Performance Considerations

### Medical Data
- Implement proper caching for frequently accessed medical data
- Use database indexes for medical queries
- Implement proper pagination for large medical datasets
- Use eager loading to prevent N+1 queries

### Audit Trails
- Implement efficient audit trail storage
- Use proper database partitioning for large audit datasets
- Implement proper cleanup for old audit data
- Use proper indexing for audit queries

## Recent Architecture Changes

### Form Validation System
- **Portuguese validation**: All validation messages must be in Portuguese
- **Laravel Data Package**: Use for all form validation instead of Form Requests
- **Visual indicators**: Required fields show red asterisk (*)
- **Validation styling**: Red borders and backgrounds for invalid fields
- **Error messages**: Styled with warning icons and proper Portuguese text
- **JSON translations**: Use `lang/pt.json` for all Portuguese translations
- **Source code in English**: All source code strings must be in English
- **String keys**: Use English strings themselves as translation keys

### File Storage Architecture
- **Private storage**: All sensitive files stored in `storage/app/private/`
- **Secure downloads**: Files accessible only through authenticated download routes
- **No public access**: Files never stored in public directories
- **Download validation**: Check file existence before allowing download

### CSS Architecture
- **Direct CSS**: No Vite compilation, use `public/assets/css/style.css`
- **Form styling**: Comprehensive validation styles with animations
- **Bootstrap integration**: Custom CSS overrides for form validation
- **Responsive design**: Mobile-friendly form layouts

### Enum-Based Validation
- **Registration Status**: Use `RegistrationStatus` enum for consistent validation
- **Dynamic options**: Views use enum values, never hardcoded options
- **Type safety**: Enum values ensure type safety and consistency
- **Validation rules**: Enum values used in Data object validation rules

## Integration Guidelines

### External Systems
- Implement proper error handling for external medical systems
- Use proper logging for medical integrations
- Implement proper retry mechanisms for failed medical operations
- Use proper authentication for external medical APIs

### Notifications
- Implement proper notification systems for medical updates
- Use proper email templates for medical notifications
- Implement proper escalation procedures for medical issues
- Use proper logging for medical notifications


=== .ai/e-ordem-architecture rules ===

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


=== .ai/design-system rules ===

# Design System - Plataforma e-Ordem
**Sistema Integrado de Gestão da Ordem dos Médicos de Moçambique (OrMM)**

**Última Atualização:** 27 de Janeiro de 2025  
**Versão:** 1.0

---

## 1. VISÃO GERAL

Este documento define o design system completo da plataforma e-Ordem, incluindo paleta de cores, tipografia, espaçamento, componentes e padrões de uso. O design system garante consistência visual e de experiência em toda a plataforma.

**Localização das Variáveis CSS:** `public/assets/css/variables.css`  
**Localização dos Estilos Base:** `public/assets/css/style.css`

---

## 2. PALETA DE CORES

### 2.1 Cores da Marca (Brand Colors)

A paleta de cores da OrMM é baseada no verde institucional, aplicado de forma hierárquica e estratégica.

#### Primary Colors
- **Primary Color:** `#2d5016` (Verde OrMM - Base)
  - **Uso:** Botões primários, links ativos, elementos de destaque, call-to-actions
  - **Não usar em:** Backgrounds estruturais (header, sidebar, body)
  - **Variável CSS:** `var(--primary-color)`
  
- **Primary Hover:** `#234010` (Tom mais escuro para estados hover)
  - **Uso:** Estados hover de botões e links primários
  - **Variável CSS:** `var(--primary-hover)`
  
- **Primary Light:** `#e9f0e6` (Verde muito claro para backgrounds sutis)
  - **Uso:** Backgrounds de hover em links, estados ativos sutis, highlights
  - **Variável CSS:** `var(--primary-light)`

#### Secondary Colors
- **Secondary Color:** `#4a7c2a` (Verde Médio)
  - **Uso:** Botões secundários, elementos de apoio, badges secundários
  - **Variável CSS:** `var(--secondary-color)`
  
- **Secondary Hover:** `#3d6622` (Tom mais escuro para hover)
  - **Variável CSS:** `var(--secondary-hover)`

#### Accent Colors
- **Accent Color:** `#6ba83a` (Verde Claro - Highlights)
  - **Uso:** Destaques visuais, elementos de ênfase, progress indicators
  - **Variável CSS:** `var(--accent-color)`
  
- **Accent Yellow:** `#ffd700` (Amarelo OrMM)
  - **Uso:** Alertas especiais, elementos de atenção, warnings importantes
  - **Variável CSS:** `var(--accent-yellow)`

### 2.2 Cores Neutras (Neutral Palette)

Escala completa de cinzas para backgrounds, textos e bordas.

| Variável | Valor | Uso |
|----------|-------|-----|
| `--neutral-0` | `#ffffff` | Backgrounds de header, sidebar, cards, modais |
| `--neutral-50` | `#f8f9fa` | Background principal do body, áreas de conteúdo |
| `--neutral-100` | `#e9ecef` | Bordas sutis, separadores, dividers |
| `--neutral-200` | `#dee2e6` | Bordas principais, linhas divisórias |
| `--neutral-300` | `#ced4da` | Inputs desabilitados, elementos inativos |
| `--neutral-400` | `#adb5bd` | Textos secundários muito claros |
| `--neutral-500` | `#6c757d` | Textos secundários, labels |
| `--neutral-600` | `#495057` | Textos de navegação, links secundários |
| `--neutral-700` | `#343a40` | Textos de sidebar, cabeçalhos de seção |
| `--neutral-800` | `#212529` | Texto principal do body, conteúdo |
| `--neutral-900` | `#000000` | Textos de máxima importância |

### 2.3 Cores Semânticas (Semantic Colors)

Cores para comunicar estados e feedback ao utilizador.

#### Success (Sucesso)
- **Cor:** `#198754` (Verde Bootstrap)
- **Background:** `#d1e7dd`
- **Texto:** `#0f5132`
- **Variáveis:** `var(--success-color)`, `var(--success-bg)`, `var(--success-text)`
- **Uso:** Operações bem-sucedidas, confirmações, estados positivos

#### Warning (Aviso)
- **Cor:** `#ffc107` (Amarelo Bootstrap)
- **Background:** `#fff3cd`
- **Texto:** `#664d03`
- **Variáveis:** `var(--warning-color)`, `var(--warning-bg)`, `var(--warning-text)`
- **Uso:** Avisos, alertas que requerem atenção, estados pendentes

#### Danger (Perigo/Erro)
- **Cor:** `#dc3545` (Vermelho Bootstrap)
- **Background:** `#f8d7da`
- **Texto:** `#842029`
- **Variáveis:** `var(--danger-color)`, `var(--danger-bg)`, `var(--danger-text)`
- **Uso:** Erros, ações destrutivas, estados críticos

#### Info (Informação)
- **Cor:** `#0dcaf0` (Azul Bootstrap)
- **Background:** `#cff4fc`
- **Texto:** `#055160`
- **Variáveis:** `var(--info-color)`, `var(--info-bg)`, `var(--info-text)`
- **Uso:** Informações gerais, dicas, estados informativos

### 2.4 Distribuição de Cores na Interface

**Regra de Ouro:** Cores saturadas (verde escuro, cores semânticas) devem ocupar **máximo 15%** da interface. Cores neutras devem dominar (~75%).

- **Cores Neutras:** ~75% da interface
- **Verde Escuro (#2d5016):** ~5% (apenas elementos de ação)
- **Verde Claro (#e9f0e6):** ~10% (hover states, highlights)
- **Cores Semânticas:** ~5% (badges, alertas)
- **Outras Cores:** ~5% (destaques especiais)

---

## 3. TIPOGRAFIA E HIERARQUIA TEXTUAL

### 3.1 Família Tipográfica

**Fonte Principal:** Inter  
**Fallbacks:** "Segoe UI", system-ui, -apple-system, sans-serif  
**Variável CSS:** `var(--font-sans)`

**Fonte Monoespaçada:** "SF Mono", "Monaco", monospace  
**Variável CSS:** `var(--font-mono)`  
**Uso:** Códigos, números técnicos, dados tabulares

### 3.2 Escala Tipográfica

Escala baseada em rem, garantindo acessibilidade e consistência.

| Variável | Tamanho | Pixels | Uso |
|----------|---------|--------|-----|
| `--text-xs` | `0.75rem` | 12px | Textos muito pequenos, labels secundários, timestamps |
| `--text-sm` | `0.875rem` | 14px | Textos secundários, corpo de tabelas, form labels |
| `--text-base` | `1rem` | 16px | Texto principal do body, parágrafos |
| `--text-lg` | `1.125rem` | 18px | Subtítulos, cards importantes |
| `--text-xl` | `1.25rem` | 20px | Títulos de seção, headings de nível 3 |
| `--text-2xl` | `1.5rem` | 24px | Títulos principais, headings de nível 2 |

### 3.3 Hierarquia de Headings

**Regra:** Sempre usar variáveis CSS, nunca valores hardcoded.

#### H1 - Título Principal da Página
```css
font-size: var(--text-2xl);  /* 24px */
font-weight: 700;             /* Bold */
color: var(--neutral-800);    /* Quase preto */
line-height: 1.2;
margin-bottom: var(--space-4); /* 16px */
```

**Uso:** Título principal de cada página, exibido no header ou topo do conteúdo.

#### H2 - Títulos de Seção
```css
font-size: var(--text-xl);    /* 20px */
font-weight: 600;             /* Semibold */
color: var(--neutral-800);
line-height: 1.3;
margin-bottom: var(--space-3); /* 12px */
margin-top: var(--space-5);    /* 24px */
```

**Uso:** Títulos de seções principais, cards importantes, grupos de formulários.

#### H3 - Subtítulos
```css
font-size: var(--text-lg);    /* 18px */
font-weight: 600;             /* Semibold */
color: var(--neutral-700);
line-height: 1.4;
margin-bottom: var(--space-2); /* 8px */
```

**Uso:** Subtítulos dentro de seções, títulos de cards, labels de grupos.

#### H4-H6 - Headings Secundários
```css
font-size: var(--text-base);  /* 16px */
font-weight: 600;             /* Semibold */
color: var(--neutral-700);
```

**Uso:** Headings dentro de componentes, títulos de modais pequenos.

### 3.4 Pesos de Fonte

| Peso | Valor | Variável | Uso |
|------|-------|----------|-----|
| Regular | 400 | `font-weight: 400` | Texto do body, parágrafos |
| Medium | 500 | `font-weight: 500` | Links, labels importantes |
| Semibold | 600 | `font-weight: 600` | Headings, títulos de cards |
| Bold | 700 | `font-weight: 700` | Títulos principais, ênfase |

### 3.5 Line Height (Altura de Linha)

- **Headings:** `1.2` a `1.3` (compacto)
- **Body Text:** `1.5` (legível)
- **Labels e Textos Pequenos:** `1.4`

### 3.6 Aplicação em Componentes

**⚠️ REGRA CRÍTICA:** **NUNCA usar variáveis CSS diretamente nas views.** Sempre usar classes CSS personalizadas definidas em `public/assets/css/style.css`.

#### Títulos de Página
```blade
<h1 class="heading-1">{{ $header }}</h1>
```

#### Títulos de Seção
```blade
<h2 class="heading-2">{{ __('Section Title') }}</h2>
```

#### Títulos de Cards
```blade
<h5 class="card-title-lg">{{ __('Card Title') }}</h5>
```

#### Texto do Body
```blade
<p class="text-base text-dark">
    {{ $content }}
</p>
```

#### Textos Secundários
```blade
<span class="text-sm text-muted">
    {{ $secondaryText }}
</span>
```

#### Estatísticas/Números
```blade
<h3 class="stat-number">{{ $value }}</h3>
<h6 class="stat-label">Total</h6>
```

#### Timeline Items
```blade
<h6 class="timeline-title">Event Title</h6>
<p class="timeline-description">Event description</p>
<small class="timeline-meta">{{ $date }}</small>
```

#### Modal Titles
```blade
<h5 class="modal-title-lg">Modal Title</h5>
```

### 3.7 Contraste e Acessibilidade

Todos os textos devem atender aos requisitos WCAG 2.1 AA:

- **Texto Normal (≤18px):** Contraste mínimo 4.5:1
  - Implementado: 12.6:1 (neutral-800 sobre neutral-50) ✅
  
- **Texto Grande (>18px):** Contraste mínimo 3:1
  - Implementado: 7.1:1+ para headings ✅
  
- **Componentes UI:** Contraste mínimo 3:1
  - Implementado: 7.1:1+ para botões e links ✅

---

## 4. ESPAÇAMENTO

### 4.1 Sistema de Espaçamento (8pt Grid)

Espaçamento baseado em grid de 8 pontos para consistência visual.

| Variável | Valor | Pixels | Uso |
|----------|-------|--------|-----|
| `--space-1` | `0.25rem` | 4px | Espaçamento mínimo, separadores muito finos |
| `--space-2` | `0.5rem` | 8px | Espaçamento entre elementos relacionados |
| `--space-3` | `0.75rem` | 12px | Espaçamento entre grupos pequenos |
| `--space-4` | `1rem` | 16px | Padding padrão, margens padrão |
| `--space-5` | `1.5rem` | 24px | Espaçamento entre seções, padding de cards |
| `--space-6` | `2rem` | 32px | Espaçamento entre seções grandes |
| `--space-8` | `3rem` | 48px | Espaçamento máximo, separação de áreas principais |

### 4.2 Classes CSS de Espaçamento

**⚠️ REGRA CRÍTICA:** **NUNCA usar variáveis CSS diretamente nas views.** Sempre usar classes CSS personalizadas definidas em `public/assets/css/style.css`.

#### Classes de Padding
```blade
<div class="p-spacing-1">Padding mínimo (4px)</div>
<div class="p-spacing-2">Padding pequeno (8px)</div>
<div class="p-spacing-3">Padding médio (12px)</div>
<div class="p-spacing-4">Padding padrão (16px)</div>
<div class="p-spacing-5">Padding grande (24px)</div>
<div class="p-spacing-6">Padding extra grande (32px)</div>
<div class="p-spacing-8">Padding máximo (48px)</div>
```

#### Classes de Margin
```blade
<div class="mb-spacing-4">Margin bottom padrão (16px)</div>
<div class="mb-spacing-5">Margin bottom grande (24px)</div>
<div class="mb-spacing-6">Margin bottom extra grande (32px)</div>
<div class="mt-spacing-4">Margin top padrão (16px)</div>
<div class="mt-spacing-5">Margin top grande (24px)</div>
```

#### Classes de Gap (Flexbox/Grid)
```blade
<div class="d-flex gap-spacing-3">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

#### Classes Especializadas

**Cards:**
```blade
<div class="card">
    <div class="card-body card-spacing">Conteúdo padrão (24px padding)</div>
</div>
<div class="card">
    <div class="card-body card-spacing-sm">Conteúdo compacto (16px padding)</div>
</div>
<div class="card">
    <div class="card-body card-spacing-lg">Conteúdo espaçoso (32px padding)</div>
</div>
```

**Formulários:**
```blade
<div class="form-group form-group-spacing">
    <label>Campo padrão (16px margin-bottom)</label>
    <input type="text">
</div>
<div class="form-group form-group-spacing-sm">
    <label>Campo compacto (12px margin-bottom)</label>
    <input type="text">
</div>
<div class="form-group form-group-spacing-lg">
    <label>Campo espaçoso (24px margin-bottom)</label>
    <input type="text">
</div>
```

**Seções:**
```blade
<section class="section-spacing">Seção padrão (32px margin-bottom)</section>
<section class="section-spacing-sm">Seção compacta (24px margin-bottom)</section>
<section class="section-spacing-lg">Seção espaçosa (48px margin-bottom)</section>
```

### 4.3 Densidade Visual

**Princípio:** Usar whitespace generosamente para reduzir densidade visual e melhorar legibilidade.

#### Regras de Densidade

**Cards:**
- **Padrão:** Usar classe `.card-spacing` (24px padding)
- **Compacto:** Usar classe `.card-spacing-sm` (16px padding) apenas quando necessário
- **Espaçoso:** Usar classe `.card-spacing-lg` (32px padding) para cards importantes

**Linhas de Tabela:**
- **Padding vertical:** Mínimo de `var(--space-3)` (12px)
- **Padding horizontal:** Mínimo de `var(--space-4)` (16px)
- **Espaçamento entre linhas:** Adequado para leitura confortável

**Formulários:**
- **Espaçamento entre campos:** Usar classe `.form-group-spacing` (16px margin-bottom)
- **Formulários longos:** Revisar densidade visual:
  - Agrupar campos relacionados em seções com `.section-spacing`
  - Usar `.form-group-spacing-lg` (24px) entre grupos de campos importantes
  - Considerar dividir formulários longos em múltiplas etapas (wizard)
- **Labels e inputs:** Espaçamento mínimo de `var(--space-2)` (8px) entre label e input

**Seções:**
- **Entre seções principais:** Usar classe `.section-spacing` (32px margin-bottom)
- **Entre subseções:** Usar classe `.section-spacing-sm` (24px margin-bottom)
- **Entre áreas principais:** Usar classe `.section-spacing-lg` (48px margin-bottom)

#### Revisão de Densidade Visual

**Formulários Longos:**
1. **Agrupamento:** Agrupar campos relacionados em cards ou seções visuais
2. **Espaçamento:** Usar `.form-group-spacing-lg` entre grupos importantes
3. **Divisão:** Considerar wizard multi-etapas para formulários com 10+ campos
4. **Visualização:** Garantir que pelo menos 50% da altura da viewport seja visível sem scroll

**Tabelas:**
1. **Padding:** Garantir padding mínimo de 12px vertical e 16px horizontal
2. **Altura de linha:** Mínimo de 44px para touch targets em mobile
3. **Espaçamento entre linhas:** Visualmente confortável (não muito apertado)

**Cards:**
1. **Padding interno:** Mínimo de 24px (`.card-spacing`)
2. **Espaçamento entre cards:** Mínimo de 16px (`.mb-spacing-4` ou `.gap-spacing-4`)
3. **Conteúdo:** Evitar sobrecarga de informações em um único card

---

## 5. COMPONENTES

### 5.1 Border Radius

| Variável | Valor | Uso |
|----------|-------|-----|
| `--border-radius-sm` | `4px` | Inputs pequenos, badges pequenos |
| `--border-radius-md` | `6px` | Botões, inputs padrão, cards pequenos |
| `--border-radius-lg` | `8px` | Cards, modais, containers |
| `--border-radius-xl` | `12px` | Cards grandes, containers destacados |

### 5.2 Sombras (Shadows)

| Variável | Valor | Uso |
|----------|-------|-----|
| `--shadow-sm` | `0 1px 2px 0 rgba(0, 0, 0, 0.05)` | Elementos sutis, separadores elevados |
| `--shadow-md` | `0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)` | Cards, dropdowns, sidebars |
| `--shadow-lg` | `0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)` | Modais, popovers, elementos destacados |

### 5.3 Transições

| Variável | Valor | Uso |
|----------|-------|-----|
| `--transition-fast` | `150ms ease` | Hover states, micro-interações |
| `--transition-normal` | `300ms ease` | Transições padrão, animações suaves |

---

## 6. LAYOUT

### 6.1 Dimensões Principais

| Variável | Valor | Uso |
|----------|-------|-----|
| `--header-height` | `60px` | Altura do header superior |
| `--sidebar-width` | `260px` | Largura da sidebar (desktop) |
| `--sidebar-collapsed-width` | `70px` | Largura da sidebar colapsada |
| `--container-padding` | `1.5rem` | Padding padrão de containers |

### 6.2 Breakpoints Responsivos

| Breakpoint | Largura | Uso |
|------------|---------|-----|
| Mobile | `< 576px` | Smartphones |
| Tablet | `≥ 768px` | Tablets |
| Desktop | `≥ 992px` | Desktops e laptops |

---

## 7. PADRÕES DE USO

### 7.1 Aplicação de Cores

**✅ CORRETO:**
- Usar `var(--primary-color)` para botões primários
- Usar `var(--neutral-0)` para backgrounds de header/sidebar
- Usar `var(--neutral-50)` para background do body
- Usar `var(--primary-light)` para estados hover sutis

**❌ INCORRETO:**
- Usar `#2d5016` diretamente (hardcoded)
- Usar verde escuro em backgrounds estruturais
- Misturar cores sem seguir a hierarquia

### 7.2 Aplicação de Tipografia

**✅ CORRETO:**
```blade
<h1 class="heading-1">Título</h1>
<p class="text-base">Texto do body</p>
<span class="text-sm text-muted">Texto secundário</span>
<h3 class="stat-number">{{ $value }}</h3>
<h6 class="stat-label">Label</h6>
```

**❌ INCORRETO:**
```blade
<h1 style="font-size: var(--text-2xl); font-weight: 700;">Título</h1>  <!-- Variável CSS direta -->
<h1 style="font-size: 24px;">Título</h1>  <!-- Hardcoded -->
<p style="font-size: 14px;">Texto</p>     <!-- Hardcoded -->
```

### 7.3 Aplicação de Espaçamento

**✅ CORRETO:**
```blade
<div class="p-5 mb-6">
<!-- Ou usar classes Bootstrap que já seguem o grid -->
<div class="card p-4 mb-4">
```

**Nota:** Para espaçamento, preferir classes Bootstrap (`p-1` a `p-5`, `m-1` a `m-5`, etc.) que já seguem um sistema consistente. Se necessário criar classes personalizadas, definir em `style.css`.

**❌ INCORRETO:**
```blade
<div style="padding: var(--space-5); margin-bottom: var(--space-6);">  <!-- Variável CSS direta -->
<div style="padding: 24px; margin-bottom: 32px;">  <!-- Hardcoded -->
```

---

## 8. COMPONENTES REUTILIZÁVEIS

### 8.1 Cards

**Estrutura Base:**
```blade
<div class="card p-5">
    <h3 class="card-title-lg">Título</h3>
    <p class="text-base text-muted">Conteúdo</p>
</div>
```

**Nota:** Border-radius e box-shadow podem usar classes Bootstrap quando disponíveis. Se necessário criar classes personalizadas, definir em `style.css` usando variáveis CSS.

### 8.2 Botões

**Botão Primário:**
```blade
<button class="btn btn-primary">
    Ação Primária
</button>
```

**Nota:** Classes Bootstrap (`btn-primary`, `btn-outline-secondary`, etc.) já usam variáveis CSS internamente. Não é necessário adicionar estilos inline.

**Botão Secundário:**
```blade
<button class="btn btn-outline-secondary">
    Ação Secundária
</button>
```

### 8.3 Badges

**Badge de Status:**
```blade
<span class="badge bg-success text-sm">
    Ativo
</span>
```

**Nota:** Usar classes Bootstrap (`bg-success`, `bg-warning`, etc.) que já aplicam as cores semânticas corretas. Para tamanhos de texto, usar classes `.text-sm`, `.text-xs`, etc.

---

## 9. ACESSIBILIDADE

### 9.1 Contraste de Cores

Todos os contrastes validados para WCAG 2.1 AA:
- Texto normal: 12.6:1 ✅
- Texto grande: 7.1:1+ ✅
- Componentes UI: 7.1:1+ ✅

### 9.2 Navegação por Teclado

- Tab index lógico implementado
- Focus indicators visíveis (outline de 3px)
- Suporte a Enter/Space em botões customizados

### 9.3 Atributos ARIA

- `aria-label` em elementos interativos
- `role` apropriados
- `alt` em imagens
- `aria-expanded` em elementos colapsáveis

---

## 10. MANUTENÇÃO E ATUALIZAÇÃO

### 10.1 Modificando o Design System

**IMPORTANTE:** Qualquer alteração nas variáveis CSS deve ser:
1. Documentada neste arquivo
2. Testada em todas as views principais
3. Validada para acessibilidade (contraste)
4. Aprovada antes de implementação

### 10.2 Adicionando Novas Variáveis

Ao adicionar novas variáveis:
1. Adicionar em `public/assets/css/variables.css`
2. Documentar neste arquivo
3. Fornecer exemplos de uso
4. Garantir consistência com padrões existentes

---

## 11. REFERÊNCIAS

- **WCAG 2.1:** Web Content Accessibility Guidelines Level AA
- **NHS Design System:** Referência para sistemas de saúde
- **Material Design:** Guidelines de design de interface
- **Bootstrap 5:** Framework base utilizado

---

## 12. TABELAS E PAGINAÇÃO

### 12.1 Padrão de Tabelas

**Colunas Máximas:** Máximo de 7 colunas essenciais em desktop para garantir legibilidade.

**Colunas Padrão para Listagens:**
- Código/ID
- Nome/Identificação
- Informação de Contacto (Telefone ou Email)
- Data relevante (Submissão, Criação, etc.)
- Tipo/Categoria
- Status
- Ações

**Estrutura de Tabela:**
```blade
<table class="table table-hover align-middle">
    <thead class="bg-light">
        <tr>
            <th>Código</th>
            <th>Nome</th>
            <!-- ... outras colunas ... -->
            <th class="text-end">Ações</th>
        </tr>
    </thead>
    <tbody>
        <!-- Conteúdo -->
    </tbody>
</table>
```

### 12.2 Padrão de Ações em Tabelas

**Regra:** Botão "Ver Detalhes" fora do dropdown, outras ações dentro do dropdown.

**Estrutura:**
```blade
<td class="text-end">
    <div class="d-flex align-items-center justify-content-end gap-2">
        <a href="{{ route('resource.show', $item) }}" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
            <i class="fas fa-eye"></i>
        </a>
        <div class="dropdown">
            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('resource.edit', $item) }}">
                        <i class="fas fa-edit me-2 text-secondary"></i> Editar
                    </a>
                </li>
                <li>
                    <button class="dropdown-item text-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                        <i class="fas fa-trash me-2"></i> Apagar
                    </button>
                </li>
            </ul>
        </div>
    </div>
</td>
```

**Ícones Padronizados:**
- Ver Detalhes: `fa-eye` (azul/primary)
- Editar: `fa-edit` (cinza/secondary)
- Apagar: `fa-trash` (vermelho/danger)
- Aprovar: `fa-check` (verde/success)
- Rejeitar: `fa-times` (vermelho/danger)

### 12.3 Paginação

**Idioma:** Sempre em português.

**Template:** Usar template customizado em `resources/views/vendor/pagination/bootstrap-5.blade.php`.

**Estrutura:**
```blade
@if($items->hasPages())
    <div class="card-footer bg-transparent border-top-0 pt-0 pb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">Mostrando {{ $items->firstItem() ?? 0 }} a {{ $items->lastItem() ?? 0 }} de {{ $items->total() }} registos</small>
            {{ $items->links() }}
        </div>
    </div>
@endif
```

**Texto Padronizado:**
- "Mostrando X a Y de Z registos" (não "registros")
- Botões "Anterior" e "Seguinte" (não "Previous" e "Next")

**Estilo:**
- Usar Bootstrap 5 pagination (`Paginator::useBootstrapFive()`)
- Footer do card com `bg-transparent border-top-0` para estilo limpo
- Texto em `text-muted` e `small` para informação contextual

---

## 13. SISTEMA DE STATUS BADGES

### 13.1 Componente de Badge de Status

**Componente:** `<x-status-badge>` - Badge de status com ícone para acessibilidade.

**Uso:**
```blade
<x-status-badge :status="$registration->status" :size="'sm'" />
<x-status-badge :status="$registration->status" :size="'default'" />
<x-status-badge :status="$registration->status" :size="'lg'" />
```

**Parâmetros:**
- `status` (obrigatório): Instância do enum `RegistrationStatus`
- `size` (opcional): `'sm'`, `'default'`, `'lg'` (padrão: `'default'`)
- `showIcon` (opcional): `true` ou `false` (padrão: `true`)
- `showDescription` (opcional): `true` ou `false` (padrão: `false`)

### 13.2 Mapeamento Cor-Estado (RegistrationStatus)

| Status | Cor | Ícone | Descrição |
|--------|-----|-------|-----------|
| Rascunho (DRAFT) | Cinza (secondary) | `fa-file-alt` | Inscrição em rascunho, ainda não submetida |
| Submetido (SUBMITTED) | Amarelo (warning) | `fa-paper-plane` | Inscrição submetida e aguardando análise |
| Em Análise (UNDER_REVIEW) | Azul (info) | `fa-search` | Inscrição em análise pelo secretariado |
| Documentos Pendentes (DOCUMENTS_PENDING) | Amarelo (warning) | `fa-file-exclamation` | Aguardando documentos adicionais |
| Pagamento Pendente (PAYMENT_PENDING) | Amarelo (warning) | `fa-credit-card` | Aguardando confirmação de pagamento |
| Validado (VALIDATED) | Azul primário (primary) | `fa-check-circle` | Inscrição validada, pronta para aprovação |
| Aprovado (APPROVED) | Verde (success) | `fa-check-circle` | Inscrição aprovada e ativa |
| Rejeitado (REJECTED) | Vermelho (danger) | `fa-times-circle` | Inscrição rejeitada |
| Arquivado (ARCHIVED) | Cinza escuro (dark) | `fa-archive` | Inscrição arquivada (inativa há mais de 45 dias) |
| Expirado (EXPIRED) | Vermelho (danger) | `fa-clock` | Inscrição expirada |

### 13.3 Legenda de Status

**Componente:** `<x-status-legend>` - Exibe legenda completa de todos os status.

**Uso:**
```blade
<x-status-legend title="Legenda de Status de Inscrições" :statusEnum="\App\Enums\RegistrationStatus::class" />
```

**Parâmetros:**
- `title` (opcional): Título da legenda (padrão: "Legenda de Status")
- `statusEnum` (obrigatório): Classe do enum de status
- `collapsible` (opcional): `true` ou `false` (padrão: `true`)

### 13.4 Regras de Acessibilidade

**Ícones Obrigatórios:**
- Todos os badges devem incluir ícones para não depender apenas de cor
- Ícones usam `aria-hidden="true"` para não serem lidos por leitores de tela
- Texto do badge é sempre exibido para acessibilidade

**Atributos ARIA:**
- `aria-label` contém label e descrição do status
- `title` attribute opcional para tooltip com descrição completa

**Contraste:**
- Badges usam variante `-light` para backgrounds (melhor contraste)
- Texto usa cor semântica correspondente para legibilidade

---

## 14. PAGINAÇÃO MELHORADA

### 14.1 Componente de Paginação

**Componente:** `<x-pagination-enhanced>` - Paginação completa com controlo de items por página e navegação rápida.

**Uso:**
```blade
<x-pagination-enhanced :paginator="$items" />
```

**Parâmetros:**
- `paginator` (obrigatório): Instância do paginator do Laravel
- `perPageOptions` (opcional): Array de opções de items por página (padrão: `[10, 25, 50, 100]`)
- `showPerPageSelector` (opcional): `true` ou `false` (padrão: `true`)
- `showFirstLast` (opcional): `true` ou `false` (padrão: `true`)

### 14.2 Funcionalidades

**Indicação de Total:**
- Sempre exibe: "Mostrando X a Y de Z registos"
- Valores em negrito para destaque
- Texto em português

**Controlo de Items por Página:**
- Dropdown com opções: 10, 25, 50, 100
- Valor padrão: 10
- Preserva filtros e parâmetros de pesquisa ao alterar

**Navegação Rápida:**
- Botão "Primeira" página (⏪) - apenas quando não está na primeira
- Botão "Anterior" (◀)
- Números de página (mostra 5 páginas: atual ± 2)
- Botão "Seguinte" (▶)
- Botão "Última" página (⏩) - apenas quando não está na última

**Informação Contextual:**
- Range atual: "Mostrando X a Y"
- Total: "de Z registos"
- Sempre visível no topo da paginação

### 14.3 Implementação no Controller

**Suporte a `per_page` parameter:**
```php
$perPage = $request->get('per_page', 10);
$perPage = in_array($perPage, [10, 25, 50, 100]) ? (int) $perPage : 10;

$items = Model::query()
    ->paginate($perPage)
    ->withQueryString(); // Preserva filtros
```

### 14.4 Regras de Acessibilidade

**Atributos ARIA:**
- `aria-label` em todos os botões de navegação
- `aria-current="page"` na página ativa
- `aria-disabled="true"` em botões desabilitados

**Navegação por Teclado:**
- Todos os links são navegáveis por teclado
- Foco visível em todos os elementos interativos

---

**Documento mantido por:** Equipa de Desenvolvimento e-Ordem  
**Última revisão:** 27 de Janeiro de 2025


=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.14
- laravel/folio (FOLIO) - v1
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- laravel/telescope (TELESCOPE) - v5
- livewire/livewire (LIVEWIRE) - v4
- laravel/breeze (BREEZE) - v2
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12

## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.


=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms


=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.


=== tests rules ===

## Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test` with a specific filename or filter.


=== folio/core rules ===

## Laravel Folio

- Laravel Folio is a file based router. With Laravel Folio, a new route is created for every Blade file within the configured Folio directory. For example, pages are usually in in `resources/views/pages/` and the file structure determines routes:
    - `pages/index.blade.php` → `/`
    - `pages/profile/index.blade.php` → `/profile`
    - `pages/auth/login.blade.php` → `/auth/login`
- You may list available Folio routes using `php artisan folio:list`  or using Boost's `list-routes` tool.

### New Pages & Routes
- Always create new `folio` pages and routes using `php artisan folio:page [name]` following existing naming conventions.

<code-snippet name="Example folio:page Commands for Automatic Routing" lang="shell">
    // Creates: resources/views/pages/products.blade.php → /products
    php artisan folio:page "products"

    // Creates: resources/views/pages/products/[id].blade.php → /products/{id}
    php artisan folio:page "products/[id]"
</code-snippet>

- Add a 'name' to each new Folio page at the very top of the file so it has a named route available for other parts of the codebase to use.


<code-snippet name="Adding named route to Folio page" lang="php">
use function Laravel\Folio\name;

name('products.index');
</code-snippet>


### Support & Documentation
- Folio supports: middleware, serving pages from multiple paths, subdomain routing, named routes, nested routes, index routes, route parameters, and route model binding.
- If available, use Boost's `search-docs` tool to use Folio to its full potential and help the user effectively.


<code-snippet name="Folio Middleware Example" lang="php">
use function Laravel\Folio\{name, middleware};

name('admin.products');
middleware(['auth', 'verified', 'can:manage-products']);
?>
</code-snippet>


=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.


=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure
- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.


=== livewire/core rules ===

## Livewire Core
- Use the `search-docs` tool to find exact version specific documentation for how to write Livewire & Livewire tests.
- Use the `php artisan make:livewire [Posts\CreatePost]` artisan command to create new components
- State should live on the server, with the UI reflecting it.
- All Livewire requests hit the Laravel backend, they're like regular HTTP requests. Always validate form data, and run authorization checks in Livewire actions.

## Livewire Best Practices
- Livewire components require a single root element.
- Use `wire:loading` and `wire:dirty` for delightful loading states.
- Add `wire:key` in loops:

    ```blade
    @foreach ($items as $item)
        <div wire:key="item-{{ $item->id }}">
            {{ $item->name }}
        </div>
    @endforeach
    ```

- Prefer lifecycle hooks like `mount()`, `updatedFoo()` for initialization and reactive side effects:

<code-snippet name="Lifecycle hook examples" lang="php">
    public function mount(User $user) { $this->user = $user; }
    public function updatedSearch() { $this->resetPage(); }
</code-snippet>


## Testing Livewire

<code-snippet name="Example Livewire component test" lang="php">
    Livewire::test(Counter::class)
        ->assertSet('count', 0)
        ->call('increment')
        ->assertSet('count', 1)
        ->assertSee(1)
        ->assertStatus(200);
</code-snippet>


    <code-snippet name="Testing a Livewire component exists within a page" lang="php">
        $this->get('/posts/create')
        ->assertSeeLivewire(CreatePost::class);
    </code-snippet>


=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.


=== pest/core rules ===

## Pest
### Testing
- If you need to verify a feature is working, write or update a Unit / Feature test.

### Pest Tests
- All tests must be written using Pest. Use `php artisan make:test --pest {name}`.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files - these are core to the application.
- Tests should test all of the happy paths, failure paths, and weird paths.
- Tests live in the `tests/Feature` and `tests/Unit` directories.
- Pest tests look and behave like this:
<code-snippet name="Basic Pest Test Example" lang="php">
it('is true', function () {
    expect(true)->toBeTrue();
});
</code-snippet>

### Running Tests
- Run the minimal number of tests using an appropriate filter before finalizing code edits.
- To run all tests: `php artisan test`.
- To run all tests in a file: `php artisan test tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --filter=testName` (recommended after making a change to a related file).
- When the tests relating to your changes are passing, ask the user if they would like to run the entire test suite to ensure everything is still passing.

### Pest Assertions
- When asserting status codes on a response, use the specific method like `assertForbidden` and `assertNotFound` instead of using `assertStatus(403)` or similar, e.g.:
<code-snippet name="Pest Example Asserting postJson Response" lang="php">
it('returns all', function () {
    $response = $this->postJson('/api/docs', []);

    $response->assertSuccessful();
});
</code-snippet>

### Mocking
- Mocking can be very helpful when appropriate.
- When mocking, you can use the `Pest\Laravel\mock` Pest function, but always import it via `use function Pest\Laravel\mock;` before using it. Alternatively, you can use `$this->mock()` if existing tests do.
- You can also create partial mocks using the same import or self method.

### Datasets
- Use datasets in Pest to simplify tests which have a lot of duplicated data. This is often the case when testing validation rules, so consider going with this solution when writing tests for validation rules.

<code-snippet name="Pest Dataset Example" lang="php">
it('has emails', function (string $email) {
    expect($email)->not->toBeEmpty();
})->with([
    'james' => 'james@laravel.com',
    'taylor' => 'taylor@laravel.com',
]);
</code-snippet>


=== pest/v4 rules ===

## Pest 4

- Pest v4 is a huge upgrade to Pest and offers: browser testing, smoke testing, visual regression testing, test sharding, and faster type coverage.
- Browser testing is incredibly powerful and useful for this project.
- Browser tests should live in `tests/Browser/`.
- Use the `search-docs` tool for detailed guidance on utilizing these features.

### Browser Testing
- You can use Laravel features like `Event::fake()`, `assertAuthenticated()`, and model factories within Pest v4 browser tests, as well as `RefreshDatabase` (when needed) to ensure a clean state for each test.
- Interact with the page (click, type, scroll, select, submit, drag-and-drop, touch gestures, etc.) when appropriate to complete the test.
- If requested, test on multiple browsers (Chrome, Firefox, Safari).
- If requested, test on different devices and viewports (like iPhone 14 Pro, tablets, or custom breakpoints).
- Switch color schemes (light/dark mode) when appropriate.
- Take screenshots or pause tests for debugging when appropriate.

### Example Tests

<code-snippet name="Pest Browser Test Example" lang="php">
it('may reset the password', function () {
    Notification::fake();

    $this->actingAs(User::factory()->create());

    $page = visit('/sign-in'); // Visit on a real browser...

    $page->assertSee('Sign In')
        ->assertNoJavascriptErrors() // or ->assertNoConsoleLogs()
        ->click('Forgot Password?')
        ->fill('email', 'nuno@laravel.com')
        ->click('Send Reset Link')
        ->assertSee('We have emailed your password reset link!')

    Notification::assertSent(ResetPassword::class);
});
</code-snippet>

<code-snippet name="Pest Smoke Testing Example" lang="php">
$pages = visit(['/', '/about', '/contact']);

$pages->assertNoJavascriptErrors()->assertNoConsoleLogs();
</code-snippet>
</laravel-boost-guidelines>
