<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\SavePostRequest;
use App\Models\User;

class UserPostController extends Controller
{
    public function index(User $user)
    {
        return PostResource::collection($user->posts()->paginate());
    }
}
