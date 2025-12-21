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

public function getUrlAttribute($value)
{
    if (!$value) return null;

    $appUrl = env('APP_URL', 'https://a04wg0wwccosgc4kk40kkwo8.168.231.110.172.sslip.io');
    return rtrim($appUrl, '/') . '/storage/' . $value;
}

}
