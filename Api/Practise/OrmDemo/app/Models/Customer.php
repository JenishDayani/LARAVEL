<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
        protected $hidden = array('pivot');


    function product()
    {
        return $this->belongsToMany('App\Models\Product','orders');
    }

    function productPrice500()
    {
        return $this->product()->where('price',500);
    }
}
