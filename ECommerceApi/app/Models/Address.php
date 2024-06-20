<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address'
    ];

    function user()
    {
        return $this->belongTo('App\Models\User','user_id');
    }
}
