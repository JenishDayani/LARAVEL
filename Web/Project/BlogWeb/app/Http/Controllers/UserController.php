<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Blogs;
use App\Models\Profile;
use File;

class UserController extends Controller
{
    // public function index(Request $req)
    // {
    //     $recentBlogs = Blogs::latest()->take(4)->get();
    //     $req->session()->put('recentBlogs',$recentBlogs);
    //     if(Session::has('client'))
    //     {
    //         $user = Session::get('client');
    //         $blogs = Blogs::with(['like' => function ($query) use ($user) {
    //             $query->where('user_id', $user->id);
    //         }])->get();

    //         if($req->input('searchSubmit'))
    //         {
    //             $tag = $req->input('search');
    //             $blogs = Blogs::with(['like' => function ($query) use ($user) {
    //                         $query->where('user_id', $user->id);
    //                     }])->where('tag', 'LIKE', "%$tag%")->get();
    //             // $blogs = Blogs::where('tag', 'LIKE', "%$tag%")->get();
    //         }
    //         if($blogs->isEmpty())
    //         {
    //             $error = "Blogs Not Found";
    //             return view('User.errorPage',compact('error'));
    //         }
    //         // return $blogs;
    //     }
    //     else
    //     {
    //         // $blogs = Blogs::with('like')->get();
    //         $user = 0;
    //         $blogs = Blogs::with(['like' => function ($query) use ($user) {
    //                         $query->where('user_id', $user);
    //                     }])->get();
    //         if($req->input('searchSubmit'))
    //         {
    //             $tag = $req->input('search');
                
    //             $blogs = Blogs::with(['like' => function ($query) use ($user) {
    //                         $query->where('user_id', $user);
    //                     }])->where('tag', 'LIKE', "%$tag%")->get();
    //         }
    //         if($blogs->isEmpty())
    //         {
    //             $error = "Blogs Not Found";
    //             return view('User.errorPage',compact('error'));
    //         }
    //     }
    //     return view('User.home',compact('blogs'));
    // }


    // public function login(Request $req)
    // {
    //     if($req->has('login'))
    //     {
    //         $email = $req->input('email');
    //         $password = $req->input('pass');
    //         if(Auth::attempt(['email' => $email, 'password' => $password]))
    //         {
    //             $user = Auth::user();
    //             if(Auth::user()->role == 1)
    //             {
    //                 // return "Hello" . $user = Auth::user();
    //                 // return response()->json([
    //                 //     'message' => 'Admin Successfully Login'
    //                 // ]);
    //                 // $req->session()->put('admin',$user);
    //                 return redirect()->route('AdminHome');
    //             }
    //             else if(Auth::user()->role == 0)
    //             {
    //                 return $user;
    //                 // $req->session()->put('client',$user);
    //                 return redirect()->route('Home');
    //             }
    //             else
    //             {
    //                 return "Hello World";
    //             }
    //         }
    //         else
    //         {
    //             return response()->json([
    //                 'message' => 'Wrong'
    //             ]);
    //         }
    //         // if(User::where('email',$email)->exists())
    //         // {
    //         //     $user = User::where('email',$email)->first();
    //         //     if($user->password == $password)
    //         //     {
    //         //         // $user->createToken('authToken')->accessToken;
    //         //         if($user->role_id == 1)
    //         //         {
    //         //             $req->session()->put('admin',$user);
    //         //             return redirect()->route('AdminHome');
    //         //         }
    //         //         else
    //         //         {
    //         //             $req->session()->put('client',$user);
    //         //             return redirect()->route('Home');
    //         //         }
    //         //     }
    //         //     else
    //         //     {
    //         //         return redirect()->route('Login')
    //         //         ->with([
    //         //         'message' => 'Password is Incorrect!',
    //         //         'alert-type' => 'danger', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
    //         //     ])->withInput();
    //         //     }
    //         // }
    //         // else
    //         // {
    //         //     return redirect()->route('Login')
    //         //     ->with([
    //         //         'message' => 'Email Not Found!',
    //         //         'alert-type' => 'danger', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
    //         //     ])->withInput();
    //         // }
    //         // return response()->json([
    //         //     'Email' => $email,
    //         //     'Password' => $password
    //         // ]);
    //     }
    //     return view('login');
    // }

    public function login(Request $req)
    {
        if($req->has('login'))
        {
            $email = $req->email;
            $password = $req->pass;
            if(User::where('email',$email)->exists())
            {
                if(Auth::attempt(['email' => $email, 'password' => $password]))
                {
                    $user = Auth::user();
                    if(Auth::user()->role == 1)
                    {
                        return redirect()->route('AdminHome');
                    }
                    else if(Auth::user()->role == 0)
                    {
                        return redirect()->route('Home');
                    }
                }
                else
                {
                    return redirect()->route('Login')->with([
                        'message' => 'Incorrect Password!',
                        'alert-type' => 'danger'
                    ])->withInput();
                }
            }
            else
            {
                return redirect()->route('Login')->with([
                    'message' => 'Email Not Found!',
                    'alert-type' => 'danger'
                ]);
            }
            
        }
        return view('login');
    }

    public function index(Request $req)
    {
        $recentBlogs = recentBlogsGet();
        // $req->session()->put('recentBlogs',$recentBlogs);
        if(isUser())
        {
            $user = Auth::user();
            $blogs = Blogs::with(['like' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])->get();

            if($req->searchSubmit)
            {
                $tag = $req->search;
                $blogs = Blogs::with(['like' => function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        }])->where('tag', 'LIKE', "%$tag%")->get();
            }
            if($blogs->isEmpty())
            {
                $error = "Blogs Not Found";
                return view('User.errorPage',compact('error'));
            }
        }
        else
        {
            $user = 0;
            $blogs = Blogs::with(['like' => function ($query) use ($user) {
                            $query->where('user_id', $user);
                        }])->get();
            if($req->input('searchSubmit'))
            {
                $tag = $req->search;
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
        return view('User.home',compact(['blogs','recentBlogs']));
    }

    public function logout(Request $req)
    {
        Auth::logout();
        return redirect()->route('Home');
    }

    public function viewUserProfile(Request $req)
    {
        $recentBlogs = recentBlogsGet();
        $data = Auth::user();
        $user = User::with('profiles')->find($data->id);
        return view('User.viewProfile',compact(['user','recentBlogs']));
    }

    public function editUserProfile(Request $req)
    {
        $recentBlogs = recentBlogsGet();
        $user = Auth::user();
        $profile = User::with('profiles')->find($user->id);
        if($req->update)
        {
            $req->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'mobile' => 'required|numeric',
                'address' => 'required|string|max:255',
                'gender' => 'required',
                'profile' => 'image|mimes:jpg,jpeg,png|max:204',
                ]);

            // $profileEdit = Profile::where('user_id',$user->id)->first();
            $allData = $req->all();
            updateProfileInformation($allData);
            // $name = $req->name;
            // $email = $req->email;
            // $address = $req->address;
            // $mobile = $req->mobile;
            // $gender = $req->gender;
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

            $user->profile = $profileImage;

            // $user->name = $name;
            // $user->email = $email;

            // $profileEdit->address = $address;
            // $profileEdit->mobile = $mobile;
            // $profileEdit->gender = $gender;

            $user->save();
            // $profileEdit->save();

            // $data = [
            //     'name' => $name,
            //     'email' => $email,
            //     'address' => $address,
            //     'mobile' => $mobile,
            //     'gender' => $gender,
            // ];
            return redirect()->route('UserViewProfile');
        }
        return view('User.editProfile',compact(['profile','recentBlogs']));
    }
}