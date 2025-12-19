<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ['url', 'imageable_id', 'imageable_type'];
    public function imageable() {
        return $this->morphTo();
    }

    public function getFullUrlAttribute()
    {
        // If you store images in storage/app/public
        return asset('storage/' . $this->url);
    }


}
