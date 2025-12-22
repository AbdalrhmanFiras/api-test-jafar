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
            'email'     => $this->user ,
            'phone'          => $this->phone,
            'age'          => $this->age,
            'country'          => $this->country,
            'city'          => $this->city,
            'user_id'    => $this->user_id,
            'image_url'  => $this->image_url, 
        ];
    }
}