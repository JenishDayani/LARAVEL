<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function addUser()
    {
        // return view('users');
        // return view('viewusers');
        return view('deleteusers');
    }
}
