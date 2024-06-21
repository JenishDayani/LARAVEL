<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbBlog extends Model
{
    use HasFactory;

    function user()
    {
        return $this->belongsTo('App\Models\DbUser','db_user_id');
    }
}
