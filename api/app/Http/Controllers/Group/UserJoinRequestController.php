<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupJoinRequestResource;
use App\Models\GroupJoinRequest;
use App\Models\Group;

class UserJoinRequestController extends Controller
{
    public function show(Group $group)
    {
        $request = $group->joinRequests()->where('user_id', auth()->id())->firstOrFail();

        return new GroupJoinRequestResource($request);
    }

    public function store(Group $group)
    {
        $request = $group->joinRequests()->updateOrCreate([
            'user_id' => auth()->id(),
        ]);

        return new GroupJoinRequestResource($request);
    }

    public function destroy(Group $group)
    {
        $request = $group->joinRequests()->where('user_id', auth()->id())->firstOrFail();

        $request->delete();

        return response()->noContent();
    }
}
