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
        $userFeedGenerator = auth()->user();

        $feedAllowedUserIds = $userFeedGenerator->following()
            ->pluck('following_id')
            ->push($userFeedGenerator->id);

        $sharedPosts = Post::select([
            'posts.*',
            'post_shares.created_at AS shared_at',
            'post_shares.user_id AS shared_by_id',
            DB::raw('"shared" as type')
        ])
            ->whereIn('post_shares.user_id', $feedAllowedUserIds)
            ->join('post_shares', 'post_shares.post_id', '=', 'posts.id');

        $regularPosts = Post::select([
            'posts.*',
            DB::raw('NULL as shared_at'),
            DB::raw('NULL as shared_by_id'),
            DB::raw('"regular" as type')
        ])->whereIn('posts.user_id', $feedAllowedUserIds);

        $posts = $sharedPosts
            ->unionAll($regularPosts)
            ->orderByDesc(DB::raw('COALESCE(shared_at, created_at)'))
            ->paginate();

        $posts->load([
            'user',
            'sharedBy',
            'comments' => function ($query) {
                $query->latest()->take(15);
            }
        ]);

        $posts->loadCount(['likes', 'comments', 'shares']);

        return PostResource::collection($posts);
    }
}