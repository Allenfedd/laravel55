<?php

namespace App\Http\Controllers\API;

use App\Repositories\Contracts\PostRepositoryInterface as PostRepository;

class HomeController extends ApiController
{
    protected $post;

    public function __construct(PostRepository $post)
    {
        $this->post = $post;
    }

    public function dashboard()
    {
        $posts = $this->post->count();

        $data = compact('posts');

        return $this->response()->json($data);
    }
}