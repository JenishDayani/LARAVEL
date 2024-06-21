<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'price',
        'p_image',
        'tax_id'
    ];

    function watchlist()
    {
        return $this->hasMany('App\Models\Watchlist','product_id');
    }

    function cart()
    {
        return $this->hasMany('App\Models\Cart','product_id');
    }

    function offer()
    {
        return $this->hasMany('App\Models\Offer','product_id');
    }

    function tax()
    {
        return $this->belongsTo('App\Models\Tax','tax_id');
    }
}
