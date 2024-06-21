<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $hidden = array('pivot');

    function customer()
    {
        return $this->belongsToMany('App\Models\Customer','orders');
    }
}
