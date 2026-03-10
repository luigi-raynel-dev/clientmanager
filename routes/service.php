<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/services', [ServiceController::class, 'index'])
        ->name('services.index');

    Route::middleware(['admin'])->group(function () {
        Route::get('/services/create', function () {
            return Inertia::render('Services/Create');
        })
            ->name('services.create');

        Route::post('/services', [ServiceController::class, 'store'])
            ->name('services.store');

        // Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])
        //     ->name('services.edit');

        // Route::put('/services/{id}', [ServiceController::class, 'update'])
        //     ->name('services.update');

        // Route::delete('/services/{id}', [ServiceController::class, 'destroy'])
        //     ->name('services.destroy');
    });
});
