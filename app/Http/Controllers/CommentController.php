<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
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
        $post = Post::find($postId);
        if(!$postId){
            return response()->json(['post not found'],404);
        }
        $data['post_id'] = $postId;
        $data['user_id'] = $user ?? null; 
        $comment = Comment::create($data);

    return $this->responseSuccess(['data' =>$comment ],'Comment Added Successfully' ,201);
    }

     /**
     * Get all your comments 
     */

    public function index(){
        $user_id = Auth::id();
        $comments = Comment::where('user_id' , $user_id)->paginate(5);
        return $this->responseSuccess(['data'=>$comments] , !$comments ? 
        'Comment fetched successfully' : 'not comments' ,200);
    }

  /**
     * Get all post comments 
     */
    public function getPostcomment($postId){
            $comments = Comment::where('post_id' , $postId)->with('user')
            ->paginate(100);

             if($comments->isEmpty()){
                return $this->responseError(null , 'no comments yet.', 404);
            }
       return $this->responseSuccess(['data' => CommentResource::collection($comments)   
            ] , 'comments fetched successfully' , 200);
    }


}
