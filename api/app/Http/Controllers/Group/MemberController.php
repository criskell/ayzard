<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupMemberResource;
use App\Models\Group;
use App\Models\User;
use App\Http\Requests\SaveMemberRequest;

class MemberController extends Controller
{
    public function index(Group $group)
    {
        $this->authorize('member', $group);

        $members = $group->members()->paginate();

        return GroupMemberResource::collection($members);
    }

    public function show(Group $group, User $user)
    {
        $loggedMember = $group->members()->where('user_id', auth()->id())->first();
        
        $this->authorize('member', [$group, $loggedMember]);

        $member = $group->members()->where('user_id', $user->id)->firstOrFail();

        return new GroupMemberResource($member);
    }

    public function update(SaveMemberRequest $request, Group $group, User $user)
    {
        $loggedMember = $group->members()->where('user_id', auth()->id())->first();

        $this->authorize('admin', [$group, $loggedMember]);

        $member = $group->members()->where('user_id', $user->id)->firstOrFail();

        $member->update($request->only(['is_admin']));

        return new GroupMemberResource($member);
    }

    public function destroy(Group $group, User $user)
    {
        $loggedMember = $group->members()->where('user_id', auth()->id())->first();

        $this->authorize('admin', [$group, $loggedMember]);

        $member = $group->members()->where('user_id', $user->id)->firstOrFail();

        $member->delete();

        return response()->noContent();
    }
}
