<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load module routes if they exist
            if (file_exists(base_path('Modules'))) {
                $modulesPath = base_path('Modules');
                $modules = array_filter(glob($modulesPath.'/*'), 'is_dir');
                foreach ($modules as $module) {
                    $webRoutes = $module.'/routes/web.php';
                    if (file_exists($webRoutes)) {
                        Route::middleware('web')
                            ->group($webRoutes);
                    }
                }
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'member' => \App\Http\Middleware\MemberMiddleware::class,
            'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
            'mfa.verified' => \App\Http\Middleware\EnsureMfaIsVerified::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Add global middleware to ensure all routes start with role prefix
        $middleware->web(append: [
            \App\Http\Middleware\EnsureRolePrefix::class,
        ]);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        // Run document expiration check daily at 2 AM
        // $schedule->job(\App\Jobs\CheckDocumentExpiration::class)->dailyAt('02:00');

        // Member quota management - run daily at 3 AM
        // $schedule->job(\App\Jobs\ProcessQuotaOverdue::class)->dailyAt('03:00');
        // $schedule->command('members:update-quota-penalties')->dailyAt('03:30');

        // Send quota reminders - run daily at 8 AM
        // $schedule->job(\App\Jobs\ProcessQuotaReminders::class)->dailyAt('08:00');

        // Auto-suspension check - run daily at 4 AM
        // $schedule->job(\App\Jobs\ProcessAutoSuspension::class)->dailyAt('04:00');

        // Send all member alerts - run daily at 9 AM
        // $schedule->command('members:send-alerts')->dailyAt('09:00');

        // Check member compliance - run weekly on Monday at 6 AM
        // $schedule->command('members:check-compliance --send-alerts')->weeklyOn(1, '06:00');

        // Generate monthly quotas - run on the 1st of each month at 1 AM
        // $schedule->command('members:generate-quotas')->monthlyOn(1, '01:00');

        // Exam reminders - run daily at 10 AM
        // $schedule->job(\App\Jobs\Exam\SendExamRemindersJob::class)->dailyAt('10:00');
        // $schedule->command('exams:send-reminders')->dailyAt('10:00');

        // Check exam payments - run daily at 11 AM
        // $schedule->job(\App\Jobs\Exam\CheckExamPaymentsJob::class)->dailyAt('11:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Redirecionar para login quando GET é usado em /logout
        $exceptions->render(function (
            \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e,
            \Illuminate\Http\Request $request
        ) {
            if ($request->is('logout') || $request->routeIs('logout')) {
                return redirect()->route('login')
                    ->with('info', 'Por favor, use o botão de logout no menu.');
            }
        });

        // Redirecionar para login quando há erro 419 (CSRF token expired)
        $exceptions->render(function (
            \Symfony\Component\HttpKernel\Exception\HttpException $e,
            \Illuminate\Http\Request $request
        ) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Sua sessão expirou. Por favor, faça login novamente.',
                        'error' => 'token_mismatch',
                    ], 419);
                }

                return redirect()->route('login')
                    ->with('error', 'Sua sessão expirou. Por favor, faça login novamente.');
            }
        });
    })->create();
