<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Str;

class CartResource extends JsonResource
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
            "productTotalAmount" => $this->total_amount,
            "productAmount" => $this->amount,
            "productTax" => $this->tax,
            "qty" => $this->qty,
            "product_id" => Str::uuid()->toString(),
            "product_name" => $this->product->name,
            "product_price" => $this->product->price,
            "product_image" => asset("storage/image/product/{$this->product->p_image}")
        ];
    }
}
