<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostShare;
use App\Models\GroupMember;
use App\Models\PageLike;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function show(Request $request)
    {
        $search = $request->query('search', '');

        $userFeedGenerator = auth()->user();

        $friendIds = $userFeedGenerator->friendsFromSource()->pluck('source_id')->merge($userFeedGenerator->friendsFromTarget()->pluck('target_id'));

        $feedAllowedUserIds = $userFeedGenerator->following()
            ->pluck('following_id')
            ->push($userFeedGenerator->id)
            ->merge($friendIds);

        $feedAllowedGroupIds = GroupMember::where('user_id', $userFeedGenerator->id)
            ->pluck('group_id');

        $feedAllowedPageIds = PageLike::where('user_id', $userFeedGenerator->id)
            ->pluck('page_id');

        $sharedPosts = Post::select([
            'posts.*',
            'post_shares.created_at AS shared_at',
            'post_shares.user_id AS shared_by_id',
            DB::raw('"shared" as type')
        ])
            ->where('posts.content', 'LIKE', '%' . $search .'%')
            ->whereIn('post_shares.user_id', $feedAllowedUserIds)
            ->orWhereIn('posts.group_id', $feedAllowedGroupIds)
            ->orWhereIn('posts.page_id', $feedAllowedPageIds)
            ->join('post_shares', 'post_shares.post_id', '=', 'posts.id');

        $regularPosts = Post::select([
            'posts.*',
            DB::raw('NULL as shared_at'),
            DB::raw('NULL as shared_by_id'),
            DB::raw('"regular" as type')
        ])
            ->where('posts.content', 'LIKE', '%' . $search .'%')
            ->whereIn('posts.user_id', $feedAllowedUserIds)
            ->orWhereIn('posts.group_id', $feedAllowedGroupIds)
            ->orWhereIn('posts.page_id', $feedAllowedPageIds);

        $posts = $sharedPosts
            ->unionAll($regularPosts)
            ->orderByDesc(DB::raw('COALESCE(shared_at, created_at)'))
            ->paginate();

        $posts->load([
            'user',
            'sharedBy',
            'group',
            'page',
            'comments' => function ($query) {
                $query->latest()->take(15);
            }
        ]);

        $posts->loadCount(['likes', 'comments', 'shares']);

        return PostResource::collection($posts);
    }
}