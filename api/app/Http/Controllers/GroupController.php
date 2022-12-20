<?php

namespace App\Http\Controllers;

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

        return new GroupResource($group);
    }

    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
