<?php

namespace App\Concerns;

trait UserActivity
{
    public function getUserActivityData($activities, array $followingIds = [], array $likedActivityIds = [])
    {
        return $activities->map(function ($activity) use ($followingIds, $likedActivityIds) {
            $activity->isFollowing = in_array($activity->user_id, $followingIds);

            $activity->hasLiked = in_array($activity->id, $likedActivityIds);

            $activity->likesCount = $activity->likes->count();

            $activity->commentsCount = $activity->comments()->count();

            return $activity;
        });
    }
}
