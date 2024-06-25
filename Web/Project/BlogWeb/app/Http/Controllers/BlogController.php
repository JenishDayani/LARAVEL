<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Blogs;
use App\Models\Likes;
use File;
use Illuminate\Support\Facades\Auth;


class BlogController extends Controller
{
    public function createBlog(Request $req)
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

    public function adminViewBlog(Request $req)
    {
        $blogs = Blogs::all();
        return view('Admin.viewBlog',compact('blogs'));
    }

    public function adminEditBlog(Request $req,$id)
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

    public function adminDeleteBlog(Request $req,$id)
    {
        $blog = Blogs::find($id);
        $blog->delete();
        return redirect()->route('AdminViewBlog');
    }

    public function likeBlogView()
    {
        $recentBlogs = recentBlogsGet();
        $user = Auth::user();
        $likeBlogs = Blogs::whereHas('like', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })->get();
        if($likeBlogs->isEmpty())
        {
            $error = "You have Not Like Any Blogs";
            return view('User.errorPage',compact('error'));
        }
        return view('User.likeBlog',compact(['likeBlogs','recentBlogs']));

    }

    public function likeBlog(Request $req,$id)
    {
        $user = Auth::user();
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

    // public function tagBlog($tag)
    // {
    //     $recentBlogs = recentBlogsGet();
    //     if(Auth::check())
    //     {
    //         $user = Auth::user();
    //         $blogs = Blogs::with(['like' => function ($query) use ($user) {
    //                     $query->where('user_id', $user->id);
    //                 }])->where('tag', 'LIKE', "%$tag%")->get();
    //         if($blogs->isEmpty())
    //         {
    //             $error = "Blogs Not Found";
    //             return view('User.errorPage',compact('error'));
    //         }
    //     }
    //     else
    //     {
    //         $user = 0;            
    //         $blogs = Blogs::with(['like' => function ($query) use ($user) {
    //                     $query->where('user_id', $user);
    //                 }])->where('tag', 'LIKE', "%$tag%")->get();

    //         if($blogs->isEmpty())
    //         {
    //             $error = "Blogs Not Found";
    //             return view('User.errorPage',compact('error'));
    //         }
    //     }
    //     return view('User.home',compact(['blogs','recentBlogs']));
    // }

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

    // public function recentBlog(Request $req)
    // {
    //     $blogs = Blogs::latest()->take(4)->get();
    //     return $blogs;
    // }
}   