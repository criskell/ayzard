<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments;

        return [
            'data' => [
                'comments' => $comments,
            ],
        ];
    }

    public function store(StoreCommentRequest $request, Post $post)
    {
        $comment = new Comment;

        $comment->content = $request->content;

        $comment->user()->associate($request->user());
        $comment->post()->associate($post);

        $comment->save();

        return [
            'data' => [
                'id' => $comment->id,
            ]
        ];
    }

    public function show(Comment $comment)
    {

    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    public function destroy(Comment $comment)
    {
        //
    }
}
