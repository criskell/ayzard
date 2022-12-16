<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\PostResource;

class FeedController extends Controller
{
    public function show()
    {
        $posts = Post::with([
            'user',
            'comments' => function ($query) {
                $query->latest()->limit(15);
            },
        ])
            ->withCount(['comments', 'likes'])
            ->paginate();

        return PostResource::collection($posts);
    }
}
