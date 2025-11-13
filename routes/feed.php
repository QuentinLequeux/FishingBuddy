<?php

use App\Http\Controllers\Activities\ActivitiesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/feed', [ActivitiesController::class, 'index'])->name('feed');

    Route::post('/feed/{user}/follow', [ActivitiesController::class, 'follow'])->name('feed.follow');
});

// TODO : Route accessible guest ?
