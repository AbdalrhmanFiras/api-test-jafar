<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        
      
        return 'DB connection OK';
    } catch (\Exception $e
    ) {
        return 'DB connection failed: ' . $e->getMessage();
    }
});









