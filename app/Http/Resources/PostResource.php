<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'dec' => $this->dec,
            'profile_name' => optional($this->user->profile)->name,
            'profile_image' => optional(optional($this->user->profile)->image)->url,
            'image_url' => $this->when($this->image_url , function(){
                return $this->image_url;}),
                 'likes_count' => $this->likes_count,
                'is_liked' => $this->is_liked,
                   
        ];
    }
}
