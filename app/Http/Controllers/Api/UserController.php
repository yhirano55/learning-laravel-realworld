<?php

namespace App\Http\Controllers\Api;

use App\Transformers\UserTransformer;
use App\Http\Requests\Api\UpdateUser;

class UserController extends ApiController
{
    public function __construct(UserTransformer $transformer)
    {
        $this->transformer = $transformer;
        $this->middleware('auth.api');
    }

    public function index()
    {
        return $this->respondUnauthorized(auth()->user());
    }

    public function update(UpdateUser $request)
    {
        $user = auth()->user();
        $user->update($request->get('user'));

        return $this->respondUnauthorized($user);
    }
}
