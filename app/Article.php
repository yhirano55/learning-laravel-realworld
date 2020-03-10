<?php

namespace App;

use App\Filters\Filterable;
use App\Traits\FavoritedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Providers\Auth\Illuminate;

class Article extends Model
{
    use FavoritedTrait, Filterable, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'body',
    ];

    /**
     * The relations to eager load on every query
     *
     * @var array
     */
    protected $with = [
        'tags'
    ];

    /**
     * Get the list of attached to the article.
     *
     * @return array
     */
    public function getTagListAttribute()
    {
        return $this->tags->pluck('name')->toArray();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLoadRelations($query)
    {
        return $query->with(['user.followers' => function ($query) {
                $query->where('follower_id', auth()->id());
            }])
            ->with(['favorited' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
            ->withCount('favorited');
    }

    /**
     * Get the user that own the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all the comments for the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Get all the tags for the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ]
        ];
    }

    /**
     * Get the key name for route model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
