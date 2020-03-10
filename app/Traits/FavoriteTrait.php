<?php

namespace App\Traits;

use App\Article;

trait FavoriteTrait
{
    /**
     * Favorite the given article
     *
     * @param Article $article
     * @return mixed
     */
    public function favorite(Article $article)
    {
        if (! $this->hasFavorite($article)) {
            return $this->favorites()->attach($article);
        }
    }

    /**
     * Unfavorite the given article.
     *
     * @param Article $article
     * @return mixed
     */
    public function unFavorite(Article $article)
    {
        return $this->favorites()->detach($article);
    }

    /**
     * Get the articles favorited by the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function favorites()
    {
        return $this->belongsToMany(Article::class, 'favorites', 'user_id', 'article_id')->withTimestamps();
    }

    /**
     * Check if the user has favorited the given article.
     *
     * @param Article $article
     * @return bool
     */
    public function hasFavorite(Article $article)
    {
        return !! $this->favorites()->where('article_id', $article->id)->count();
    }
}
