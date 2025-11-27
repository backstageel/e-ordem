<?php

use Illuminate\Support\Facades\Route;
use Modules\Registration\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use Modules\Registration\Http\Controllers\Admin\RegistrationTypeSelectionController as AdminRegistrationTypeSelectionController;
use Modules\Registration\Http\Controllers\Guest\RegistrationController as GuestRegistrationController;
use Modules\Registration\Http\Controllers\Guest\RegistrationTypeSelectionController;
use Modules\Registration\Http\Controllers\Member\RegistrationController as MemberRegistrationController;

// Admin routes
Route::middleware(['auth', 'verified', 'mfa.verified', 'role:admin|super-admin|secretariat'])
    ->prefix('admin/registrations')
    ->name('admin.registrations.')
    ->group(function () {
        Route::get('/', [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/export', [AdminRegistrationController::class, 'export'])->name('export');

        // Type selection page for admin/secretariat
        Route::get('/type-selection', [AdminRegistrationTypeSelectionController::class, 'index'])->name('type-selection');

        // Wizard routes for admin (separate from guest wizards)
        Route::get('/certification/wizard', function () {
            return view('registration::admin.registrations.certification.wizard');
        })->name('certification.wizard');

        Route::get('/provisional/wizard', function () {
            return view('registration::admin.registrations.provisional.wizard');
        })->name('provisional.wizard');

        Route::get('/effective/wizard', function () {
            return view('registration::admin.registrations.effective.wizard');
        })->name('effective.wizard');

        // Legacy wizard route redirects to type selection
        Route::get('/wizard', function () {
            return redirect()->route('admin.registrations.type-selection');
        })->name('wizard');

        Route::get('/{registration}/edit-wizard', function ($registration) {
            // TODO: Implement edit wizard for admin when needed
            return redirect()->route('admin.registrations.show', $registration);
        })->name('edit-wizard');

        // Creation/updates are handled by wizards; legacy store removed
        Route::get('/{registration}', [AdminRegistrationController::class, 'show'])->name('show');
        Route::post('/{registration}/approve', [AdminRegistrationController::class, 'approve'])->name('approve');
        Route::post('/{registration}/reject', [AdminRegistrationController::class, 'reject'])->name('reject');
        Route::post('/{registration}/validate', [AdminRegistrationController::class, 'validateRegistration'])->name('validate');

        // Detail actions
        Route::get('/{registration}/export-pdf', [AdminRegistrationController::class, 'exportPdf'])->name('export-pdf');
        Route::post('/{registration}/documents/{document}/approve', [AdminRegistrationController::class, 'approveDocument'])->name('documents.approve');
        Route::post('/{registration}/documents/{document}/reject', [AdminRegistrationController::class, 'rejectDocument'])->name('documents.reject');
        Route::post('/{registration}/documents/approve-all', [AdminRegistrationController::class, 'approveAllDocuments'])->name('documents.approve-all');
        Route::post('/{registration}/documents/reject-all', [AdminRegistrationController::class, 'rejectAllDocuments'])->name('documents.reject-all');
        Route::post('/{registration}/validate-payment', [AdminRegistrationController::class, 'validatePayment'])->name('validate-payment');
        Route::delete('/{registration}', [AdminRegistrationController::class, 'destroy'])->name('destroy');
    });

// Member routes
Route::middleware(['auth', 'verified', 'mfa.verified', 'role:member'])
    ->prefix('member/registrations')
    ->name('member.registrations.')
    ->group(function () {
        Route::get('/', [MemberRegistrationController::class, 'index'])->name('index');
        Route::get('/create', [MemberRegistrationController::class, 'create'])->name('create');
        Route::post('/', [MemberRegistrationController::class, 'store'])->name('store');
        Route::get('/{registration}', [MemberRegistrationController::class, 'show'])->name('show');
        Route::get('/{registration}/renew', [MemberRegistrationController::class, 'renew'])->name('renew');
        Route::post('/{registration}/renewal', [MemberRegistrationController::class, 'storeRenewal'])->name('store-renewal');
    });

// Guest routes
Route::prefix('guest/registrations')
    ->name('guest.registrations.')
    ->group(function () {
        // Type selection page (NEW - shows 3 main options)
        Route::get('/type-selection', [RegistrationTypeSelectionController::class, 'index'])->name('type-selection');

        // Certification wizard
        Route::get('/certification/wizard', function () {
            return view('registration::guest.registrations.certification.wizard');
        })->name('certification.wizard');

        // Provisional wizard
        Route::get('/provisional/wizard', function () {
            return view('registration::guest.registrations.provisional.wizard');
        })->name('provisional.wizard');

        // Effective wizard
        Route::get('/effective/wizard', function () {
            return view('registration::guest.registrations.effective.wizard');
        })->name('effective.wizard');

        // Legacy: redirect to type selection
        Route::get('/type', function () {
            return redirect()->route('guest.registrations.type-selection');
        })->name('type');

        // Certification wizard route
        Route::get('/certification/wizard', function () {
            return view('registration::guest.registrations.certification.wizard');
        })->name('certification.wizard');

        // Provisional wizard route
        Route::get('/provisional/wizard', function () {
            return view('registration::guest.registrations.provisional.wizard');
        })->name('provisional.wizard');

        // Effective wizard route
        Route::get('/effective/wizard', function () {
            return view('registration::guest.registrations.effective.wizard');
        })->name('effective.wizard');

        // Success page
        Route::get('/success', [GuestRegistrationController::class, 'success'])->name('success');

        // Registration status checking
        Route::get('/check-status', [GuestRegistrationController::class, 'checkStatus'])->name('check-status');
        Route::post('/show-status', [GuestRegistrationController::class, 'showStatus'])->name('show-status');

        // Legacy: redirect to type selection
        Route::get('/select-type/{category}', function () {
            return redirect()->route('guest.registrations.type-selection');
        })->name('select-type');

        // Legacy wizard route (redirects to type selection)
        Route::get('/wizard', function () {
            return redirect()->route('guest.registrations.type-selection');
        })->name('wizard');

        // Payment routes
        Route::get('/payment/{registration}/{payment}', function ($registration, $payment) {
            return view('pages.guest.registrations.payment.[registration].[payment]', compact('registration', 'payment'));
        })->name('payment');
    });
