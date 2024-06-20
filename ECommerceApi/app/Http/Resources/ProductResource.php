<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "id" => Str::uuid()->toString(),
            "name" => $this->name,
            "price" => $this->price,
            "product_image" => asset("storage/image/product/$this->p_image")
        ];
    }
}
