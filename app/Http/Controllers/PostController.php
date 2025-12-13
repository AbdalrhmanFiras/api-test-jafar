<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * @tags Post Endpoint
 */
class PostController extends Controller
{
    /**
     * Create post
     */
     public function store(Request $request){

        $data = $request->validate([
            'name'    => 'required|string',
            'dec'     => 'required|string',
            'comment' => 'nullable|string',
            'like'    => 'nullable|string'
        ]);

         $user = Auth::id();
         $data['user_id'] = $user;
         $post = Post::create($data);

         return $this->responseSuccess($post, 'post created successfully'  , 201);

     }

    /**
     * Edit(update) post
     */

    public function edit(Request $request , $id){
 
        try{

        $data = $request->validate([
         'name' => 'sometimes|string',
         'dec' => 'sometimes|string', 
         'comment' => 'sometimes|string', 
         'like' => 'sometimes|string', 
         ]);

        $post = Post::findorFail($id);

        $post->update($data);
         $post->save($data);
         return $this->responseSuccess($post, 'post updated successfully');
        }catch(ModelNotFoundException){
          return $this->responseError(null,'post not found', 404);
        }
    }
    /**
     * Delete(destory) post
     */
    public function delete($id){
        try{
            $post = Post::findOrFail($id);
            $post->delete();
            return $this->responseSuccess([],'Posts deleted successfully', 200);
        }catch(ModelNotFoundException){
            return $this->responseError(null,'post not found', 404);

        }
    }

    /**
     * Get All post
     */
    public function index(){

        try{
            $posts = Post::with('comments')->paginate(10);
            if($posts->isEmpty())  return $this->responseError(null,'No posts found',404);
            return $this->responseSuccess($posts,'Posts fetched successfully', 200);
        }catch (Exception $e) {
        return $this->responseError(null,
            'Something went wrong',500,
        $e->getMessage()
        );
    }
    }
    /**
     * Show(specific) post
     */
    public function show($postId){
        try{
        $post = Post::with('comments')->where('id' , $postId)->firstOrFail();
        return $this->responseSuccess(new PostResource($post),'post fetch successfully',200);
        }catch(ModelNotFoundException){
            return $this->responseError(null,'post not found',404);
        }

    }

}
