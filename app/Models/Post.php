<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->belongsToMany(User::class , 'post_like');
    }

    public function image(){
        return $this->morphOne(Image::class , 'imageable');
    }


    public static function boot(){
        parent::boot();

        // static::created(function($model){// after 
        //     $model->dec = 'from created boot';
        //     $model->save();
        // });
        // static::creating(function($model){// durning the creation 
        //     $model->dec = 'from creating boot';
        // });
        // static::saving(function($model){// durning the creation 
        //     $model->dec = 'from saving boot';
        // });

        static::replicating(function($model){
            $model->dec = 'dec 2';
        });
    }
}
