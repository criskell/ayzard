<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\DestroyPostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return [
            'data' => $posts,
        ];
    }

    public function store(SavePostRequest $request)
    {
        $post = auth()->user()->posts()->create($request->only(['content']));

        return [
            'data' => [
                'id' => $post->id,
            ],
        ];
    }

    public function show(Post $post)
    {
        return [
            'data' => $post,
        ];
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->only(['content']));

        return response()->noContent();
    }

    public function destroy(DestroyPostRequest $request, Post $post)
    {
        $post->delete();

        return response()->noContent();
    }
}
