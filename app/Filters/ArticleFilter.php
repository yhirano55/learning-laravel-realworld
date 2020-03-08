<?php

namespace App\Filters;

use App\Tag;
use App\User;

class ArticleFilter extends Filter
{
    public function author($username)
    {
        $user = User::whereUsername($username)->first();
        $userId = $user ? $user->id : null;
        return $this->builder->whereUserId($userId);
    }

    public function favorited($username)
    {
        $user = User::whereUsername($username)->first();
        $articleIds = $user ? $user->favorites()->pluck('id')->toArray() : null;
        return $this->builder->find($articleIds);
    }

    public function tag($name)
    {
        $tag = Tag::WhereName($name)->first();
        $articleIds = $tag ? $tag->articles()->pluck('id')->toArray() : null;
        return $this->builder->find($articleIds);
    }
}
