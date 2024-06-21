<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Blog;

class Likes extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blog_id',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }


    function blog()
    {
        return $this->belongsTo(Blog::class);
    }

}
