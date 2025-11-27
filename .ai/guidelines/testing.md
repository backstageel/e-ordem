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
