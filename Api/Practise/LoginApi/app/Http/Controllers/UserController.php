<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class UserController extends Controller
{

    public function register(Request $req)
    {
        $validateData = $req->validate([
            'name' =>'required',
            'email' =>'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if(!(User::where('email',$req->input('email'))->exists()))
        {
            $user = User::create($validateData);
            return response()->json([
                "message" => "User Registered Successfully"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "Email Already Registered"
            ]);
        }
    }


    public function index()
    {
        $user = Auth::guard()->user();
        $customId = Str::uuid($user->id)->toString();
        return response()->json([
            'id' => $customId,
            'UserInfo' =>$user
        ]);
    }

    public function login(Request $req)
    {
        if(User::where('email',$req->email)->exists())
        {
            $data = User::where('email',$req->email)->first();
            if($data->password == $req->password)
            {
                $token = $data->createToken('authToken');
                return response()->json([
                    "message" => "User Login Successfully",
                    "Token" => $token,
                    "UserInfo" => $data
                ]);
            }
            else
            {
                return response()->json([
                    "message" => "Wrong Password"
                ]);
            }
        }
        else
        {
            return response()->json([
                "message" => "User Not Found"
            ]);
        }
    }

    public function logout()
    {
        Auth::guard()->user()->token()->revoke();
        Auth::guard()->user()->token()->delete();
        return response()->json([
            "message" => "User Logout Successfully"
        ]);
    }
}
