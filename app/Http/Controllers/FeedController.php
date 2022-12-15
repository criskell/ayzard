<?php

namespace App\Http\Controllers;

use App\Models\Post;

class FeedController extends Controller
{
    public function show()
    {
        $posts = Post::all();

        return [
            'data' => [
                'posts' => $posts,
            ],
        ];
    }
}
