<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\PostShare;
use App\Models\Post;

class PostShareController extends Controller
{
    public function store(Post $post)
    {
        $user = auth()->user();

        if (! $post->shares()->where('user_id', $user->id)->exists()) {
            $post->shares()->create([
                'user_id' => $user->id,
            ]);
        }
    }

    public function destroy(Post $post)
    {
        $share = $post->shares()->where('user_id', auth()->user()->id)->first();

        if ($share) {
            $share->delete();
        }
    }
}
