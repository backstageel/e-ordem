<?php

use Illuminate\Support\Facades\Route;
use Modules\Card\Http\Controllers\CardController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('cards', CardController::class)->names('card');
});
