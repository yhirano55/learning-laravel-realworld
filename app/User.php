<?php

namespace App;

use Tymon\JWTAuth\JWTAuth;
use App\Traits\FavoriteTrait;
use App\Traits\FollowableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, FavoriteTrait, FollowableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'bio', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set the password using bcrypt hasher.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Generate a JWT token for user
     *
     * @return string
     */
    public function getTokenAttribute()
    {
        return JWTAuth::fromUser($this);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the key name for route model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }
}
