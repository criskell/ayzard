<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        //
    }

    public function store(SavePostRequest $request)
    {
        $post = auth()->user()->posts()->create($request->only(['content']));

        return [
            'data' => [
                'id' => $post->id,
            ],
        ];
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
