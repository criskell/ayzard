<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::apiSingleton('posts.like', PostLikeController::class)->creatable()->only([
        'store', 'destroy'
    ]);
    Route::apiResource('posts.comments', CommentController::class)->shallow();
    Route::get('/feed', [FeedController::class, 'show']);
});