<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\DbUser;

class UserController extends Controller
{
    public function addUser(Request $req)
    {
        if(!(DbUser::where('user_email',$req->input('Email'))->exists()))
        {
            $user = new DbUser;
            $user->uuid = Str::uuid()->toString();
            $user->user_name = $req->input('Name');
            $user->user_email = $req->input('Email');
            $user->user_password = $req->input('Password');
            $user->user_mobile = $req->input('PhoneNumber');


            if(is_null($req->ProfilePhoto))
            {
                $user->user_image = "Profile.jpg";
            }
            else
            {
                $imageName = time().'-'.rand(0000000000,9999999999).'-'.$req->ProfilePhoto->getClientOriginalName();
                $req->ProfilePhoto->storeAs('image/profile',$imageName);
                $user->user_image = $imageName;
            }
            $user->save();
            return response()->json([
                "message" => "User Created Successfully"
            ],201);
        }
        else{
            return response()->json([
                "message" => "Email is Already Registered"
            ]);
        }
    }
}
