<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;


    function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    // function blog()
    // {
    //     return $this->hasMany('App\Models\Blog');
    // }

    function getGroups()
    {
        // return $this->hasMany('App\Models\Group','group_id','groupid');
    }
}
