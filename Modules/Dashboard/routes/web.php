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

// Secretariat Dashboard routes
Route::middleware(['auth', 'verified', 'mfa.verified', 'role:secretariat'])
    ->prefix('secretariat/dashboard')
    ->as('secretariat.dashboard.')
    ->group(function () {
        Route::get('/', [SecretariatDashboardController::class, 'index'])->name('index');
    });

// Member Dashboard routes
Route::middleware(['auth', 'verified', 'mfa.verified', 'role:member'])
    ->prefix('member/dashboard')
    ->as('member.dashboard.')
    ->group(function () {
        Route::get('/', [MemberDashboardController::class, 'index'])->name('index');
    });
