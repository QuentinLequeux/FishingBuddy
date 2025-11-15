<?php

use App\Http\Controllers\Activities\ActivitiesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/feed', [ActivitiesController::class, 'index'])->name('feed');

    Route::post('/feed', [ActivitiesController::class, 'store'])->name('feed.store');

    Route::post('/feed/{user}/follow', [ActivitiesController::class, 'follow'])->name('feed.follow');

    Route::delete('/feed/{activity}', [ActivitiesController::class, 'destroy'])->name('feed.destroy');

    Route::post('/feed/{activity}', [ActivitiesController::class, 'like'])->name('feed.like');
});

// TODO : Route accessible guest ?
