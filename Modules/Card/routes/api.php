<?php

use Illuminate\Support\Facades\Route;
use Modules\Card\Http\Controllers\CardController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('cards', CardController::class)->names('card');
});
