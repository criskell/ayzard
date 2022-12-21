<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Feed;
use App\Http\Controllers\User;
use App\Http\Controllers\Post;
use App\Http\Controllers\Comment;
use App\Http\Controllers\Group;
use App\Http\Controllers\Page;

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::post('/login', [Auth\LoginController::class, 'login'])->name('login');
    Route::post('/register', [Auth\RegisterController::class, 'register'])->name('register');
});

Route::middleware(['auth:api'])->group(function () {
    Route::apiSingleton('users.follow', User\UserFollowController::class)
        ->creatable()
        ->only(['store', 'destroy']);

    Route::apiResource('users.posts', Post\UserPostController::class)->only(['index']);
    Route::apiResource('posts', Post\PostController::class)->shallow();
    Route::apiSingleton('posts.like', Post\PostLikeController::class)->creatable()->only(['store', 'destroy']);
    Route::apiSingleton('posts.share', Post\PostShareController::class)->creatable()->only(['store', 'destroy']);
    Route::apiResource('posts.comments', Post\PostCommentController::class)->only(['index', 'store']);

    Route::apiResource('comments', Comment\CommentController::class)->except(['index', 'store']);

    Route::delete('/group/join-requests/{join_request}', [Group\JoinRequestController::class, 'destroy']);
    Route::post('/group/join-requests/{join_request}/approval', [Group\JoinRequestController::class, 'approve']);
    Route::delete('/group/join-requests/{join_request}/approval', [Group\JoinRequestController::class, 'reject']);
    Route::get('/groups/{group}/join-requests', [Group\JoinRequestController::class, 'index']);    
    Route::apiSingleton('groups.join-request', Group\UserJoinRequestController::class)
        ->creatable()
        ->only(['show', 'store', 'destroy']);

    Route::apiResource('groups.members', Group\MemberController::class)
        ->parameters([
            'members' => 'user',
        ])
        ->except([
            'store'
        ]);

    Route::apiResource('groups', Group\GroupController::class);

    Route::get('/feed', [Feed\FeedController::class, 'show']);

    Route::apiResource('pages', Page\PageController::class);
    Route::apiSingleton('pages.like', Page\PageLikeController::class)
        ->creatable()
        ->only(['store', 'destroy']);
});