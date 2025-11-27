<?php

use App\Http\Controllers\Admin\AiChatController;
use App\Http\Controllers\Admin\ArchiveController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\MedicalSpecialityController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MemberQuotaController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ResidencyController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SystemConfigController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ExamApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'verified', 'mfa.verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard route - try module route first, fallback to legacy controller

    // Administrative and Audit Module routes
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/dashboard', [SystemConfigController::class, 'dashboard'])->name('dashboard');

        // System configs resource routes
        Route::resource('configs', SystemConfigController::class)->except(['show']);

        // Backup routes (non-RESTful)
        Route::get('/backups', [SystemConfigController::class, 'backups'])->name('backups');
        Route::post('/backups/settings', [SystemConfigController::class, 'updateBackupSettings'])->name('update-backup-settings');
        Route::post('/backups/create', [SystemConfigController::class, 'createBackup'])->name('create-backup');
        Route::post('/backups/restore', [SystemConfigController::class, 'restoreBackup'])->name('restore-backup');
    });

    // User Management routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::get('/{user}/change-password', [UserManagementController::class, 'changePassword'])->name('change-password');
        Route::put('/{user}/password', [UserManagementController::class, 'updatePassword'])->name('update-password');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });

    // Role & Permission Management routes
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // Additional role and permission routes
    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [RoleController::class, 'assignToRole'])->name('roles.assign-permissions');
    Route::delete('roles/{role}/permissions', [RoleController::class, 'removeFromRole'])->name('roles.remove-permissions');

    // Audit Log routes
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', [AuditController::class, 'index'])->name('index');
        Route::get('/statistics', [AuditController::class, 'statistics'])->name('statistics');
        Route::get('/export', [AuditController::class, 'export'])->name('export');
        Route::get('/{log}', [AuditController::class, 'show'])->name('show');
    });

    // Reports routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/operational', [ReportController::class, 'operational'])->name('operational');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/custom', [ReportController::class, 'custom'])->name('custom');
        Route::get('/statistics', [ReportController::class, 'statistics'])->name('statistics');
    });

    // Notifications routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/create', [NotificationController::class, 'create'])->name('create');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
        Route::get('/statistics', [NotificationController::class, 'statistics'])->name('statistics');
        Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
        Route::patch('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::patch('/{notification}/unread', [NotificationController::class, 'markAsUnread'])->name('mark-unread');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });

    // Archive routes
    Route::prefix('archives')->name('archives.')->group(function () {
        Route::get('/', [ArchiveController::class, 'index'])->name('index');
        Route::get('/statistics', [ArchiveController::class, 'statistics'])->name('statistics');
        Route::get('/show', [ArchiveController::class, 'show'])->name('show');
        Route::post('/restore', [ArchiveController::class, 'restore'])->name('restore');
        Route::delete('/force-delete', [ArchiveController::class, 'forceDelete'])->name('force-delete');
        Route::get('/export', [ArchiveController::class, 'export'])->name('export');
    });

    // AI Chat routes
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/', [AiChatController::class, 'index'])->name('index');
        Route::post('/chat', [AiChatController::class, 'chat'])->name('chat');
    });

    // Cards management routes
    Route::prefix('cards')->name('cards.')->group(function () {
        Route::get('/', [CardController::class, 'index'])->name('index');
        Route::get('/create', [CardController::class, 'create'])->name('create');
        Route::post('/', [CardController::class, 'store'])->name('store');
        Route::get('/{id}', [CardController::class, 'show'])->name('show');
        Route::post('/{id}/status', [CardController::class, 'updateStatus'])->name('update-status');
        Route::get('/history', [CardController::class, 'history'])->name('history');
    });

    // Registration management routes moved to Modules/Registration/routes/web.php

    // Document management routes
    Route::prefix('documents')->name('documents.')->group(function () {
        // Documents resource routes
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::get('/search-persons', [DocumentController::class, 'searchPersons'])->name('search-persons');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');

        // Document validation and management routes (non-RESTful)
        Route::get('/{document}/validate', [DocumentController::class, 'showValidationForm'])->name('validate-form');
        Route::post('/{document}/validate', [DocumentController::class, 'validateDocument'])->name('validate');
        Route::get('/{document}/view', [DocumentController::class, 'view'])->name('view');
        Route::get('/{document}/serve', [DocumentController::class, 'serve'])->name('serve')->middleware('signed');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::get('/{document}/download-translation', [DocumentController::class, 'downloadTranslation'])->name('download-translation');
        Route::get('/member/{member}/checklist', [DocumentController::class, 'showChecklist'])->name('checklist');
        Route::get('/status/check', [DocumentController::class, 'checkDocumentsStatus'])->name('check-status');
        Route::get('/export/xlsx', [DocumentController::class, 'exportXlsx'])->name('export.xlsx');
        Route::get('/export/pdf', [DocumentController::class, 'exportPdf'])->name('export.pdf');
    });

    // Member management routes
    Route::prefix('members')->name('members.')->group(function () {
        // Static routes must come before resource routes to avoid route conflicts
        Route::get('/report', [MemberController::class, 'report'])->name('report');
        Route::get('/export', [MemberController::class, 'export'])->name('export');

        // Members resource routes
        Route::resource('/', MemberController::class)->parameters(['' => 'member']);

        // Member management routes (non-RESTful)
        Route::get('/{member}/status', [MemberController::class, 'showStatusForm'])->name('status');
        Route::patch('/{member}/status', [MemberController::class, 'updateStatus'])->name('update-status');
        Route::post('/{member}/documents', [MemberController::class, 'uploadDocuments'])->name('upload-documents');
        Route::patch('/{member}/quota', [MemberController::class, 'updateQuotaStatus'])->name('update-quota');
        Route::get('/{member}/card', [MemberController::class, 'showCard'])->name('card');
        Route::post('/{member}/card', [MemberController::class, 'generateCard'])->name('generate-card');
        Route::get('/{member}/payments/create', [MemberController::class, 'createPayment'])->name('create-payment');
        Route::post('/check-pending-documents', [MemberController::class, 'checkPendingDocuments'])->name('check-pending-documents');

        // Quota management routes (nested)
        Route::prefix('{member}/quotas')->name('quotas.')->group(function () {
            Route::get('/', [MemberQuotaController::class, 'index'])->name('index');
            Route::get('/create', [MemberQuotaController::class, 'create'])->name('create');
            Route::post('/', [MemberQuotaController::class, 'store'])->name('store');
            Route::get('/{quota}/edit', [MemberQuotaController::class, 'edit'])->name('edit');
            Route::put('/{quota}', [MemberQuotaController::class, 'update'])->name('update');
            Route::post('/{quota}/mark-paid', [MemberQuotaController::class, 'markAsPaid'])->name('mark-paid');
            Route::delete('/{quota}', [MemberQuotaController::class, 'destroy'])->name('destroy');
        });
    });

    // Exam management routes
    Route::prefix('exams')->name('exams.')->group(function () {
        // Exam management routes (non-RESTful) - must be before resource routes
        Route::get('/history', [ExamController::class, 'history'])->name('history');

        // Exams resource routes
        Route::resource('/', ExamController::class)->parameters(['' => 'exam']);
        Route::get('/{exam}/schedule', [ExamController::class, 'schedule'])->name('schedule');
        Route::post('/{exam}/schedule', [ExamController::class, 'saveSchedule'])->name('save-schedule');
        Route::get('/{exam}/candidates', [ExamController::class, 'candidates'])->name('candidates');
        Route::get('/{exam}/upload-results', [ExamController::class, 'uploadResults'])->name('upload-results');
        Route::post('/{exam}/process-results', [ExamController::class, 'processResults'])->name('process-results');
        Route::get('/{exam}/generate-lists', [ExamController::class, 'generateLists'])->name('generate-lists');
        Route::post('/{exam}/export-lists', [ExamController::class, 'exportLists'])->name('export-lists');
        Route::post('/{exam}/preview-list', [ExamController::class, 'previewList'])->name('preview-list');
        Route::post('/{exam}/notify-results', [ExamController::class, 'notifyResults'])->name('notify-results');
        Route::post('/{exam}/generate-certificates', [ExamController::class, 'generateCertificates'])->name('generate-certificates');
        Route::get('/{exam}/statistics', [ExamController::class, 'statistics'])->name('statistics');
        Route::patch('/{exam}/archive', [ExamController::class, 'archive'])->name('archive');
        Route::get('/{exam}/decisions', [ExamController::class, 'decisions'])->name('decisions');
        Route::get('/{exam}/appeals', [ExamController::class, 'appeals'])->name('appeals');
        Route::post('/applications/{application}/status', [ExamController::class, 'updateApplicationStatus'])->name('applications.update-status');
        Route::post('/appeals/{appeal}/process', [ExamController::class, 'processAppeal'])->name('appeals.process');
    });

    // Exam application routes
    Route::prefix('exam-applications')->name('exam-applications.')->group(function () {
        // Exam applications resource routes
        Route::resource('/', ExamApplicationController::class)->parameters(['' => 'application']);
    });

    // Payment management routes
    Route::prefix('payments')->name('payments.')->group(function () {
        // Payment management routes (non-RESTful) - must be before resource routes
        Route::get('/settings', [PaymentController::class, 'settings'])->name('settings');
        Route::post('/settings', [PaymentController::class, 'updateSettings'])->name('update-settings');
        Route::get('/export', [PaymentController::class, 'export'])->name('export');
        Route::get('/print-report', [PaymentController::class, 'printReport'])->name('print-report');
        Route::post('/search-members', [PaymentController::class, 'searchMembers'])->name('search-members');

        // Payments resource routes
        Route::resource('/', PaymentController::class)->parameters(['' => 'id']);

        // Payment management routes (non-RESTful)
        Route::get('/{id}/download-receipt', [PaymentController::class, 'downloadReceipt'])->name('download-receipt');
        Route::get('/{id}/send-receipt', [PaymentController::class, 'sendReceiptByEmail'])->name('send-receipt');
    });

    // Medical Residency management routes
    Route::prefix('residence')->name('residence.')->group(function () {
        // Programs routes
        Route::prefix('programs')->name('programs.')->group(function () {
            Route::get('/', [ResidencyController::class, 'indexPrograms'])->name('index');
            Route::get('/create', [ResidencyController::class, 'createProgram'])->name('create');
            Route::post('/', [ResidencyController::class, 'storeProgram'])->name('store');
            Route::get('/{id}', [ResidencyController::class, 'showProgram'])->name('show');
            Route::get('/{id}/edit', [ResidencyController::class, 'editProgram'])->name('edit');
            Route::put('/{id}', [ResidencyController::class, 'updateProgram'])->name('update');
            Route::delete('/{id}', [ResidencyController::class, 'destroyProgram'])->name('destroy');
        });

        // Residents routes
        Route::prefix('residents')->name('residents.')->group(function () {
            Route::get('/', [ResidencyController::class, 'indexResidents'])->name('index');
            Route::get('/create', [ResidencyController::class, 'createResident'])->name('create');
            Route::post('/', [ResidencyController::class, 'storeResident'])->name('store');
            Route::get('/{id}', [ResidencyController::class, 'showResident'])->name('show');
            Route::get('/{id}/edit', [ResidencyController::class, 'editResident'])->name('edit');
            Route::put('/{id}', [ResidencyController::class, 'updateResident'])->name('update');
            Route::delete('/{id}', [ResidencyController::class, 'destroyResident'])->name('destroy');
        });

        // Applications routes
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ResidencyController::class, 'indexApplications'])->name('index');
            Route::get('/create', [ResidencyController::class, 'createApplication'])->name('create');
            Route::post('/', [ResidencyController::class, 'storeApplication'])->name('store');
            Route::get('/{id}', [ResidencyController::class, 'showApplication'])->name('show');
            Route::get('/{id}/edit', [ResidencyController::class, 'editApplication'])->name('edit');
            Route::put('/{id}', [ResidencyController::class, 'updateApplication'])->name('update');
            Route::delete('/{id}', [ResidencyController::class, 'destroyApplication'])->name('destroy');
            Route::post('/{id}/approve', [ResidencyController::class, 'approveApplication'])->name('approve');
            Route::post('/{id}/reject', [ResidencyController::class, 'rejectApplication'])->name('reject');
        });

        // Training locations routes
        Route::prefix('locations')->name('locations.')->group(function () {
            Route::get('/', [ResidencyController::class, 'indexLocations'])->name('index');
            Route::get('/create', [ResidencyController::class, 'createLocation'])->name('create');
            Route::post('/', [ResidencyController::class, 'storeLocation'])->name('store');
            Route::get('/{id}', [ResidencyController::class, 'showLocation'])->name('show');
            Route::get('/{id}/edit', [ResidencyController::class, 'editLocation'])->name('edit');
            Route::put('/{id}', [ResidencyController::class, 'updateLocation'])->name('update');
            Route::delete('/{id}', [ResidencyController::class, 'destroyLocation'])->name('destroy');
            Route::post('/assign', [ResidencyController::class, 'assignLocations'])->name('assign');
        });

        // Evaluations routes
        Route::prefix('evaluations')->name('evaluations.')->group(function () {
            Route::get('/', [ResidencyController::class, 'indexEvaluations'])->name('index');
            Route::get('/create', [ResidencyController::class, 'createEvaluation'])->name('create');
            Route::post('/', [ResidencyController::class, 'storeEvaluation'])->name('store');
            Route::get('/{id}', [ResidencyController::class, 'showEvaluation'])->name('show');
            Route::get('/{id}/edit', [ResidencyController::class, 'editEvaluation'])->name('edit');
            Route::put('/{id}', [ResidencyController::class, 'updateEvaluation'])->name('update');
            Route::delete('/{id}', [ResidencyController::class, 'destroyEvaluation'])->name('destroy');
        });

        // Completions routes
        Route::prefix('completions')->name('completions.')->group(function () {
            Route::get('/', [ResidencyController::class, 'indexCompletions'])->name('index');
            Route::get('/create', [ResidencyController::class, 'createCompletion'])->name('create');
            Route::post('/', [ResidencyController::class, 'storeCompletion'])->name('store');
            Route::get('/{id}', [ResidencyController::class, 'showCompletion'])->name('show');
            Route::get('/{id}/edit', [ResidencyController::class, 'editCompletion'])->name('edit');
            Route::put('/{id}', [ResidencyController::class, 'updateCompletion'])->name('update');
            Route::delete('/{id}', [ResidencyController::class, 'destroyCompletion'])->name('destroy');
            Route::get('/{id}/certificate', [ResidencyController::class, 'generateCertificate'])->name('certificate');
        });

        // Exams integration routes
        Route::prefix('exams')->name('exams.')->group(function () {
            Route::get('/', [ResidencyController::class, 'indexExams'])->name('index');
            Route::get('/create', [ResidencyController::class, 'createExam'])->name('create');
            Route::post('/', [ResidencyController::class, 'storeExam'])->name('store');
            Route::get('/{id}', [ResidencyController::class, 'showExam'])->name('show');
            Route::get('/{id}/edit', [ResidencyController::class, 'editExam'])->name('edit');
            Route::put('/{id}', [ResidencyController::class, 'updateExam'])->name('update');
            Route::delete('/{id}', [ResidencyController::class, 'destroyExam'])->name('destroy');
        });

        // Reports routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ResidencyController::class, 'indexReports'])->name('index');
            Route::get('/generate', [ResidencyController::class, 'generateReport'])->name('generate');
            Route::get('/export', [ResidencyController::class, 'exportReport'])->name('export');
        });

        // History routes
        Route::prefix('history')->name('history.')->group(function () {
            Route::get('/', [ResidencyController::class, 'indexHistory'])->name('index');
            Route::get('/{id}', [ResidencyController::class, 'showHistory'])->name('show');
        });
    });

    // Medical Specialities routes
    Route::prefix('medical-specialities')->name('medical-specialities.')->group(function () {
        Route::get('/', [MedicalSpecialityController::class, 'index'])->name('index');
    });
});
