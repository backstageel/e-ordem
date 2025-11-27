# Development Guidelines for Laravel Bootstrap Starter Kit

This document provides guidelines and instructions for developing and maintaining this Laravel Bootstrap Starter Kit project.

## Build/Configuration Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL or another database supported by Laravel

### Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Set up environment variables:
   ```bash
   cp .env.example .env
   sail artisan key:generate
   ```

5. Configure your database in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

6. Run migrations:
   ```bash
   sail artisan migrate
   ```

7. Start the development server:
   ```bash
   # Using the custom dev script that runs server, queue, logs, and Vite concurrently
   composer dev
   
   # Or run individual components
   sail up
   npm run dev
   ```

## Testing Information

### Test Configuration

The project uses PHPUnit for testing. The configuration is in `phpunit.xml` and includes:
- Separate test suites for Unit and Feature tests
- A dedicated testing database (configured as `testing` in `phpunit.xml`)
- In-memory drivers for cache, mail, queue, and session during testing

### Running Tests

To run all tests:
```bash
sail artisan test
```

To run a specific test:
```bash
sail artisan test --filter=TestName
```

To run a specific test suite:
```bash
sail artisan test --testsuite=Unit
sail artisan test --testsuite=Feature
```

### Creating New Tests

#### Unit Tests

Unit tests should be placed in the `tests/Unit` directory and extend `PHPUnit\Framework\TestCase`:

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleUnitTest extends TestCase
{
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
```

#### Feature Tests

Feature tests should be placed in the `tests/Feature` directory and extend `Tests\TestCase`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleFeatureTest extends TestCase
{
    public function test_example(): void
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }
}
```

## Additional Development Information

### Code Style

The project follows the Laravel coding style:
- 4 spaces for indentation
- UTF-8 encoding
- LF line endings
- Trimming trailing whitespace

Laravel Pint is included for code style fixing. Run it with:
```bash
sail pint
```

### Project Structure

This project follows the standard Laravel structure with some additional components:

- Laravel Modules: The project uses the `nwidart/laravel-modules` package for modular development.
- Laravel Livewire: Used for dynamic frontend components with PowerGrid for data tables.
- Laravel Breeze: Provides authentication scaffolding.
- Laravel Sanctum: Used for API authentication.

### Development Tools

- Laravel Debugbar: Provides debugging information during development.
- Laravel Telescope: Provides insights into the request/response cycle and more.
- Laravel Pail: Real-time log viewer.

### Docker Development Environment

The project includes Laravel Sail for Docker-based development:

```bash
# Start the Docker environment
./vendor/bin/sail up

# Run commands within the Docker environment
./vendor/bin/sail artisan migrate
./vendor/bin/sail composer install
./vendor/bin/sail npm run dev
```

### Useful Commands

- `sail artisan route:list`: List all registered routes
- `sail artisan make:controller ControllerName`: Create a new controller
- `sail artisan make:model ModelName -m`: Create a new model with migration
- `sail artisan make:migration create_table_name`: Create a new migration
- `sail artisan module:make ModuleName`: Create a new module
- `sail artisan module:make-controller ControllerName ModuleName`: Create a controller in a module

### Important Notes
- Always use blade components for layout. All layouts must be based on <x-layouts.app>
- we use Resource routes, using Route::resource, unless it is a single route to be defined to the controller or the path is not resourcefull.
- Never use fillable in models. Use guarded=false.
- This project uses Bootstrap, never ever tailwind css.

### Enum Conventions
**All Enums in this application MUST follow this exact pattern:**

```php
enum RegistrationStatus: string
{
    case APPROVED = 'approved';
    case PENDING = 'pending';
    
    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Aprovado',
            self::PENDING => 'Pendente',
        };
    }
    
    public function color(): string
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::PENDING => 'warning',
        };
    }
}
```

**Enum Rules:**
- **MUST have `label()` method** for display text
- **MUST have `color()` method** for UI badge colors  
- **NEVER use verbose names** like `getDisplayName()` or `getBadgeColor()`
- **API must be consistent** across all Enums
- Use `label()` for user-facing text
- Use `color()` for CSS classes and UI styling

**Usage in Models:**
```php
protected $casts = [
    'status' => RegistrationStatus::class,
    'priority_level' => RegistrationPriority::class,
];
```

**Usage in Views:**
```blade
<span class="badge badge-{{ $registration->status->color() }}">
    {{ $registration->status->label() }}
</span>
```

## Deployment Guidelines

### Production Environment Requirements

- PHP 8.2 or higher
- Nginx or Apache web server
- PostgreSQL 14 or higher
- Redis for caching and queues
- Supervisor for queue workers
- SSL certificate for HTTPS

### Deployment Process

1. Set up the production server with all required software.

2. Clone the repository to the production server:
   ```bash
   git clone <repository-url> /var/www/production
   cd /var/www/production
   ```

3. Install dependencies:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```

4. Set up environment variables:
   ```bash
   cp .env.example .env
   sail artisan key:generate
   ```

5. Configure the `.env` file for production:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-production-domain.com
   
   DB_CONNECTION=pgsql
   DB_HOST=your-production-db-host
   DB_PORT=5432
   DB_DATABASE=your_production_database
   DB_USERNAME=your_production_username
   DB_PASSWORD=your_production_password
   
   CACHE_DRIVER=redis
   SESSION_DRIVER=redis
   QUEUE_CONNECTION=redis
   
   REDIS_HOST=your-redis-host
   REDIS_PASSWORD=your-redis-password
   REDIS_PORT=6379
   ```

6. Run migrations:
   ```bash
   sail artisan migrate --force
   ```

7. Set up file permissions:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

8. Configure Nginx:
   ```nginx
   server {
       listen 80;
       server_name your-production-domain.com;
       return 301 https://$server_name$request_uri;
   }
   
   server {
       listen 443 ssl;
       server_name your-production-domain.com;
       
       ssl_certificate /path/to/certificate.crt;
       ssl_certificate_key /path/to/private.key;
       
       root /var/www/production/public;
       
       index index.php;
       
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       
       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
       }
       
       location ~ /\.ht {
           deny all;
       }
       
       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```

9. Set up Supervisor for queue workers:
   ```
   [program:laravel-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=sail artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
   autostart=true
   autorestart=true
   user=www-data
   numprocs=8
   redirect_stderr=true
   stdout_logfile=/var/www/production/storage/logs/worker.log
   stopwaitsecs=3600
   ```

10. Restart Nginx and Supervisor:
    ```bash
    sudo service nginx restart
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl start laravel-worker:*
    ```

### Deployment Automation

For automated deployments, you can use Laravel Deployer:

1. Install Laravel Deployer:
   ```bash
   composer require deployer/deployer --dev
   ```

2. Create a deployment configuration file:
   ```bash
   sail artisan deploy:init
   ```

3. Configure the deployment settings in `deploy.php`.

4. Deploy to production:
   ```bash
   sail artisan deploy production
   ```

## Performance Optimization

### Database Optimization

- Use database indexes for frequently queried columns.
- Use eager loading to avoid N+1 query problems:
  ```php
  // Instead of this (causes N+1 problem)
  $users = User::all();
  foreach ($users as $user) {
      echo $user->profile->name;
  }
  
  // Do this
  $users = User::with('profile')->get();
  foreach ($users as $user) {
      echo $user->profile->name;
  }
  ```

- Use chunking for processing large datasets:
  ```php
  User::chunk(100, function ($users) {
      foreach ($users as $user) {
          // Process user
      }
  });
  ```

### Caching Strategies

- Cache frequently accessed data:
  ```php
  $value = Cache::remember('users', 3600, function () {
      return User::all();
  });
  ```

- Use model caching for database queries:
  ```php
  // Install the package
  composer require gloudemans/laravel-model-caching
  
  // Use in models
  use GloudemansSharp\LaravelModelCaching\Traits\Cachable;
  
  class User extends Model
  {
      use Cachable;
      // ...
  }
  ```

- Cache route responses for static pages:
  ```php
  Route::get('/home', [HomeController::class, 'index'])->middleware('cache.headers:public;max_age=3600');
  ```

### Frontend Optimization

- Minimize and compress CSS and JavaScript files:
  ```bash
  npm run build
  ```

- Use lazy loading for images:
  ```html
  <img loading="lazy" src="image.jpg" alt="Description">
  ```

- Implement content delivery network (CDN) for static assets.

## Security Best Practices

### Authentication and Authorization

- Use Laravel's built-in authentication system.
- Implement two-factor authentication for sensitive areas.
- Use Laravel's authorization policies for access control.

### Data Protection

- Always validate user input:
  ```php
  $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
  ]);
  ```

- Use prepared statements (Laravel's query builder and Eloquent do this automatically).
- Encrypt sensitive data:
  ```php
  use Illuminate\Support\Facades\Crypt;
  
  $encrypted = Crypt::encrypt($value);
  $decrypted = Crypt::decrypt($encrypted);
  ```

### CSRF Protection

- Always include CSRF tokens in forms:
  ```html
  <form method="POST" action="/profile">
      @csrf
      <!-- Form fields -->
  </form>
  ```

### XSS Prevention

- Always escape output:
  ```php
  {{ $userInput }} <!-- Automatically escaped -->
  {!! $trustedHtml !!} <!-- Not escaped, use only for trusted content -->
  ```

### Security Headers

- Configure security headers in your web server or using middleware:
  ```php
  // In App\Http\Middleware\SecurityHeaders.php
  public function handle($request, Closure $next)
  {
      $response = $next($request);
      $response->headers->set('X-Content-Type-Options', 'nosniff');
      $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
      $response->headers->set('X-XSS-Protection', '1; mode=block');
      return $response;
  }
  ```

## Troubleshooting Common Issues

### Installation Problems

- **Composer memory limit error**: Increase memory limit in php.ini or use:
  ```bash
  COMPOSER_MEMORY_LIMIT=-1 composer install
  ```

- **Node.js version issues**: Use NVM to manage Node.js versions:
  ```bash
  nvm install 16
  nvm use 16
  ```

### Runtime Errors

- **500 Server Error**: Check Laravel logs in `storage/logs/laravel.log`.
- **White screen of death**: Enable debugging in `.env` file:
  ```
  APP_DEBUG=true
  ```

- **Database connection issues**: Verify database credentials in `.env` file.

### Performance Issues

- **Slow queries**: Use Laravel Debugbar to identify slow queries.
- **High memory usage**: Profile your application using Blackfire or Xdebug.
- **Slow page loads**: Use browser developer tools to identify bottlenecks.

## Contributing Guidelines

### Code Contribution Process

1. Fork the repository.
2. Create a feature branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. Make your changes and commit them:
   ```bash
   git commit -m "Add your feature description"
   ```

4. Push to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```

5. Create a pull request to the main repository.

### Coding Standards

- Follow PSR-12 coding standards.
- Write descriptive commit messages.
- Include tests for new features.
- Update documentation for significant changes.

### Pull Request Guidelines

- Keep pull requests focused on a single feature or bug fix.
- Include a clear description of the changes.
- Reference any related issues.
- Ensure all tests pass before submitting.

## Conclusion

This document provides a comprehensive guide for developing and maintaining this Laravel Bootstrap Starter Kit. Following these guidelines will help ensure consistency, quality, and maintainability of the codebase.

For any questions or clarifications, please contact the project maintainers.
