<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            // 'id' => $this->id,
            // 'uuid' => $this->uuid,
            'id' => $this->uuid,
            'name' => $this->name,
            'mobile' => $this->phone,
            'email' => $this->email,
            'profile_picture' => $this->image
        ];
    }
}
