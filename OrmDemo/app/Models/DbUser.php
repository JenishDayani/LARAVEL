<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbUser extends Model
{
    use HasFactory;

    // function getBlogs()
    // {
    //     return $this->hasMany('App\Models\Blog','dbUser_id');
    // }

    function blog()
    {
        return $this->hasMany('App\Models\Blog','dbUser_id');
    }
}
