<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;



Route::get('/home', function () {
    return view('welcome');
});

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);


Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        
      
        return 'DB connection OK';
    } catch (\Exception $e
    ) {
        return 'DB connection failed: ' . $e->getMessage();
    }
});









