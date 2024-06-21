<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CartResource;

class OrderResource extends JsonResource
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
            "shippingAddress" => $this->d_address,
            "orderId" => $this->order_id,
            "order_date" => $this->created_at->format('d-M-Y'),
            "order_time" => $this->created_at->format('H:m:s'),
            "total_amount" => $this->total,
            "total_tax_amount" => $this->total_tax,
            "bill_amount" => $this->pay_amount,
            "items" => CartResource::collection($this->cart)
        ];
    }
}