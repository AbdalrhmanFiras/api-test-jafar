<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProfileRequest;

/**
 * @tags Profile Endpoint
 */
class ProfileController extends Controller
{

    /**
 * create Profile 
 */
  public function store(StoreProfileRequest $request)
{
    $userId = Auth::id();
    $email = User::where('id' , $userId)->value('email');
    $data = $request->validated();
    $data['user_id'] = $userId;
    $data['email'] = $email;

    $profile = Profile::create($data);

    if($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = (string) Str::uuid() . '.' . $file->extension();
        $path = $file->storeAs('profiles', $filename, 'public');

        $profile->image()->create([
            'url'  => $path,
            'type' => 'profile'
        ]);
    }

    $profile = $profile->fresh('image');

    return response()->json([
        'message' => 'Profile created successfully',
        'data' => new ProfileResource($profile)
    ], 201);
}
/**
 * Update Profile
 */
public function update(Request $request)
{
    $userId = Auth::id();

    $data = $request->validate([
        'name'  => 'sometimes|string|max:225',
        'bio'   => 'sometimes|string',
        'age' => 'sometimes|integer', 
        'phone' => 'sometimes|string|min:5', 
        'country' => 'sometimes|string', 
        'city' => 'sometimes|string', 
        'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    try {
        $profile = Profile::with('image')->where('user_id', $userId)->firstOrFail();

        // Update basic fields
        $profile->update([
            'name' => $data['name'] ?? $profile->name,
            'bio'  => $data['bio'] ?? $profile->bio,
            'age'  => $data['age'] ?? $profile->age,
            'city'  => $data['city'] ?? $profile->city,
            'country'  => $data['country'] ?? $profile->country,
            'phone'  => $data['phone'] ?? $profile->phone,
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = (string) Str::uuid() . '.' . $file->extension();
            $path = $file->storeAs('profiles', $filename, 'public');

            $image = $profile->image;

            if ($image) {
                if ($image->url && Storage::disk('public')->exists($image->url)) {
                    Storage::disk('public')->delete($image->url);
                }
                $image->update([
                    'url'  => $path,
                    'type' => 'profile',
                ]);
            } else {
                $profile->image()->create([
                    'url'  => $path,
                    'type' => 'profile',
                ]);
            }
        }
        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $profile->fresh('image')
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Profile not found'], 404);
    }
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
        $profiles = Profile::paginate(50);
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



