<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\GroupJoinRequest;
use App\Http\Resources\GroupJoinRequestResource;
use App\Models\Group;

class JoinRequestController extends Controller
{
    public function index(Group $group)
    {
        $joinRequests = $group->joinRequests;

        return GroupJoinRequestResource::collection($joinRequests);
    }

    public function destroy(GroupJoinRequest $joinRequest)
    {
        $joinRequest->delete();

        return response()->noContent();
    }

    public function approve(GroupJoinRequest $joinRequest)
    {
        $joinRequest->delete();

        return response()->noContent();
    }

    public function reject(GroupJoinRequest $request)
    {
        $joinRequest->delete();

        return response()->noContent();
    }
}
