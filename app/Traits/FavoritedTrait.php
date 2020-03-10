<?php

namespace App\Traits;

use App\User;

trait FavoritedTrait
{
    /**
     * @return bool
     */
    public function getFavoritedAttribute()
    {
        if (! auth()->check()) {
            return false;
        }

        if (! $this->relationLoaded('favorited')) {
            $this->load(['favorited' => function($query) {
                $query->where('user_id', auth()->id());
            }]);
        }

        $favorited = $this->getRelation('favorited');

        if (! empty($favorited) && $favorited->contains('id', auth()->id())) {
            return true;
        }

        return false
    }

    /**
     * @return mixed
     */
    public function getFavoritesCountAttribute()
    {
        if (array_key_exists('favorited_count', $this->getAttributes())) {
            return $this->favorited_count;
        }

        return $this->favorited()->count();
    }

    /**
     * @return mixed
     */
    public function favorited()
    {
        return $this->belongsToMany(User::class, 'favorites', 'article_id', 'user_id')->withTimestamps();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isFavoritedBy(User $user)
    {
        return !! $this->favorited()->where('user_id', $user->id)->count();
    }
}
