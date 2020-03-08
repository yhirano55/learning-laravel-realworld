<?php

namespace App\Http\Controllers\Api;

use App\Tag;
use App\Transformers\TagTransformer;

class TagController extends ApiController
{
    public function __construct(TagTransformer $transformer)
    {
        $this->transformaer = $transformer;
    }

    public function index()
    {
        $tags = Tag::all()->pluck('name');
        return $this->respondWithTransformer($tags);
    }
}
