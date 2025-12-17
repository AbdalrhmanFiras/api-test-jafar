<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @tags Comment Endpoint
 */


class CommentController extends Controller
{

     /**
     * Create(Add) Comment to Post 
     */
    public function store(Request $request, $postId){
        $data = $request->validate(['context' => 'required|string',  
    ]); 
        $user = Auth::id();
        $postId = Post::where('id' , $postId)->value('id');
        if(!$postId){
            return response()->json(['post not found'],404);
        }
        $data['post_id'] = $postId;
        $data['user_id'] = $user ?? null; 
        $comment = Comment::create($data);

    return response()->json(['Comment Added Successfully' ,$comment],200);
    }



     /**
     * Get all youre Comments 
     */

    public function index(){
        $user_id = Auth::id();
        $comments = Comment::where('user_id' , $user_id)->paginate(5);
            return $this->responseSuccess($comments , !$comments ? 'Comment fetched successfully' : 'not comments' ,200);
    }

}
