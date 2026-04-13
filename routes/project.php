<?php

use App\Http\Controllers\ProjectController;
use App\Models\Project;
use App\Models\ProjectStatus;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])
        ->name('projects.index');

    Route::middleware(['admin'])->group(function () {
        Route::get('/projects/create', function () {
            return Inertia::render('Projects/Create', [
                'name' => "Project #" . (Project::count() + 1),
                'statuses' => ProjectStatus::orderBy('order')->get(),
            ]);
        })
            ->name('projects.create');

        Route::post('/projects', [ProjectController::class, 'store'])
            ->name('projects.store');
    });
});
