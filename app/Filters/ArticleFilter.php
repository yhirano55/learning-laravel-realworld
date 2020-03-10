<?php

namespace App\Filters;

use App\Tag;
use App\User;

class ArticleFilter extends Filter
{
    /**
     * Filter by author username.
     * Get all the articles by the user with given username.
     *
     * @param $username
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function author($username)
    {
        $user = User::whereUsername($username)->first();
        $userId = $user ? $user->id : null;
        return $this->builder->whereUserId($userId);
    }

    /**
     * Filter by favorited username.
     * Get all the articles favorites by the user with given username.
     *
     * @param $username
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function favorited($username)
    {
        $user = User::whereUsername($username)->first();
        $articleIds = $user ? $user->favorites()->pluck('id')->toArray() : null;
        return $this->builder->find($articleIds);
    }

    /**
     * Filter by tag name.
     * Get all the articles tagged by the user with given tag name.
     *
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function tag($name)
    {
        $tag = Tag::WhereName($name)->first();
        $articleIds = $tag ? $tag->articles()->pluck('id')->toArray() : null;
        return $this->builder->find($articleIds);
    }
}
