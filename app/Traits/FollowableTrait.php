<?php

namespace App\Traits;

use App\User;

trait FollowableTrait
{
    public function getFollowingAttribute()
    {
        if (! auth()->check()) {
            return false;
        }

        return $this->isFollowedBy(auth()->user());
    }

    public function follow(User $user)
    {
        if (! $this->isFollowing($user))
        {
            return $this->following()->attach($user);
        }
    }

    public function unFollow()
    {
        return $this->following()->detach($user);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id')->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return !! $this->following()->where('followed_id', $user->id)->count();
    }

    public function isFollowedBy(User $user)
    {
        return !! $this->followers()->where('followed_id', $user->id)->count();
    }
}
