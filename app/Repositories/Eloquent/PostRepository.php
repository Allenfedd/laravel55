<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;

class PostRepository extends Repository implements PostRepositoryInterface
{
    public function model()
    {
        return Post::class;
    }

    public function update(array $data, $id, $attribute = 'id')
    {
        $this->model = $this->findOrFail($id);
        $this->model->update($data);
        return $this->model;
    }

    public function syncTag(Post $post, array $tags)
    {
        $post->tags()->sync($tags);
    }

    public function delete($id)
    {
        $this->model = $this->findOrFail($id);
        $this->model->delete();

        return $this->model;
    }

    public function count()
    {
        return $this->model->count();
    }
}