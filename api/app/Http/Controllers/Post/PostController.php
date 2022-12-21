<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\SavePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Group;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::paginate());
    }

    public function store(SavePostRequest $request)
    {
        $groupId = $request->query('group_id');

        if ($groupId) {
            $group = Group::findOrFail($groupId);
            $loggedMember = $group->members()->where('user_id', auth()->id())->first();
            $this->authorize('member', [$group, $loggedMember]);
        }

        $data = $request->only(['content']) + [
            'group_id' => $groupId,
        ];
        $post = auth()->user()->posts()->create($data);

        return new PostResource($post);
    }

    public function show(Post $post)
    {
        if ($post->group_id) {
            $group = Group::findOrFail($post->group_id);
            $loggedMember = $group->members()->where('user_id', auth()->id())->first();
            $this->authorize('member', [$group, $loggedMember]);
        }

        return new PostResource($post);
    }

    public function update(SavePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->update($request->only(['content']));

        return response()->noContent();
    }

    public function destroy(SavePostRequest $request, Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->noContent();
    }
}
