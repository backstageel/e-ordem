<?php

use Illuminate\Support\Facades\Route;
use Modules\Residency\Http\Controllers\ResidencyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('residencies', ResidencyController::class)->names('residency');
});
