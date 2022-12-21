<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Feed;
use App\Http\Controllers\User;
use App\Http\Controllers\Post;
use App\Http\Controllers\Comment;
use App\Http\Controllers\Group;

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::post('/login', [Auth\LoginController::class, 'login'])->name('login');
    Route::post('/register', [Auth\RegisterController::class, 'register'])->name('register');
});

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('users.posts', Post\UserPostController::class)->only(['index', 'store']);

    Route::apiSingleton('users.follow', User\UserFollowController::class)
        ->creatable()
        ->only(['store', 'destroy']);

    Route::apiResource('posts', Post\PostController::class)->shallow();
    Route::apiSingleton('posts.like', Post\PostLikeController::class)->creatable()->only(['store', 'destroy']);
    Route::apiSingleton('posts.share', Post\PostShareController::class)->creatable()->only(['store', 'destroy']);
    Route::apiResource('posts.comments', Post\PostCommentController::class)->only(['index', 'store']);

    Route::apiResource('comments', Comment\CommentController::class)->except(['index', 'store']);

    Route::apiResource('groups', Group\GroupController::class);
    Route::apiSingleton('groups.join-request', Group\UserJoinRequestController::class)
        ->creatable()
        ->only(['show', 'store', 'destroy']);

    Route::get('/feed', [Feed\FeedController::class, 'show']);
});