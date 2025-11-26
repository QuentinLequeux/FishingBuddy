<?php

use Inertia\Inertia;
use App\Models\User;

Route::get('/profile/{user:name?}', function (User $user) {
    return Inertia::render('profile/Profile', [
        'user' => $user,
        'posts' => $user->activities()->count(),
        'follows' => $user->followers()->count(),
        'isFollowing' => $user->following()->where('followed_id', $user->id)->exists(),
    ]);
})->name('profile');

// TODO : slug
