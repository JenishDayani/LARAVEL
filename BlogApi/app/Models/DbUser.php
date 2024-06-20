<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class DbUser extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    function blog()
    {
        return $this->hasMany('App\Models\DbBlog');
    }
}
