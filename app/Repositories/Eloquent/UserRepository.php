<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }
}