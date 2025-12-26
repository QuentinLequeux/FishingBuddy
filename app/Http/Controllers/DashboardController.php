<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Activity;
use App\Concerns\RandomUser;
use App\Concerns\UserActivity;

class DashboardController extends Controller
{
    use RandomUser;
    use UserActivity;

    public function index()
    {
        $user = auth()->user();

        $followingIds = $user ? $user->following()->pluck('followed_id')->toArray() : [];

        $users = $this->getRandomUsers($user, $followingIds, 4);

        $activity = Activity::with(['user', 'specie', 'lure', 'comments.user'])
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
