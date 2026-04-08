<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('home');

require __DIR__ . '/settings.php';
require __DIR__ . '/user.php';
require __DIR__ . '/client.php';
require __DIR__ . '/service.php';
require __DIR__ . '/pricingType.php';
require __DIR__ . '/project.php';
