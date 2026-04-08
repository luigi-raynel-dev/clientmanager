<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])
        ->name('projects.index');

    Route::middleware(['admin'])->group(function () {
        Route::get('/projects/create', function () {
            return Inertia::render('Projects/Create');
        })
            ->name('projects.create');

        Route::post('/projects', [ProjectController::class, 'store'])
            ->name('projects.store');
    });
});
