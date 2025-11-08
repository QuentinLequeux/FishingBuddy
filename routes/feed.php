<?php

use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/feed', function () {
        Return Inertia::render('feed/Feed');
    })->name('feed');
});

// TODO : Route accessible guest ?
