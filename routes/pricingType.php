<?php

use App\Http\Controllers\PricingTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::post('/pricing-types', [PricingTypeController::class, 'store'])
            ->name('pricingTypes.store');
    });
});
