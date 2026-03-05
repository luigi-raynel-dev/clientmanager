<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])
        ->name('clients.index');

    Route::get('/clients/create', function () {
        return Inertia::render('Clients/Create');
    })
        ->name('clients.create');

    // Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])
    //     ->name('clients.edit');

    Route::post('/clients', [ClientController::class, 'store'])
        ->name('clients.store');

    // Route::put('/clients/{id}', [ClientController::class, 'update'])
    //     ->name('clients.update');

    // Route::delete('/clients/{id}', [ClientController::class, 'destroy'])
    //     ->name('clients.destroy');
});
