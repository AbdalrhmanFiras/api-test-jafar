<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
     protected $appends = ['image_url','is_liked'];

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likedUsers(){
        return $this->belongsToMany(User::class , 'post_like');
    }

    public function image(){
        return $this->morphOne(Image::class , 'imageable');
    }

      public function getImageUrlAttribute()
    {
        return $this->image ? $this->image->url : null;
    }

public function getIsLikedAttribute(): bool
{
    $user = Auth::user();
    if (!$user) return false;

    // Check if the current user exists in likedUsers collection if loaded
    if ($this->relationLoaded('likedUsers')) {
        return $this->likedUsers->contains($user->id);
    }

    // Otherwise query the pivot table directly
    return $this->likedUsers()->where('user_id', $user->id)->exists();
}
     public function getLikesCountAttribute(): int
    {
        return $this->likedUsers()->count();
    }


    // public static function boot(){
    //     parent::boot();

    //     // static::created(function($model){// after 
    //     //     $model->dec = 'from created boot';
    //     //     $model->save();
    //     // });
    //     // static::creating(function($model){// durning the creation 
    //     //     $model->dec = 'from creating boot';
    //     // });
    //     // static::saving(function($model){// durning the creation 
    //     //     $model->dec = 'from saving boot';
    //     // });

    //     static::replicating(function($model){
    //         $model->dec = 'dec 2';
    //     });
    // }
}
