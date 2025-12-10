<?php

use Inertia\Inertia;
use App\Models\User;

Route::get('/profile', function () {
    return redirect('/feed');
});

Route::get('/profile/{user:slug}', function (User $user) {
    return Inertia::render('profile/Profile', [
        'user' => $user,
        'posts' => $user->activities()->count(),
        'follows' => $user->followers()->count(),
        'followings' => $user->following()->count(),
        'old' => $user->activities()->with(['specie', 'lure', 'user', 'comments.user'])->oldest()->get(),
        'recent' => $user->activities()->with(['specie', 'lure', 'user', 'comments.user'])->latest()->get(),
        'followers_list' => $user->followers()->with('follower:id,name,slug')->get()->pluck('follower'),
        'following_list' => $user->following()->with('followed:id,name,slug')->get()->pluck('followed'),
        'liked' => $user->activities()->with(['specie', 'lure', 'user', 'comments.user'])->withCount('likes')->orderByDesc('likes_count')->get(),
        'isFollowing' => auth()->check()
            ? auth()->user()->following()->where('followed_id', $user->id)->exists()
            : false,
    ]);
})->name('profile');
