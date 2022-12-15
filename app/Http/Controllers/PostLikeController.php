<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;

class PostLikeController extends Controller
{
    public function store(Post $post)
    {
        $user = auth()->user();

        if (! $post->likes()->where('user_id', $user->id)->exists()) {
            $user->likedPosts()->attach($post);
        }
    }

    public function destroy(Post $post)
    {
        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        if ($like) {
            $like->delete();
        }
    }
}
