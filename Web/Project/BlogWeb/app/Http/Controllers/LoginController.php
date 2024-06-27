<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Blogs;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{ 
    public function login(Request $req)
    {
        if($req->has('login'))
        {
            $req->validate([
                'email' => 'required|email',
                'pass' => 'required'
            ]);

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
            if($req->searchSubmit)
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


    public function tagBlog($tag)
    {
        if(isUser())
        {
            $user = Auth::user();
            $blogs = Blogs::with(['like' => function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    }])->where('tag', 'LIKE', "%$tag%")->get();

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
                    }])->where('tag', 'LIKE', "%$tag%")->get();
            
            if($blogs->isEmpty())
            {
                $error = "Blogs Not Found";
                return view('User.errorPage',compact('error'));
            }
        }
        return view('User.home',compact('blogs'));
    }
}