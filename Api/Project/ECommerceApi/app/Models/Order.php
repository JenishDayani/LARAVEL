<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'd_address',
        'total',
        'total_tax',
        'pay_amount',
        'offer_id',
        'discount_amount',
        'final_amount',
    ];

    function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    function cart()
    {
        return $this->hasMany('App\Models\Cart','order_id','order_id');
    }

    function offer()
    {
        return $this->belongTo('App\Models\Offer','offer_id');
    }
}
