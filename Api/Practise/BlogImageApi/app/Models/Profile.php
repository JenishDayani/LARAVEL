<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\User;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mobile',
        'gender',
        'address',
        'city',
        'state',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
