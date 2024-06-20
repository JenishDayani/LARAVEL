<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable= [
        'coupon',
        'min_order',
        'discount',
        'max',
        'product_id'
    ];

    function order()
    {
        return $this->hasMany('App\Models\Order','offer_id');
    }

    function product()
    {
        return $this->belongTo('App\Models\Product','product_id');
    }
}
