<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/create', function () {
        return Inertia::render('Users/Create');
    })
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::put('/users/{id}', [UserController::class, 'update'])
        ->name('users.update');
});
