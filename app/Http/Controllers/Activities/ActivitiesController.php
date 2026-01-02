<?php

namespace App\Http\Controllers\Activities;

use App\Models\Like;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Lure;
use App\Models\Specie;
use App\Models\Activity;
use App\Concerns\RandomUser;
use Illuminate\Http\Request;
use App\Concerns\UserActivity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activity\ActivityStoreRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivitiesController extends Controller
{
    use RandomUser;
    use UserActivity;
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $followingIds = $user ? $user->following()->pluck('followed_id')->toArray() : [];

        $likedActivityIds = $user ? $user->likes()->pluck('activity_id')->toArray() : [];

        $filters = $request->input('filters', []);
        $offset = (int) $request->get('offset', 0);
        $limit = 5;

        $activities = $this->getActivities($limit, $offset, $filters);

        $activities = $this->getUserActivityData($activities, $followingIds, $likedActivityIds);

        $follows = $this->getActivities(10)->whereIn('user_id', $followingIds);

        $follows = $this->getUserActivityData($follows, $followingIds, $likedActivityIds);

        $users = $this->getRandomUsers($user, $followingIds, 10);

        $suggestions = $this->getRandomUsers($user, $followingIds, 5);

        return Inertia::render('feed/Feed', [
            'activities' => $activities,
            'follows' => $follows,
            'species' => Specie::all(),
            'lures' => Lure::all(),
            'users' => $users,
            'suggestions' => $suggestions,
            'tab' => request('tab', 'feed'),
            'publish' => request()->boolean('publish'),
            'hasMore' => $activities->count() === $limit,
            'offset' => $offset + $limit,
            'filters' => $filters,
        ]);
    }

    /**
     * Follow a user.
     */
    public function follow(User $user)
    {
        $authUser = auth()->user();

        $isFollowing = $authUser->following()->where('users.id', $user->id)->exists();

        if ($isFollowing) {
            $authUser->following()->detach($user->id);
        } else {
            $authUser->following()->attach($user->id);
        }
    }

    /**
     * Like a post.
     */
    public function like(Activity $activity)
    {
        $authId = auth()->id();

        $isLiked = $activity->likes()->where('user_id', $authId)->first();

        if ($isLiked) {
            $isLiked->delete();
        } else {
            Like::create(['user_id' => $authId, 'activity_id' => $activity->id]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActivityStoreRequest $request)
    {
        $this->authorize('create', Activity::class);

        $validated = $request->validated();

        Activity::create([
            'user_id' => auth()->user()->id,
            'specie_id' => $validated['specie_id'],
            'lure_id' => $validated['lure_id'],
            'size' => $validated['size'],
            'weight' => $validated['weight'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $this->authorize('delete', $activity);

        $activity->delete();
    }

    public function view(Activity $activity)
    {
        $user = auth()->user();

        $alreadyView = $activity->views()
            ->where('user_id', $user->id)
            ->exists();

        if (!$alreadyView) {
            $activity->views()->create([
                'user_id' => $user->id
            ]);
        }
    }

    public function search(Request $request)
    {
        $user = auth()->user();

        $followingIds = $user ? $user->following()->pluck('followed_id')->toArray() : [];

        $likedActivityIds = $user ? $user->likes()->pluck('activity_id')->toArray() : [];

        $activities = $this->getActivities();

        $activities = $this->getUserActivityData($activities, $followingIds, $likedActivityIds);

        $query = $request->get('q', '');

        $users = User::where('name', 'like', "$query%")
            ->take(10)
            ->get();

        return Inertia::render('feed/Feed', [
                'users' => $users,
                'searchQuery' => $query,
                'activities' => $activities,
            ]);
    }

    private function getActivities(int $limit = 5, int $offset = 0, array $filters = [])
    {
        $viewer = auth()->user();

        $query = Activity::with(['user', 'lure', 'specie', 'comments.user'])
            ->whereHas('user', function ($query) use ($viewer) {
                $query->where('activities_visibility', 'public');

                if ($viewer) {
                    $query->orWhere(function ($q) use ($viewer) {
                        $q->where('activities_visibility', 'followers')
                            ->whereHas('followers', function ($f) use ($viewer) {
                                $f->where('follower_id', $viewer->id);
                            });
                    });

                    $query->orWhere('id', $viewer->id);
                }
            });

            if (($filters['mine'] ?? false) && $viewer) {
                $query->where('user_id', $viewer->id);
            }
            return $query
            ->latest()
            ->offset($offset)
            ->take($limit)
            ->get();
    }
}

// TODO : Optimiser queries et models
// TODO : LikeController et FollowController ?
