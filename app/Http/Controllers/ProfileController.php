<?php

namespace App\Http\Controllers;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Storage;

/**
 * @tags Profile Endpoint
 */
class ProfileController extends Controller
{

    /**
 * create Profile 
 */
    public function store(Request $request){
        $userId = Auth::id();
        $data = $request->validate([
            'name' => 'required|string|max:225',
            'bio' => 'nullable|string',
            'image'=> 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        if($profileCheck = Profile::where('user_id' , $userId)->first()){
            return response()->json(['message' => 'profile already created'] , 200);
        }
        $data['user_id'] = $userId;
        $profile = Profile::create($data);
        $file = $request->file('image');
        $ext = $file->extension();
        $filename = (string) Str::uuid() . '.' . $ext;
        $path = $file->storeAs('profiles' , $filename,'public')        ;
        $profile->image()->create([
            'url' => $path,
            'type' => 'profile'
        ]);
        return response()->json(['message' => 'Profile create successfully' , 'data' => new ProfileResource($profile)] , 201);
    }


   /**
 * update Profile 
 */
    public function update(Request $request)
    {
    $userId = Auth::id();
    $data = $request->validate([
        'name'  => 'required|string|max:225',
        'bio'   => 'sometimes|string',
        'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048' 
    ]);

    
    $profile = Profile::where('user_id', $userId)->first();
    if (!$profile) {
        return response()->json(['message' => 'Profile not found'], 404);
    }
    $profile->update([
        'name' => $data['name'],
        'bio'  => $data['bio'] ?? $profile->bio,
    ]);

   
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $ext = $file->extension();
        $filename = (string) Str::uuid() . '.' . $ext;

        $path = $file->storeAs('profiles', $filename, 'public');

      $image = $profile->image; 

    if ($image) {
        if (Storage::disk('public')->exists($image->url)) {
            Storage::disk('public')->delete($image->url);
        }

        $image->update([
            'url' => $path,
        ]);
    } else {
        $profile->image()->create([
            'url' => $path,
        ]);
    return response()->json([
        'message' => 'Profile updated successfully',
        'data' => new ProfileResource($profile)
    ], 200);
}

   /**
 * show Profile 
 */
   public function show(){ 
    $userId = Auth::id();
    $profile = Profile::where('user_id', $userId)->first();

 
   if(!$profile) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    return response()->json([
        'message' => 'Profile fetched successfully',
        'data' => new ProfileResource($profile)
    ], 200);
}


/**
 * Get all profiles
 */
    public function index(){
        $profiles = Profile::paginate(5);
            return $this->responseSuccess(
                ['data' => ProfileResource::collection($profiles),
                'pagination' => [
                    'current_page' => $profiles->currentPage(),
                    'last_page' => $profiles->lastPage(),
                    'per_page' => $profiles->perPage(),
                    'total' => $profiles->total(),
                    ]],$profiles ?'Profiles fetched successfully' : 'No Profiles found' , 200);
    }
}



