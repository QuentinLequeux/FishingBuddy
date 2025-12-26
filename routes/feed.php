<?php

use App\Http\Controllers\Activities\CommentController;
use App\Http\Controllers\Activities\ActivitiesController;

Route::get('/feed', [ActivitiesController::class, 'index'])->name('feed');

Route::get('/feed/search', [ActivitiesController::class, 'search'])->name('feed.search');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::post('/feed', [ActivitiesController::class, 'store'])->name('feed.store');

    Route::post('/feed/{activity}', [ActivitiesController::class, 'like'])->name('feed.like');

    Route::delete('/feed/{activity}', [ActivitiesController::class, 'destroy'])->name('feed.destroy');

    Route::post('/feed/{user}/follow', [ActivitiesController::class, 'follow'])->name('feed.follow');

    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');

    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::post('/feed/{activity}/view', [ActivitiesController::class, 'view'])->name('feed.view');
});
