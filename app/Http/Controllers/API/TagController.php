<?php

namespace App\Http\Controllers\API;

use App\Repositories\Contracts\TagRepositoryInterface as TagRepository;

class TagController extends ApiController
{
    protected $tag;

    public function __construct(TagRepository $tag)
    {
        $this->tag = $tag;
    }

    public function index()
    {
        $tags = $this->tag->all(['id', 'name']);

        return $this->response()->json(['data' => $tags]);
    }
}
