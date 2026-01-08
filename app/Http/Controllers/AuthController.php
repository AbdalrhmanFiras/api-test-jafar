<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


/**
 * @tags Auth Endpoint
 */


class AuthController extends Controller
{

/**
 * login with google
 */
public function googleCallback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::where('email', $googleUser->email)->first();

    if (!$user) {
        $user = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'password' => bcrypt(str()->random(16)),
        ]);
    }

    Auth::login($user);

    return redirect('/home');
}
    /**
     * handle the user register
     */
  public function register(Request $request)
        {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6'
            ]);

            $user = \App\Models\User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Registered',
                'user'    => $user,
                'token'   => $token
            ], 201);
        }

 /**
     * handle the user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Logged in',
            'token' => $token,
            'user' => auth()->user()
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out']);
    }

    public function me()
    {
    
        return response()->json(auth()->user());
    }
}
