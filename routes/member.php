<?php

use App\Http\Controllers\Member\CardController;
use App\Http\Controllers\Member\DocumentController;
use App\Http\Controllers\Member\ExamController;
use App\Http\Controllers\Member\NotificationController;
use App\Http\Controllers\Member\PaymentController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\QuotaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'mfa.verified', 'member'])->prefix('member')->name('member.')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Registrations routes moved to Modules/Registration/routes/web.php

    // Documents routes
    Route::prefix('documents')->name('documents.')->group(function () {
        // Document management routes (non-RESTful) - Must be BEFORE resource routes
        Route::get('/pending', [DocumentController::class, 'pending'])->name('pending');

        // Documents resource routes
        Route::resource('/', DocumentController::class)->parameters(['' => 'document']);

        // Additional document routes (non-RESTful)
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::get('/{document}/download-translation', [DocumentController::class, 'downloadTranslation'])->name('download-translation');
    });

    // Exams routes
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::get('/available', [ExamController::class, 'available'])->name('available');
        Route::get('/apply/{exam}', [ExamController::class, 'apply'])->name('apply');
        Route::post('/apply/{exam}', [ExamController::class, 'store'])->name('store');
        Route::get('/{application}', [ExamController::class, 'show'])->name('show');
        Route::get('/{application}/schedule', [ExamController::class, 'schedule'])->name('schedule');
        Route::post('/{application}/schedule', [ExamController::class, 'storeSchedule'])->name('store-schedule');
        Route::get('/{application}/results', [ExamController::class, 'results'])->name('results');
        Route::get('/{application}/appeal', [ExamController::class, 'appeal'])->name('appeal');
        Route::post('/{application}/appeal', [ExamController::class, 'storeAppeal'])->name('store-appeal');
    });

    // Payments routes
    Route::prefix('payments')->name('payments.')->group(function () {
        // Payment management routes (non-RESTful) - must be before resource routes
        Route::get('/receipts', [PaymentController::class, 'receipts'])->name('receipts');

        // Payments resource routes
        Route::resource('/', PaymentController::class)->parameters(['' => 'id']);

        // Payment management routes (non-RESTful)
        Route::get('/{id}/download', [PaymentController::class, 'downloadReceipt'])->name('download');
        Route::get('/{id}/email', [PaymentController::class, 'emailReceipt'])->name('email');
    });

    // Quotas routes
    Route::prefix('quotas')->name('quotas.')->group(function () {
        Route::get('/', [QuotaController::class, 'index'])->name('index');
        Route::get('/{quota}', [QuotaController::class, 'show'])->name('show');
    });

    // Digital card routes
    Route::prefix('card')->name('card.')->group(function () {
        Route::get('/', [CardController::class, 'index'])->name('index');
        Route::post('/generate', [CardController::class, 'generate'])->name('generate');
        Route::get('/download', [CardController::class, 'download'])->name('download');
        Route::get('/email', [CardController::class, 'email'])->name('email');
        Route::get('/print', [CardController::class, 'print'])->name('print');
    });

    // Notifications routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
    });

    // Support route (simple placeholder for now)
    Route::get('/support', function () {
        return view('member.support.index');
    })->name('support');
});
