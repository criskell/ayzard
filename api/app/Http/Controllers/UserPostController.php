<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Requests\SavePostRequest;
use App\Models\User;

class UserPostController extends Controller
{
    public function index(User $user)
    {
        return PostResource::collection($user->posts()->paginate());
    }

    public function store(SavePostRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $post = $user->posts()->create($request->only(['content']));

        return new PostResource($post);
    }
}
