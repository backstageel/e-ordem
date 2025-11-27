<?php

use Illuminate\Support\Facades\Route;
use Modules\Archive\Http\Controllers\ArchiveController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('archives', ArchiveController::class)->names('archive');
});
