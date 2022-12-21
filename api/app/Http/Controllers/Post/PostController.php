<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\SavePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::paginate());
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function update(SavePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->update($request->only(['content']));

        return response()->noContent();
    }

    public function destroy(SavePostRequest $request, Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->noContent();
    }
}
