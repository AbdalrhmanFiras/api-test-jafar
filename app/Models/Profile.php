<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
        protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function image(){
        return $this->morphOne(Image::class , 'imageable');
    }
}
