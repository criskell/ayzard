<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageLike;

class PageLikeController extends Controller
{
    public function store(Page $page)
    {
        $user = auth()->user();

        if (! $page->likes()->where('user_id', $user->id)->exists()) {
            $page->likes()->create([
                'user_id' => $user->id,
            ]);
        }
    }

    public function destroy(Page $page)
    {
        $like = $page->likes()->where('user_id', auth()->user()->id)->first();

        if ($like) {
            $like->delete();
        }
    }
}
