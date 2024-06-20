<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'des',
        'tag',
        'blog_image'
    ];

    function like()
    {
        return $this->hasMany(Likes::class,'blog_id');
    }
}
