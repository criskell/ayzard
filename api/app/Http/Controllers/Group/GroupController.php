<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Http\Requests\SaveGroupRequest;
use App\Http\Resources\GroupResource;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::paginate();

        return GroupResource::collection($groups);
    }

    public function store(SaveGroupRequest $request)
    {
        $group = auth()->user()->groups()->create($request->only(['name', 'description']));

        $group->members()->create([
            'user_id' => auth()->id(),
            'is_admin' => true,
        ]);

        return new GroupResource($group);
    }

    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    public function update(SaveGroupRequest $request, Group $group)
    {
        $this->authorize('update', $group);

        $group->update($request->only(['name', 'description']));

        return response()->noContent();
    }

    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        $group->delete();

        return response()->noContent();
    }
}
