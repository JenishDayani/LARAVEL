<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; 
use App\Models\User;
use App\Models\Profile;

class UserController extends Controller
{
    
    public function register(Request $req)
    {
        $validateData = $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
            'profileImage' => 'image',

            'mobile' => 'required|numeric|digits:10',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:25',
            'state' => 'required|string|max:25',
        ]);

        $name = $req->name;
        $email = $req->email;
        $password = $req->password;
        $profileImage = 'Profile.jpg';
        $mobile = $req->mobile;
        $gender = $req->gender;
        $address = $req->address;
        $city = $req->city;
        $state = $req->state;

        if($req->file('profileImage'))
        {
            $profileImage = time() . '-' . rand(111111111111111,999999999999999) . '-' . $req->file('profileImage')->getClientOriginalName();
            $req->file('profileImage')->move(public_path('storage/images/User'), $profileImage);
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'profile_image' => $profileImage,
        ];

        $user = User::create($data);

        $data2 = [
            'user_id' => $user->id,
            'mobile' =>$mobile,
            'gender' =>$gender,
            'address' =>$address,
            'city' =>$city,
            'state' =>$state,
        ];

        $profile = Profile::create($data2);

        return response()->json([
            'status' => 'success',
            'message' => 'User Registered Successfully',
        ]);
    }

    public function editProfile(Request $req)
    {
        $validateData = $req->validate([
            'name' => 'string',
            'email' => 'email',

            'mobile' => 'numeric|digits:10',
            'gender' => 'in:Male,Female',
            'address' => 'string|max:255',
            'city' => 'string|max:25',
            'state' => 'string|max:25',
        ]);

        $user = Auth::guard()->user();
        $profile = Profile::where('user_id',$user->id)->first();
        if($user)
        {
            $name = is_null($req->name) ? $user->name : $req->name;
            $email = is_null($req->email) ? $user->email : $req->email;

            $mobile = is_null($req->mobile) ? $profile->mobile : $req->mobile;
            $gender = is_null($req->gender) ? $profile->gender : $req->gender;
            $address = is_null($req->address) ? $profile->address : $req->address;
            $city = is_null($req->city) ? $profile->city : $req->city;
            $state = is_null($req->state) ? $profile->state : $req->state;

            $data = [
                'name' => $name,
                'email' => $email,
            ];

            $data2 = [
                'mobile' => $mobile,
                'gender' => $gender,
                'address' => $address,
                'city' => $city,
                'state' => $state,
            ];

            User::whereId($user->id)->update($data);
            Profile::where('user_id',$user->id)->update($data2);

            return response()->json([
                'status' => 'Success',
                'message' => 'User Profile Updated Successful'
            ]);
        }
        else
        {
            return response()->json([
                'status' => 'Error',
                'message' => 'User Not Found'
            ],404);
        }
    }

    public function editUserImage(Request $req)
    {
        $validateData = $req->validate([
            'profileImage' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        $user = Auth::guard()->user();

        $profileImage = time() . '-' . rand(111111111111111,999999999999999) . '-' . $req->file('profileImage')->getClientOriginalName();
        // return asset('storage');
        if($user->profile_image != 'Profile.jpg')
        {
            if(File::exists(public_path("storage/images/User/$user->profile_image")))
            {
                File::delete(public_path("storage/images/User/$user->profile_image"));
            }
        }

        $req->file('profileImage')->move(public_path("storage/images/User"),$profileImage);

        $data = [
            'profile_image' => $profileImage
        ];

        User::whereId($user->id)->update($data);

        return response()->json([
            'status' => 'Success',
            'message' => 'Profile Picture updated Successful'
        ]);

    }

    public function deleteUserImage()
    {
        $user = Auth::guard()->user();
        if($user->profile_image != 'Profile.jpg')
        {
            if(File::exists(public_path("storage/images/User/$user->profile_image")))
            {
                File::delete(public_path("storage/images/User/$user->profile_image"));
            }
        }

        $data = [
            'profile_image' => 'Profile.jpg'
        ];

        User::whereId($user->id)->update($data);

        return response()->json([
            'status' => 'Success',
            'message' => 'Profile Picture removed Successfully'
        ]);
    }


    public function login(Request $req)
    {
        $validateData = $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $email = $req->email;
        $password = $req->password;
        if(User::where('email',$email)->exists())
        {
            $user = User::where('email',$email)->first();
            if(Hash::check($password, $user->password)) 
            {
                $token = $user->createToken('authToken');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login Success',
                    'token' => $token,
                ],200);
            }
        }
        else
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not Registered',
            ],404);
        }
    }

    public function logout()
    {
        Auth::guard()->user()->token()->revoke();
        Auth::guard()->user()->token()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged Out',
        ]);
    }
}
