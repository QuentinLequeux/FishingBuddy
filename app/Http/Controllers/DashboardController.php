<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Activity;
use App\Concerns\UserActivity;

class DashboardController extends Controller
{
    use UserActivity;

    public function index()
    {
        $user = auth()->user();

        $followingIds = $user ? $user->following()->pluck('followed_id')->toArray() : [];

        $users = User::whereNotIn('id', array_merge($followingIds, [$user->id]))
            ->inRandomOrder()
            ->take(4)
            ->get();

        $activity = Activity::with(['user', 'specie', 'lure', 'comments.user'])
            ->visibleFor($user)
            ->inRandomOrder()
            ->first();

        if ($activity) {
            $activity = $this->getUserActivityData(collect([$activity]), $followingIds)->first();
        }

        return Inertia::render('Dashboard', [
            'user' => $user,
            'users' => $users,
            'activity' => $activity,
        ]);
    }
}
