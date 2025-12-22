<?php

use App\Http\Controllers\Activities\ProfileController;

Route::get('/profile', function () {
    return redirect('/feed');
});

Route::get('/profile/{user:slug}', [ProfileController::class, 'index'])->name('profile');
