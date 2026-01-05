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
             'images' => $this->when(
        $this->images && $this->images->isNotEmpty(),
        fn () => $this->images->pluck('url')
                ),
              'profile_name' => $this->when(
        $this->user && $this->user->profile,
        fn () => $this->user->profile->name
               ),
            'image_url' => $this->when($this->image_url , function(){return $this->image_url;}),
            'comments' => $this->when($this->comments && $this->comments->isNotEmpty(), function() {
           return CommentResource::collection($this->comments);
            }),          
        ];
    }
}
