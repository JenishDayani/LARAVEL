<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BlogResource;
use App\Http\Resources\BlogUserResource;
use File;


class BlogController extends Controller
{
    public function addBlog(Request $req)
    {
        $validateData = $req->validate([
            'Title' => 'required',
            'Body' => 'required',
            'Tag' => 'required',
        ]);

        $user = Auth::guard()->user();
        if($req->file('Image'))
            {
                $image = time() . rand(000000000,999999999) . $req->Image->getClientOriginalName();
                $req->Image->storeAs('image/blog',$image,'public');
            }
            else
            {
                $image = "Blog.jpg";
            }

        $data = [
            'title' => $req->input('Title'),
            'body' => $req->input('Body'),
            'tag' => $req->input('Tag'),
            'user_id' => $user->id,
            'blog_image' => $image,
        ];

        $blog = Blog::create($data);

        return response()->json([
            "message" => "Blog Added Successfully"
        ]);
    }

    public function viewBlog()
    {
        $user = Auth::guard()->user();
        $blog = Blog::where('user_id',$user->id)->get();
        return BlogResource::collection($blog);
    }

    public function editBlog(Request $req,$id)
    {   
        $user = Auth::guard()->user();
        $blog = Blog::find($id);
        if($blog->user_id == $user->id)
        {
            $blog->title = is_null($req->input('Title'))? $blog->title :$req->input('Title');
            $blog->body = is_null($req->input('Body'))? $blog->body :$req->input('Body');
            $blog->tag = is_null($req->input('Tag'))? $blog->tag :$req->input('Tag');
            $blog->save();
            return response()->json([
                "message" => "Blog Updated Successfully"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "Wrong Blog ID"
            ]);
        }
    }

    public function deleteBlog(Request $req,$id)
    {   
        $user = Auth::guard()->user();
        $blog = Blog::find($id);
        if($blog->user_id == $user->id)
        {
            $blog->delete();
            return response()->json([
                "message" => "Blog Deleted Successfully"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "You have enter wrong Blog ID"
            ]);
        }
    }

    public function blogUser()
    {
        $data = Blog::with('user')->get();
        $blog = BlogUserResource::collection($data);

        // return $blog;
        return response()->json($blog);
        // return response()->json($data);
    }


    public function editBlogImage($id,Request $req)
    {
        $user = Auth::guard()->user();
        $blog = Blog::find($id);

        $validateData = $req->validate([
            'Image' => 'required|image'
        ]);

        if($blog->user_id == $user->id)
        {
            if($blog->blog_image != 'Blog.jpg')
            {
                if (File::exists(public_path("storage/image/blog/$blog->blog_image"))) {
                    File::delete(public_path("storage/image/blog/$blog->blog_image"));
                }
            }
            $image = time() . rand(000000000,999999999) . $req->Image->getClientOriginalName();
            $req->Image->storeAs('image/blog',$image,'public');
            $blog->blog_image = $image;
            $blog->save();
            return response()->json([
                "message" => "Blog Image Changed Successfully"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "Wrong Blog ID"
            ]);
        }
    }
}


// use File;

// if (File::exists(public_path("storage/image/blog/$blog->blog_image"))) {
//      File::delete(public_path("storage/image/blog/$blog->blog_image"));
//  }