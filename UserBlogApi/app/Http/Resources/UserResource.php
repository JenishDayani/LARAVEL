<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'id' => Str::uuid($this->id)->toString(),
            'name' => $this->name,
            'email' => $this->email,
            'profile_picture' => asset("storage/image/user/$this->user_image"),
        ];
    }
}
