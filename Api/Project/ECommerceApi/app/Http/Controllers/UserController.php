<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use File;

class UserController extends Controller
{
    public function addUser(Request $req)
    {
        $validateData = $req->validate([
            'Name' => 'required',
            'Address' => 'required',
            'Image' => 'image',
            'Email' => 'required|email',
            'Password' => 'required',
            'ConfirmPassword' => 'required|same:Password',
        ]);
        
        if(!(User::where('email',$req->input('Email'))->exists()))
        {    
            $role = 2;
            if($req->input('Admin')=='true')
            {
                $role = 1;
            }
            $image = 'Profile.jpg';
            if($req->file('Image'))
            {
                $image = time().rand(000000000,999999999).$req->file('Image')->getClientOriginalName();
                $req->file('Image')->storeAs('image/user',$image,'public');
            }
            $data = [
                'role_id' => $role,
                'name' => $req->input('Name'),
                'email' => $req->input('Email'),
                'password' => $req->input('Password'),
                'u_image' => $image,
                'address' => $req->input('Address'),
            ];
            $user = User::create($data);
            return response()->json([
                'message' => 'User Registered Successfully'
            ]);
        }
        else
        {
            return response()->json([
                'message' => 'Email Already Registered'
            ]);
        }
    }

    public function login(Request $req)
    {
        $validateData = $req->validate([
            'Email' => 'required|email',
            'Password' => 'required'
        ]);

        $user = User::where('email',$req->input('Email'))->first();

        if($user)
        {
            if($user->password == $req->input('Password'))
            {
                $token = $user->createToken('authToken')->accessToken;
                return response()->json([
                    'message' => "User Login Successfully",
                    'token' => $token,
                ]);
            }
            else
            {
                return response()->json([
                    'message' => 'Wrong Password'
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'User Not Found'
            ]);
        }
    }


    public function logout(Request $req)
    {
        Auth::guard()->user()->token()->revoke();
        Auth::guard()->user()->token()->delete();

        return response()->json([
            'message' => 'User Logout Successfully'
        ]);
    }


    public function viewUsers()
    {
        $user = Auth::guard()->user();
        if($user->role_id == 1)
        {
            $users = User::all();
            return response()->json($users);
        }
        else
        {
            return response()->json([
                'message' => 'Only Admin can see All Users'
            ]);
        }
    }

    public function viewUser()
    {
        $user = Auth::guard()->user();
        return response()->json($user);
    }

    public function editUser(Request $req)
    {
        $user = Auth::guard()->user();

        $user->name = ($req->input('Name'))? $req->input('Name') : $user->name;
        $user->email = ($req->input('Email'))? $req->input('Email') : $user->email;
        $user->password = ($req->input('Password'))? $req->input('Password') : $user->password;
        $user->address = ($req->input('Address'))? $req->input('Address') : $user->address;
        $user->save();

        return response()->json([
            'message' => 'User Updated Successfully'
        ]);

    }


    public function editUserImage(Request $req)
    {
        $user = Auth::guard()->user();

        $validateData = $req->validate([
            'Profile' => 'image',
        ]);

        if($req->file('Profile'))
        {
            if($user->u_image != 'Profile.jpg')
            {
                if (File::exists(public_path("storage/image/user/$user->u_image"))) 
                {
                    File::delete(public_path("storage/image/user/$user->u_image"));
                }
            }
            $image = time().rand(000000000,999999999).$req->file('Profile')->getClientOriginalName();
            $req->Profile->storeAs('image/user',$image,'public');
            $user->u_image = $image;
            $user->save();

            return response()->json([
                "message" => "Profile Image Changed Successfully"
            ]);
        }
    }

    public function deleteUser(Request $req)
    {
        $user = Auth::guard()->user();
        $user->delete();
        return response()->json([
            'message' => 'User Deleted Successfully'
        ]);
    }
}
