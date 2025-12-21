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
            'email'     => $this->user ? $this->user->email:null,
            'phone'          => $this->phone,
            'age'          => $this->age,
            'country'          => $this->country,
            'city'          => $this->city,
            'user_id'    => $this->user_id,
            'image_url'  => $this->when($this->image_url ,function(){
                return $this->image_url;
            }), 
        ];
    }

}