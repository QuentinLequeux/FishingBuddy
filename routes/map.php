<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/map', function () {
        return Inertia::render('map/Map');
    })->name('map');
});
