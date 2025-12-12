<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    
    protected $guarded = ['id'];

    public function posts(){
        return $this->belongsTo(Post::class);
    }
    public function users(){
        return $this->belongsTo(User::class);
    }
}
