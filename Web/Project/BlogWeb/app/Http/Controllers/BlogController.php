<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Blogs;
use App\Models\Likes;
use File;

class BlogController extends Controller
{
    public function createBlog(Request $req)
    {
        if(Session::has('admin'))
        {
            if($req->has('submit'))
            {
                $title = $req->input('title');
                $desc = $req->input('desc');
                $tag = $req->input('tag');


                if($req->hasFile('blogPhoto'))
                {
                    $blogImage = time(). '-' . rand(1111111111,9999999999) . '-' . $req->file('blogPhoto')->getClientOriginalName();
                    $req->file('blogPhoto')->move(public_path('storage/Blog'),$blogImage);
                    $data = [
                        'title' => $title,
                        'des' => $desc,
                        'tag' => $tag,
                        'blog_image' => $blogImage,
                    ];                
                    Blogs::Create($data);
                    return redirect()->route('CreateBlog') ->with([
                        'message' => 'Blog Created Successfully!',
                        'alert-type' => 'success', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
                    ]);
                }
                else
                {
                    return redirect()->route('CreateBlog') ->with([
                        'message' => 'Please Enter Photo!',
                        'alert-type' => 'danger', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
                    ])->withInput();
                }
            }
            return view('Admin.createBlog');
        }
        else
        {
            return redirect()->route('Login') ->with([
                    'message' => 'Only Admin Can Access this Page!',
                    'alert-type' => 'danger', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
                ]);
        }
    }

    public function adminViewBlog(Request $req)
    {
        if(Session::has('admin'))
        {
            $blogs = Blogs::all();
            return view('Admin.viewBlog',compact('blogs'));
        }
        else
        {
            return redirect()->route('Login')-> with([
                'message' => 'Only Admin Can Access this Page!',
                'alert-type' => 'danger',
            ]);
        }
    }

    public function adminEditBlog(Request $req,$id)
    {
        if(Session::has('admin'))
        {
            $blog = Blogs::find($id);
            if($req->has('update'))
            {
                $title = $req->input('title');
                $desc = $req->input('desc');
                $tag = $req->input('tag');
                $blogImage = $blog->blog_image;

                if($req->hasFile('blogPhoto'))
                {
                    if(File::exists(public_path("storage/Blog/$blogImage")))
                    {
                        File::delete(public_path("storage/Blog/$blogImage"));
                    }
                    $blogImage = time(). '-' . rand(1111111111,9999999999) . '-' . $req->file('blogPhoto')->getClientOriginalName();
                    $req->file('blogPhoto')->move(public_path('storage/Blog'),$blogImage);
                }
                $blog->title = $title;
                $blog->des = $desc;
                $blog->tag = $tag;
                $blog->blog_image = $blogImage;
                $blog->save();
                return redirect()->route('AdminViewBlog') ->with([
                    'message' => 'Blog Updated Successfully!',
                    'alert-type' => 'success', // you can use different classes for different types of alerts (e.g., success, info, warning, danger)
                ]);
            }
            return view('Admin.editBlog',compact('blog'));
        }
        else
        {
            return redirect()->route('Login')->with([
                'message' => 'Only Admin Can Access this Page!',
                'alert-type' => 'danger',
            ]);
        }
    }

    public function adminDeleteBlog(Request $req,$id)
    {
        if(Session::has('admin'))
        {
            $blog = Blogs::find($id);
            $blog->delete();
            return redirect()->route('AdminViewBlog');
        }
        else
        {
            return redirect()->route('Login')->with([
                'message' => 'Only Admin Can Access this Page!',
                'alert->type' => 'danger',
            ]);
        }
    }

    public function likeBlogView()
    {
        if(Session::has('client'))
        {
            $user = Session::get('client');
            $likeBlogs = Blogs::whereHas('like', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
            if($likeBlogs->isEmpty())
            {
                $error = "You have Not Like Any Blogs";
                return view('User.errorPage',compact('error'));
            }
            return view('User.likeBlog',compact('likeBlogs'));
        }
        else
        {
            return redirect()->route('Login')->with([
                'message' => 'Please Login to Access this Page!',
                'alert-type' => 'danger'
            ]);
        }
    }

    public function likeBlog(Request $req,$id)
    {
        if(Session::has('client'))
        {
            $user = Session::get('client');

            if(Likes::where('user_id',$user->id)->where('blog_id',$id)->exists())
            {
                $likeBlog = Likes::where('user_id',$user->id)->where('blog_id',$id)->first();
                $likeBlog->delete();
            }
            else
            {
                $data = [
                    'user_id' => $user->id,
                    'blog_id' => $id,
                ];
                Likes::create($data);
            }
            return redirect()->back();
        }
        else
        {
            return redirect()->route('Login')->with([
                'message' => 'Please Login to Like the Blog!',
                'alert-type' => 'danger'
                ]);
        }
    }

    public function tagBlog($tag)
    {
        if(Session::has('client'))
        {
            $user = Session::get('client');
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

    public function recentBlog(Request $req)
    {
        $blogs = Blogs::latest()->take(4)->get();
        return $blogs;
    }
}   