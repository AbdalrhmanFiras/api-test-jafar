<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @tags Auth Endpoint
 */


class AuthController extends Controller
{
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

//  /**
//      * handle the user login
//      */
//     public function login(Request $request)
//     {
//         $credentials = $request->validate([
//             'email' => 'required|email',
//             'password' => 'required'
//         ]);

//         if (!$token = JWTAuth::attempt($credentials)) {
//             return response()->json(['message' => 'Invalid credentials'], 401);
//         }

//         return response()->json([
//             'message' => 'Logged in',
//             'token' => $token,
//             'user' => auth()->user()
//         ]);
//     }

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
