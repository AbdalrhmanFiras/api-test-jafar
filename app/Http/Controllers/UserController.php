<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 /**
 * @tags User Endpoint
 */
class UserController extends Controller
{
    /**
         * Get All users
         */
    public function index()
    {
        
        $users = User::get();
        if(!$users)
        {
            return response()->json(['message' => 'no user found'],404);
        }
        return $users;
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
