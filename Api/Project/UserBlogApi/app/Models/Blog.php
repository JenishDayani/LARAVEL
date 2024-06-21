<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'tag',
        'blog_image',
    ];

    protected $hidden = [
        'user_id',
    ];

    function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
