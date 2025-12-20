<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/map.php';
require __DIR__.'/feed.php';
require __DIR__.'/auth.php';
require __DIR__.'/profile.php';
require __DIR__.'/settings.php';

// TODO : Middleware verified
