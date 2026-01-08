<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/google', function () {
    return Socialite::driver('google')->stateless()->redirect();
});
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);

// Route::get('/debug-env', function () {
//     dd(
//         env('GOOGLE_CLIENT_ID'),
//         env('GOOGLE_CLIENT_SECRET'),
//         env('GOOGLE_REDIRECT_URI')
//     );
// });
Route::get('/users' , [UserController::class , 'index']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get('/post', [PostController::class, 'index'])->withoutMiddleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::post('/post', [PostController::class, 'store']);
    Route::get('/post/comment', [CommentController::class, 'index']);//work
    Route::post('/post/{postId}/comment', [CommentController::class, 'store']);//
    Route::get('/post/myposts', [PostController::class, 'myPosts']);
    Route::post('/post/{Id}', [PostController::class, 'update'])->withoutMiddleware('auth:api');
    Route::get('/post/{Id}', [PostController::class, 'show']);
    Route::delete('/post/{Id}', [PostController::class, 'delete']);
});


Route::middleware('auth:api')->group(function(){
    Route::post('like/{postId}' , [LikeController::class , 'like']);
    Route::post('unlike/{postId}' , [LikeController::class , 'unlike']);
    Route::get('like/total/{postId}' , [LikeController::class , 'totalLikes']);

});


Route::post('/profile/update/' , [ProfileController::class , 'update'])->middleware('auth:api');
Route::post('/profile' , [ProfileController::class , 'store'])
->middleware('auth:api');
Route::get('/profile/' , [ProfileController::class , 'show'])
->middleware('auth:api');
Route::get('/profile/all' , [ProfileController::class , 'index']);