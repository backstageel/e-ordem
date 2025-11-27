<?php

use Illuminate\Support\Facades\Route;
use Modules\Residency\Http\Controllers\ResidencyController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('residencies', ResidencyController::class)->names('residency');
});
