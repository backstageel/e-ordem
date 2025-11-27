<?php

use Illuminate\Support\Facades\Route;
use Modules\Archive\Http\Controllers\ArchiveController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('archives', ArchiveController::class)->names('archive');
});
