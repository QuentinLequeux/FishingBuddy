<?php

use Inertia\Inertia;
use App\Models\User;

Route::get('/profile', function () {
    return redirect('/');
});

Route::get('/profile/{user:name?}', function (User $user) {
    return Inertia::render('profile/Profile', [
        'user' => $user,
        'posts' => $user->activities()->count(),
        'follows' => $user->followers()->count(),
        'followings' => $user->following()->count(),
        'followers_list' => $user->followers()->with('follower:id,name')->get()->pluck('follower'),
        'following_list' => $user->following()->with('followed:id,name')->get()->pluck('followed'),
        'isFollowing' => auth()->check()
            ? auth()->user()->following()->where('followed_id', $user->id)->exists()
            : false,
    ]);
})->name('profile');

// TODO : slug
