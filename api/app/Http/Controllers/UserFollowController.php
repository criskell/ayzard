<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    public function store(User $user)
    {
        $data = [
            'follower_id' => auth()->user()->id,
            'following_id' => $user->id,
        ];

        $follow = Follow::where($data)->first();

        if (! $follow) {
            Follow::create($data);
        }

        return response()->noContent();
    }

    public function destroy(User $user)
    {
        $follow = Follow::where([
            'follower_id' => auth()->user()->id,
            'following_id' => $user->id,
        ])->first();

        if ($follow) {
            $follow->delete();
        }

        return response()->noContent();
    }
}
