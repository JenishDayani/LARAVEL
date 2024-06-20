<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    function user()
    {
        return $this->belongsTo('App\Models\DbUser','dbUser_id');
    }

    function tag()
    {
        return $this->belongsToMany('App\Models\Tag','blog_tags');
    }

    function hello()
    {
        // $tagArray = array('tags.id');
        return $this->tag()->get();
    }
}
