<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\MailController;
use App\Models\User;
use App\Models\Profile;
use File;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{

    public function home()
    {
        return view('Admin.home');
    }

    public function createUser(Request $req)
    {
        if($req->has('submit'))
        {
            $name = $req->input('name');
            $email = $req->input('email');
            $password = Hash::make($req->input('password'));
            $profilePhoto = "Profile.png";

            $address = $req->input('address');
            $mobile = $req->input('mobile');
            $gender = $req->input('gender');

            if(User::where('email',$email)->exists())
            {
                return redirect()->route('CreateUser') ->with([
                    'message' => 'Email Already Exists!',
                    'alert-type' => 'danger', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
                ])->withInput();
            }
            else
            {
                if($req->hasFile('profilePhoto'))
                {                    
                    $image = $req->file('profilePhoto');
                    $profilePhoto = time() . '-' . rand(1111111111,9999999999) . '-' . $image->getClientOriginalName();
                    $req->profilePhoto->move(public_path('storage/User/img'),$profilePhoto);
                }
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'profile' => $profilePhoto,
                ];

                User::Create($data);

                $user = User::where('email',$email)->first();

                $data2 = [
                    'user_id' => $user->id,
                    'address' => $address,
                    'mobile' => $mobile,
                    'gender' => $gender,
                ];

                Profile::Create($data2);
                $sendMail = new MailController;
                $sendMail->index($email,$password);

                return redirect()->route('CreateUser') ->with([
                    'message' => 'User Created Successfully!',
                    'alert-type' => 'success', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
                ]);
            }
        }
        return view('Admin.User');
    }

    public function viewProfile(Request $req)
    {
        $user = Auth::user();
        $admin = User::with('profiles')->find($user->id);
        
        if($req->has('submit'))
        {
            $profile = Profile::where('user_id',$user->id)->first();
            $name = $req->input('name');
            $email = $req->input('email');
            $gender = $req->input('gender');
            $address = $req->input('address');
            $mobile = $req->input('mobile');
            $profileImage = $admin->profile;
                
            if($req->hasFile('profile'))
            {
                if($profileImage != 'Profile.png')
                {
                    if(File::exists(public_path("storage/Admin/img/$profileImage")))
                    {
                        File::delete(public_path("storage/Admin/img/$profileImage"));
                    }
                }
                $profileImage = time() . '-' . rand(1111111111,9999999999) . '-' . $req->profile->getClientOriginalName();
                $req->profile->move(public_path('storage/Admin/img'), $profileImage);
            }

            $user->name = $name;
            $user->email = $email;
            $user->profile = $profileImage;
            $profile->address = $address;
            $profile->mobile = $mobile;
            $profile->gender = $gender;
                
            $user->save();
            $profile->save();

            return redirect()->refresh();
        }
        return view('Admin.profile',compact('admin'));
    }
}