<?php

namespace App\Transformers;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['tags'];

    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'view_count' => $post->view_count,
            'created_at' => $post->created_at->toDateTimeString(),
            'updated_at' => $post->updated_at->toDateTimeString(),
        ];
    }

    public function includeTags(Post $post)
    {
        if ($tags = $post->tags) {
            return $this->collection($tags, new TagTransformer());
        }
    }
}