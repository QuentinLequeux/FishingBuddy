<?php

namespace App\Concerns;

use App\Models\User;

trait RandomUser
{
    public function getRandomUsers($user, $followingIds, $limit)
    {
        if (!$user) {
            return User::inRandomOrder()
                ->take($limit)
                ->get();
        }

        return User::whereNotIn('id', array_merge($followingIds, [$user->id]))
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }
}
