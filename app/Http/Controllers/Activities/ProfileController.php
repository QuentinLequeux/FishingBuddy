<?php

namespace App\Http\Controllers\Activities;

use App\Models\User;
use Inertia\Inertia;
use App\Concerns\UserActivity;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    use UserActivity;

    public function index(User $user)
    {
        $authUser = auth()->user();

        $followingIds = $authUser
            ? $authUser->following()->pluck('followed_id')->toArray()
            : [];

        $likedActivityIds = $authUser
            ? $authUser->likes()->pluck('activity_id')->toArray()
            : [];

        $user->loadCount([
            'activities',
            'followers',
            'following'
        ]);

        $following_list = $user->following()->get(['users.id', 'users.name', 'users.slug']);

        $followers_list = $user->followers()->with('follower:id,name,slug')->get()->pluck('follower');

        $activities = $user->activities()->with(['specie', 'lure', 'user', 'comments.user'])->take(10);

        $old = $this->getUserActivityData($activities->oldest()->get(), $followingIds, $likedActivityIds);

        $recent = $this->getUserActivityData($activities->latest()->get(), $followingIds, $likedActivityIds);

        $liked = $this->getUserActivityData($activities->withCount('likes')->orderByDesc('likes_count')->get(), $followingIds, $likedActivityIds);

        $is_following = auth()->check() ? auth()->user()->following()->where('followed_id', $user->id)->exists() : false;

        return Inertia::render('profile/Profile',[
            'old' => $old,
            'user' => $user,
            'liked' => $liked,
            'recent' => $recent,
            'isFollowing' => $is_following,
            'posts' => $user->activities_count,
            'followers_list' => $followers_list,
            'following_list' => $following_list,
            'follows' => $user->followers_count,
            'followings' => $user->following_count,
        ]);
    }
}
