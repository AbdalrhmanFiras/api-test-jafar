<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

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
            'dec'     => 'required|string|',
            'comment' => 'nullable|string',
            'like'    => 'nullable|string',
            'image'=> 'required|image|mimes:jpg,jpeg,png|max:2048'

        ]);
        $user = Auth::id();
        $data['user_id'] = $user;
        $post = Post::create($data);
        $file = $request->file('image');
        $ext = $file->extension();  
        $filename = (string) Str::uuid() . '.' . $ext;
        $path = $file->storeAs('posts' , $filename , 'public');
        $post->image()->create([
            'url' => $path,
            'type' => 'post'
        ]);
         return $this->responseSuccess(new PostResource($post), 'post created successfully'  , 201);
     }

    /**
     * Edit(update) post
     */

    public function update(Request $request , $id){
 
        try{
        $data = $request->validate([
         'name' => 'sometimes|string',
         'dec' => 'sometimes|string', 
         'comment' => 'sometimes|string', 
         'like' => 'sometimes|string',
         'image'  => 'sometimes|image|mimes:png,jpeg,jpg|max:2048'
         ]);
        $post = Post::findorFail($id);

         if($request->hasFile('image')){
            $file = $request->file('image');
            
            $ext = $file->extension();
            $filename = (string) Str::uuid() . '.' . $ext;
         

         $path = $file->storeAs('posts' , $filename,'public');
         $image = $post->image;

        if ($image) {
            Storage::disk('public')->delete($image->url);
            $image->update(['url' => $path]);
        } else {
            $post->image()->create(['url' => $path]);
        }

    }
        $post->update($data);
         return $this->responseSuccess(new PostResource($post), 'post updated successfully');
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
            $image = $post->image;
           if ($image) {
            $filePath = $image->url; 
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
             $image->delete();
}
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
            $posts = Post::with(['comments','images','user.profile'])->paginate(50);
        
            return $this->responseSuccess(
                ['data' => PostResource::collection($posts),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                    ]],PostResource::collection($posts) ?'Posts fetched successfully' : 'No posts found' , 200);
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
    /**
     * get all user posts
     */
       public function myPosts(){
        
            $posts = Post::where('user_id' , Auth::id())->paginate(10);
            if($posts->isEmpty()){
                return $this->responseError(null , 'no post yet.', 404);
            }
            return $this->responseSuccess(['data' => PostResource::collection($posts),
             'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                    ]
            ] , 'Posts fetched successfully' , 200);
        }
        
 
}
