<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        "order_id",
        "product_id",
        "qty",
        "amount",
        "tax",
        "total_amount",
    ];
    protected $timeStamp = true;
    function order()
    {
        return $this->belongsTo('App\Models\Order','order_id','order_id');
    }

    function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
}
