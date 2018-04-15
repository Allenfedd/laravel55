<?php

namespace App\Repositories\Contracts;

use App\Models\Post;

interface PostRepositoryInterface extends RepositoryInterface
{
    public function syncTag(Post $post, array $data);
}