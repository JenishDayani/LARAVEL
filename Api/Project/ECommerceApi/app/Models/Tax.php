<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable=[
        'category',
        'tax_percentage',
    ];

    function product()
    {
        return $this->hasMany('App\Models\Product','product_id');
    }
}
