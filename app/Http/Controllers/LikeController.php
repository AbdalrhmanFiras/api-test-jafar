<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/**
 * @tags Like Endpoint
 */
class LikeController extends Controller
{
    /**
     * Added Like to Post
     */
   public function like($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->likes()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'You already liked this post'], 400);
        }

        $post->likes()->attach(Auth::id());

        return response()->json(['message' => 'Post liked successfully'],200);
    }

    
    /**
    * remove Like to Post
    */
    public function unlike($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->likes()->where('user_id', Auth::id())->exists()) {
             $post->likes()->detach(Auth::id());
        }
        return response()->json(['message' => 'Post unliked successfully'],200);
    }
    
    /**
    * return the likes of Post
    */
     public function totalLikes($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

       $likesCount = $post->likes()->count();

        return response()->json([
        'post_id' => $post->id,
        'total_likes' => $likesCount
    ], 200);
    }
}
