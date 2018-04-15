<?php

namespace App\Http\Controllers\API;

use App\Repositories\Contracts\PostRepositoryInterface as PostRepository;
use App\Transformers\PostTransformer;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    protected $post;

    public function __construct(PostRepository $post)
    {
        $this->post = $post;
    }

    public function index()
    {
        return $this->response()->collection($this->post->paginate(), new PostTransformer());
    }

    public function store(Request $request)
    {
        $model = $this->post->create($request->all());
        $this->post->syncTag($model, $request->get('tag_ids'));

        return $this->response()->withNoContent();
    }

    public function show($id)
    {
        return $this->response()->item($this->post->find($id), new PostTransformer());
    }

    public function update(Request $request, $id)
    {
        $model = $this->post->update($request->all(), $id);

        $this->post->syncTag($model, $request->get('tag_ids'));

        return $this->response()->withNoContent();
    }
}