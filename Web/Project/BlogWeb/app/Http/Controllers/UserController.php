<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Blogs;
use App\Models\Profile;
use File;

class UserController extends Controller
{
    public function index(Request $req)
    {
        $recentBlogs = Blogs::latest()->take(4)->get();
        $req->session()->put('recentBlogs',$recentBlogs);
        if(Session::has('client'))
        {
            $user = Session::get('client');
            $blogs = Blogs::with(['like' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])->get();

            if($req->input('searchSubmit'))
            {
                $tag = $req->input('search');
                $blogs = Blogs::with(['like' => function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        }])->where('tag', 'LIKE', "%$tag%")->get();
                // $blogs = Blogs::where('tag', 'LIKE', "%$tag%")->get();
            }
            if($blogs->isEmpty())
            {
                $error = "Blogs Not Found";
                return view('User.errorPage',compact('error'));
            }
            // return $blogs;
        }
        else
        {
            // $blogs = Blogs::with('like')->get();
            $user =0;
            $blogs = Blogs::with(['like' => function ($query) use ($user) {
                            $query->where('user_id', $user);
                        }])->get();
            if($req->input('searchSubmit'))
            {
                $tag = $req->input('search');
                
                $blogs = Blogs::with(['like' => function ($query) use ($user) {
                            $query->where('user_id', $user);
                        }])->where('tag', 'LIKE', "%$tag%")->get();
            }
            if($blogs->isEmpty())
            {
                $error = "Blogs Not Found";
                return view('User.errorPage',compact('error'));
            }
        }
        return view('User.home',compact('blogs'));
    }


    public function login(Request $req)
    {
        if($req->has('login'))
        {
            $email = $req->input('email');
            $password = $req->input('pass');
            if(User::where('email',$email)->exists())
            {
                $user = User::where('email',$email)->first();
                if($user->password == $password)
                {
                    // $user->createToken('authToken')->accessToken;
                    if($user->role_id == 1)
                    {
                        $req->session()->put('admin',$user);
                        return redirect()->route('AdminHome');
                    }
                    else
                    {
                        $req->session()->put('client',$user);
                        return redirect()->route('Home');
                    }
                }
                else
                {
                    return redirect()->route('Login')
                    ->with([
                    'message' => 'Password is Incorrect!',
                    'alert-type' => 'danger', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
                ])->withInput();
                }
            }
            else
            {
                return redirect()->route('Login')
                ->with([
                    'message' => 'Email Not Found!',
                    'alert-type' => 'danger', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
                ])->withInput();
            }
            return response()->json([
                'Email' => $email,
                'Password' => $password
            ]);
        }
        return view('login');
    }

    public function logout(Request $req)
    {
        if($req->session()->has('client'))
        {
            $req->session()->forget('client');
            return redirect()->route('Home');
        }
        elseif($req->session()->has('admin'))
        {
            $req->session()->forget('admin');
            return redirect()->route('Home');
        }
    }

    public function viewUserProfile(Request $req)
    {
        if(Session::has('client'))
        {
            $data = Session::get('client');
            $user = User::with('profiles')->find($data->id);
            return view('User.viewProfile',compact('user'));
        }
        else
        {
            return redirect()->route('Login')->with([
                'message' => 'Please Login First!',
                'alert-type' => 'danger',
            ]);
        }
        
    }

    public function editUserProfile(Request $req)
    {
        if(Session::has('client'))
        {
            $user = Session::get('client');
            $profile = User::with('profiles')->find($user->id);
            if($req->input('update'))
            {
                $profileEdit = Profile::where('user_id',$user->id)->first();
                $name = $req->input('name');
                $email = $req->input('email');
                $address = $req->address;
                $mobile = $req->input('mobile');
                $gender = $req->input('gender');
                $profileImage = $user->profile;
                
                if($req->hasFile('profile'))
                {
                    if($profileImage != "Profile.png")
                    {
                        if(File::exists(public_path("storage/User/img/$profileImage")))
                        {
                            File::delete(public_path("storage/User/img/$profileImage"));
                        }
                    }
                    $profileImage = time(). '-' . rand(1111111111,9999999999) . '-' . $req->file('profile')->getClientOriginalName();
                    $req->file('profile')->move(public_path('storage/User/img'),$profileImage);
                }
                
                $user->name = $name;
                $user->email = $email;
                $user->profile = $profileImage;

                $profileEdit->address = $address;
                $profileEdit->mobile = $mobile;
                $profileEdit->gender = $gender;
                
                $user->save();
                $profileEdit->save();

                $req->session()->put('client',$user);

                $data = [
                    'name' => $name,
                    'email' => $email,
                    'address' => $address,
                    'mobile' => $mobile,
                    'gender' => $gender,
                ];
                    return redirect()->route('UserViewProfile');
            }
            return view('User.editProfile',compact('profile'));
        }
        else
        {
            return redirect()->route('Login')->with([
                'message' => 'Please Login to Edit your Profile!',
                'alert-type' => 'danger',
                ]);
        }
    }

}
