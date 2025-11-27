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
