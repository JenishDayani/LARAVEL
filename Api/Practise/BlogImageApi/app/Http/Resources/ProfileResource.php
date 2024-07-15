<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return [
        //     'user_id' => $this->user_id,
        //     'mobile' => $this->mobile,
        //     'gender' => $this->gender,
        //     'address' => $this->address,
        //     'city' => $this->city,
        //     'state' => $this->state,
        // ];

        return [
            'user_id' => $this->user_id,
            'mobile' => $this->mobile,
            'address' => $this->address . ', ' . $this->city . ', ' . $this->state,
        ];
    }
}
