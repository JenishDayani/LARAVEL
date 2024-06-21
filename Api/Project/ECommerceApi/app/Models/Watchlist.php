<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }

    function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
