<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'bio'        => $this->bio,
            'user_id'    => $this->user_id,
            'image_url'  => $this->when($this->image_url && $this->image_url,function(){
                return $this->image_url;
            }), 
        ];
    }
}