<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function addUser(Request $req)
    {
        $validateData = $req->validate([
            'name' => 'required',
            'email' => ['required','email'],
            'password' => 'required',
            'confirm_password' => ['required','same:password']
        ]);

        $user = User::create($validateData);
        $token = $user->createToken("auth_token")->accessToken;

        return response()->json([
            "UserDetails" => $user,
            // "Token" => $token
        ]);
        return response()->json([
            "message" => "User Added Successfully"
        ]);
    }
}
