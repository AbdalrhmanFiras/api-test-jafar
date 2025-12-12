<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get('/post/index', [PostController::class, 'index'])->withoutMiddleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::post('/post/create', [PostController::class, 'store']);
    Route::get('/post/{Id}', [PostController::class, 'show']);
    Route::put('/post/edit/{Id}', [PostController::class, 'edit']);
    Route::delete('/post/delete/{Id}', [PostController::class, 'delete']);
});


Route::middleware('auth:api')->group(function(){
        Route::post('post/{postId}/comment', [CommentController::class , 'store']);
        Route::get('post/comment', [CommentController::class , 'index']);
        
    });


Route::middleware('auth:api')->group(function(){

    Route::post('like/{postId}' , [LikeController::class , 'like']);
    Route::post('unlike/{postId}' , [LikeController::class , 'unlike']);
    Route::get('like/total/{postId}' , [LikeController::class , 'totalLikes']);

});