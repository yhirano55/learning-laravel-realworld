<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace', 'Api'], function () {
        Route::post('users/login', 'AuthController@login');
        Route::post('users', 'AuthController@register');

        Route::get('user', 'UserController@index');
        Route::match(['put', 'patch'], 'user', 'UserController@update');

        Route::group(['prefix' => 'profiles'], function() {
            Route::get('{user}', 'ProfileController@show');
            Route::post('{user}/follow', 'ProfileController@follow');
            Route::delete('{user}/follow', 'ProfileController@unfollow');
        });

        Route::resource('articles', 'ArticleController', [
            'except' => [
                'create', 'edit'
            ]
        ]);

        Route::get('articles/feed', 'ProfileController@feed');
        Route::post('articles/{article}/favorite', 'ProfileController@favorite');
        Route::delete('articles/{article}/favorite', 'ProfileController@unFavorite');

        Route::resource('articles/{article}/comments', 'CommentController', [
            'only' => [
                'index', 'store', 'destroy'
            ]
        ]);

        Route::get('tags', 'TagController@index');
});
