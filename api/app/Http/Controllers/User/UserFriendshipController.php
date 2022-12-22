<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendshipResource;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;

class UserFriendshipController extends Controller
{
    public function index(User $user)
    {
        if ($user->id !== auth()->id()) {
            abort(403);
        }

        $friendships = Friendship::with(['source', 'target'])
            ->where('source_id', $user->id)
            ->orWhere('target_id', $user->id)
            ->get();

        return FriendshipResource::collection($friendships);
    }

    public function store(Request $request, User $user)
    {
        if ($user->id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'friend_id' => 'required|exists:users,id'
        ]);

        $friendship = Friendship::where('source_id', $request->friend_id)
            ->orWhere('target_id', $request->friend_id)
            ->orWhere('source_id', $user->id)
            ->orWhere('target_id', $user->id)
            ->with(['source', 'target'])
            ->first();

        if (!$friendship) {
            $friendship = Friendship::create([
                'status' => 'pending',
                'source_id' => $user->id,
                'target_id' => $request->friend_id,
            ]);

            $friendship->setRelation('source', auth()->user());
            $friendship->setRelation('target', User::find($request->friend_id));
        }

        return new FriendshipResource($friendship);
    }

    public function show(User $user, User $friend)
    {
        if ($user->id !== auth()->id()) {
            abort(403);
        }

        $friendship = Friendship::where('source_id', $friend->id)
            ->orWhere('target_id', $friend->id)
            ->orWhere('source_id', $user->id)
            ->orWhere('target_id', $user->id)
            ->with(['source', 'target'])
            ->firstOrFail();

        return new FriendshipResource($friendship);
    }

    public function update(Request $request, User $user, User $friend)
    {
        if (!auth()->user()->is($user)) {
            abort(403);
        }

        $friendship = Friendship::where('source_id', $friend->id)
            ->orWhere('target_id', $friend->id)
            ->orWhere('source_id', $user->id)
            ->orWhere('target_id', $user->id)
            ->with(['source', 'target'])
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:confirmed',
        ]);

        $invalid = $friendship->source->is($user);

        if ($invalid) {
            abort(403);
        }

        $friendship->update([
            'status' => 'confirmed'
        ]);

        return new FriendshipResource($friendship);
    }

    public function destroy(User $user, User $friend)
    {
        if (!auth()->user()->is($user)) {
            abort(403);
        }

        $friendship = Friendship::where('source_id', $friend->id)
            ->orWhere('target_id', $friend->id)
            ->orWhere('source_id', $user->id)
            ->orWhere('target_id', $user->id)
            ->with(['source', 'target'])
            ->firstOrFail();

        $friendship->delete();

        return response()->noContent();
    }
}
