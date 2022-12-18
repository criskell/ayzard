<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostShare;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    public function show()
    {
        $sharedPosts = Post::select([
            'posts.*',
            'post_shares.created_at AS shared_at',
            'post_shares.user_id AS shared_by_id',
            DB::raw('"shared" as type')
        ])
            ->join('post_shares', 'post_shares.post_id', '=', 'posts.id');
        $regularPosts = Post::select([
            'posts.*',
            DB::raw('NULL as shared_at'),
            DB::raw('NULL as shared_by_id'),
            DB::raw('"regular" as type')
        ]);

        $posts = $sharedPosts
            ->unionAll($regularPosts)
            ->orderByDesc(DB::raw('COALESCE(shared_at, created_at)'))
            ->paginate();

        return PostResource::collection($posts);
    }

    // public function show()
    // {
    //     $sharedPosts = PostShare::with(['user', 'post'])
    //         ->take(15)
    //         ->latest()
    //         ->get()
    //         ->map(function ($share) {
    //             $post = $share->post;
    //             $post->entry_created_at = $post->shared_at = $share->created_at;
    //             $post->shared_by = $share->user;
    //             return $post;
    //         });

    //     $regularPosts = Post::with(['user'])
    //         ->take(15)
    //         ->latest()
    //         ->get()
    //         ->map(function ($post) {
    //             $post->entry_created_at = $post->created_at;
    //             return $post;
    //         });

    //     $posts = $regularPosts->merge($sharedPosts)->sortByDesc('entry_created_at');

    //     return PostResource::collection($posts);
    // }
}