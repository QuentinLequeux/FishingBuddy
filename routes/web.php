<?php

use App\Models\User;
use Inertia\Inertia;
use App\Models\Activity;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    $user = auth()->user();
    $users = User::inRandomOrder()->take(4)->get()->map(function ($u) use ($user) {
        $u->isFollowing = $user ? $user->following()->where('followed_id', $u->id)->exists() : false;
        return $u;
    });
    $activity = Activity::with(['user', 'specie', 'lure', 'comments.user'])->inRandomOrder()->first();
    if ($activity && $user) {
    $activity->isFollowing = $user
        ->following()
        ->where('followed_id', $activity->user_id)
        ->exists();}
    return Inertia::render('Dashboard', [
        'user' => $user,
        'users' => $users,
        'activity' => $activity,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/map.php';
require __DIR__.'/feed.php';
require __DIR__.'/auth.php';
require __DIR__.'/profile.php';
require __DIR__.'/settings.php';
