<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Requests\SaveCommentRequest;
use App\Models\Post;
use App\Models\Comment;

class PostCommentController extends Controller
{
    public function index(Post $post)
    {
        return CommentResource::collection($post->comments()->paginate());
    }

    public function store(SaveCommentRequest $request, Post $post)
    {
        $comment = new Comment;

        $comment->content = $request->content;

        $comment->user()->associate($request->user());
        $comment->post()->associate($post);

        $comment->save();

        return new CommentResource($comment);
    }
}
