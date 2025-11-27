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
